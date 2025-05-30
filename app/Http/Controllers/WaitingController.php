<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Waiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waiting=Waiting::orderby('jam_daftar','desc')->get();
        $kategori=Kategori::orderby('updated_at','desc')->get();
        return view('Backend.v_beranda.waiting', [
            'judul'=>'Waiting List',
            'index'=>$waiting,
            'create'=>$kategori,
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
        $request->validate([
            'kategori_id' => 'required',
            'nama_pelanggan' => 'required|max:255'
        ]);

        // Simpan atau cari pelanggan berdasarkan nama
        $pelanggan = Pelanggan::Create([
            'nama' => $request->nama_pelanggan,
            'user_id' => Auth::id()
        ]);

        Waiting::create([
            'kategori_id' => $request->kategori_id,
            'pelanggan_id' => $pelanggan->id,
            'user_id' => Auth::user()->id, 
            'jam_daftar' => now(),   
        ]);
        return redirect()->route('backend.waitinglist.index')->with('success', 'Data Berhasil Tersimpan');
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
        $waiting = Waiting::findOrFail($id);
        $pelangganId = $waiting->pelanggan_id;
        $waiting->delete();
        Pelanggan::where('id', $pelangganId)->delete();

        return redirect()->back()->with('success', 'Data waiting list berhasil dihapus.');
    }
}
