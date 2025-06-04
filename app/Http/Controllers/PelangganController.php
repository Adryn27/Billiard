<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan=Pelanggan::orderby('updated_at','desc')->get();
        return view('backend.v_pelanggan.index', [
            'judul'=>'Customer',
            'index'=>$pelanggan,
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Cek user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Jika belum ada, buat user
            $user = User::create([
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make('default_password'), // password acak
                'role' => '2', // role pelanggan
                'hp' => '0000000000000', // default
                'foto' => $googleUser->getAvatar(),
            ]);

            // Buat entri di tabel pelanggan
            Pelanggan::create([
                'user_id' => $user->id,
                'nama' => $googleUser->getName(),
                'provider' => 'google',
            ]);
        }

        // Login user
        Auth::login($user);

        return redirect()->intended('/'); // atau tujuan login
    }

    public function logout(Request $request) { 
        Auth::logout(); // Logout pengguna 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect('/'); 
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
