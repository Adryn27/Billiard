<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=User::orderby('updated_at','desc')->get();
        return view('backend.v_user.index', [
            'judul'=>'User',
            'index'=>$user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'=>'required|max:255',
            'email'=>'required|max:255|email|unique:user',
            'role'=>'required',
            'hp'=>'required|min:7|max:13',
            'password'=>'required|min:4|confirmed',
            'foto'=>'image|mimes:jpeg,jpg,png,gif|file|max:1024'
        ],
        $message=[
            'foto.image'=>'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif',
            'foto.max'=>'Ukuran file gambar maksimal adalah 1024 KB',
        ]);

        //menggunakan imageHelper
        if($request->file('foto')){
            $file = $request->file('foto');
            $extension=$file->getClientOriginalExtension();
            $originalFileName=date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory='storage/img-user/';

            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 400, 500);

            $validatedData['foto']=$originalFileName;
        }

        //password kombinasi
        $password=$request->input('password');
        $pattern='/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';
        if(preg_match($pattern, $password)){
            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData, $message);
            return redirect()->route('backend.user.index')->with('success','Data berhasil disimpan');
        } else {
            return redirect()->back()->withErrors(['password'=>'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka dan simbol karakter. ']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user=User::findOrFail($id);
        $rules=[
            'nama'=>'required|max:255',
            'role'=>'required',
            'hp'=>'required|min:7|max:13',
            'foto'=>'image|mimes:jpeg,jpg,png,gif|file|max:1024'
        ];
        $message=[
            'foto.image'=>'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif',
            'foto.max'=>'Ukuran file gambar maksimal adalah 1024 KB',
            'nama.required'=>'Isian nama wajib diisi'
        ];
        if($request->email != $user->email){
            $rules['email']='required|max:255|email|unique:user';
        }

        $validatedData=$request->validate($rules, $message);
        //menggunakan imageHelper
        if($request->file('foto')){
            // Hapus gambar lama
            if($user->foto){
                $oldImagePath=public_path('storage/img-user/') . $user->foto;
                if(file_exists($oldImagePath)){
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('foto');
            $extension=$file->getClientOriginalExtension();
            $originalFileName=date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory='storage/img-user/';
            // Simpan gambar asli
            $fileName= ImageHelper::uploadAndResize($file, $directory, $originalFileName, 400, 500);

            $validatedData['foto']=$fileName;
        }
        $user->update($validatedData);
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user= User::findOrFail($id);
        if($user->foto){
            $oldImagePath=public_path('storage/img-user/') . $user->foto;
            if(file_exists($oldImagePath)){
                unlink($oldImagePath);
            }
        }
        $user->delete();
        return redirect()->route('backend.user.index')->with('success','Data Berhasil Dihapus');
    }

    public function profil()
    {
        $user=Auth::user();
        return view('Backend.v_user.profile', [
            'judul'=>'My Profile',
            'user'=>$user,
        ]);
    }
}
