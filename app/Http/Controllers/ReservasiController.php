<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $reservasi=Reservasi::orderby('created_at','asc')->whereNot(function($query) {$query->where('proses', '2')->where('status_bayar', '1');})->get();
        $now = now();
        $reservasi = Reservasi::where(function($query) use ($now) {
            $query->where(function ($q) {
                $q->where('proses', '2')
                  ->where('status_bayar', '1');
            })
            ->where('updated_at', '>=', $now->subMinutes(10)) // masih tampil 10 menit setelah complete
            ->orWhere(function ($q) {
                $q->where('status_bayar', '!=', '1')
                  ->orWhere('proses', '!=', '2');
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();
        return view('Backend.v_beranda.reservasi', [
            'judul'=>'Reservation List',
            'index'=>$reservasi
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
        $reservasi = Reservasi::findOrFail($id);

        $request->validate([
            'metode_bayar' => 'required',
        ]);
        $reservasi->metode_bayar = $request->metode_bayar;
        $reservasi->status_bayar = '1'; // tandai sebagai dibayar
        $reservasi->save();

        session([
            'dibayarkan' => $request->dibayar,
            'kembalian' => $request->kembalian
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
