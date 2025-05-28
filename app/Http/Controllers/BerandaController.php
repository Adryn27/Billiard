<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function updateStatusReservasi()
    {
        $now = Carbon::now();

        // 1. Update reservasi yang sudah waktunya selesai
        $selesai = Reservasi::where('proses', '1') // sedang berlangsung
            ->where('jam_berakhir', '<=', $now)
            ->get();

        foreach ($selesai as $reservasi) {
            $reservasi->proses = '2'; // completed
            $reservasi->save();

            if ($reservasi->meja) {
                $reservasi->meja->status = '0'; // tersedia
                $reservasi->meja->save();
            }
        }

        // 2. Update reservasi yang waktunya baru mulai
        $mulai = Reservasi::where('proses', '0') // masih pending (belum dimulai)
            ->where('jam_mulai', '<=', $now)
            ->get();

        foreach ($mulai as $reservasi) {
            $reservasi->proses = '1'; // on-going
            $reservasi->save();

            if ($reservasi->meja) {
                $reservasi->meja->status = '1'; // digunakan
                $reservasi->meja->save();
            }
        }
    }

    public function index()
    {
        $this->updateStatusReservasi();
        $meja=Meja::orderby('nomor_meja','asc')->with('reservasi')->get();
        return view('backend.v_beranda.beranda', [
            'judul'=>'Dashboard',
            'index'=>$meja
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
            'pelanggan_id' => 'required|max:255',
            'meja_id' => 'required',
            'jam_mulai' => 'required',
            'durasi' => 'required|numeric|min:1'
        ]);

        // Hitung jam berakhir dari jam mulai + durasi
        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamBerakhir = $jamMulai->copy()->addHours((int)$request->durasi);

        // Cek apakah ada reservasi aktif di meja yang sama dan waktu saling tumpang tindih
        $conflict = Reservasi::where('meja_id', $request->meja_id)
            ->where(function ($query) use ($jamMulai, $jamBerakhir) {
                $query->whereBetween('jam_mulai', [$jamMulai, $jamBerakhir])
                    ->orWhereBetween('jam_berakhir', [$jamMulai, $jamBerakhir])
                    ->orWhere(function ($query) use ($jamMulai, $jamBerakhir) {
                        $query->where('jam_mulai', '<=', $jamMulai)
                                ->where('jam_berakhir', '>=', $jamBerakhir);
                    });
            })
            ->where('proses', '!=', '2') // Abaikan yang sudah selesai
            ->exists();

        if ($conflict) {
            return redirect()->back()->withErrors(['meja_id' => 'Meja sedang digunakan pada rentang waktu tersebut. Silakan pilih waktu lain.'])->withInput();
        }

        // Cari atau buat pelanggan berdasarkan nama (untuk pelanggan langsung)
        $pelanggan = Pelanggan::firstOrCreate(
            ['nama' => $request->pelanggan_id]
        );

        // Simpan data reservasi
        $reservasi = Reservasi::create([
            'pelanggan_id' => $pelanggan->id,
            'user_id'      => Auth::user()->id,
            'meja_id'      => $request->meja_id,
            'jam_mulai'    => $jamMulai,
            'jam_berakhir' => $jamBerakhir,
            'proses'       => '1', // On-Going
            'status_bayar' => '0', // Belum dibayar
        ]);

        // Update status meja menjadi 1 (on-going)
        $meja = Meja::find($request->meja_id);
        if ($meja) {
            $meja->status = '1';
            $meja->save();
        }

        return redirect()->route('backend.dashboard.index')->with('success', 'Data Berhasil Tersimpan');
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
