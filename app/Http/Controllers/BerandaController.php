<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use App\Models\Waiting;
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
        // $now = Carbon::now();
        // $mejas = Meja::all();

        // foreach ($mejas as $meja) {
        //     $reservasiAktif = Reservasi::where('meja_id', $meja->id)
        //         ->whereIn('proses', ['0', '1'])
        //         ->orderBy('jam_mulai', 'desc')
        //         ->first();

        //     if ($reservasiAktif) {
        //         $jamMulai = Carbon::parse($reservasiAktif->jam_mulai);
        //         $jamBerakhir = Carbon::parse($reservasiAktif->jam_berakhir);

        //         if ($now->lt($jamMulai)) {
        //             $bgColor = 'bg-warning'; // akan datang
        //             $meja->status = '2'; // dipesan
        //         } elseif ($now->between($jamMulai, $jamBerakhir)) {
        //             $bgColor = 'bg-danger'; // sedang dipakai
        //             $meja->status = '1'; // digunakan
        //         } else {
        //             $bgColor = 'bg-success'; // lewat waktunya
        //             $meja->status = '0'; // tersedia
        //         }
        //     } else {
        //         $bgColor = 'bg-success'; // tidak ada reservasi
        //         $meja->status = '0'; // tersedia
        //     }

        //     $meja->save(); // Simpan status jika perlu
        //     $meja->bgColor = $bgColor;
        // }
        $now = Carbon::now();

        // Update reservasi selesai
        Reservasi::where('proses', '1')
            ->where('jam_berakhir', '<=', $now)
            ->update(['proses' => '2']);

        // Update reservasi mulai
        Reservasi::where('proses', '0')
            ->where('jam_mulai', '<=', $now)
            ->update(['proses' => '1']);

        // Update status meja
        $mejaIdsAktif = Reservasi::whereIn('proses', ['0', '1'])->pluck('meja_id')->unique();

        $mejas = Meja::all();

        foreach ($mejas as $meja) {
            if ($mejaIdsAktif->contains($meja->id)) {
                $reservasiAktif = Reservasi::where('meja_id', $meja->id)
                    ->whereIn('proses', ['0', '1'])
                    ->orderBy('jam_mulai', 'desc')
                    ->first();

                if ($reservasiAktif->proses == '1') {
                    $meja->status = '1'; // digunakan
                } else {
                    $meja->status = '2'; // dipesan
                }
            } else {
                $meja->status = '0'; // tersedia
            }
            $meja->save();
        }
        // $now = Carbon::now();

        // // 1. Update reservasi yang sudah waktunya selesai
        // $selesai = Reservasi::where('proses', '1') // sedang berlangsung
        //     ->where('jam_berakhir', '<=', $now)
        //     ->get();

        // foreach ($selesai as $reservasi) {
        //     $reservasi->proses = '2'; // completed
        //     $reservasi->save();

        //     if ($reservasi->meja) {
        //         $reservasi->meja->status = '0'; // tersedia
        //         $reservasi->meja->save();
        //     }
        // }

        // // 2. Update reservasi yang waktunya baru mulai
        // $mulai = Reservasi::where('proses', '0') // masih pending (belum dimulai)
        //     ->where('jam_mulai', '<=', $now)
        //     ->get();

        // foreach ($mulai as $reservasi) {
        //     $reservasi->proses = '1'; // on-going
        //     $reservasi->save();

        //     if ($reservasi->meja) {
        //         $reservasi->meja->status = '1'; // digunakan
        //         $reservasi->meja->save();
        //     }
        // }
    }

    public function index(Request $request)
    {
        if ($request->get('showModal') === 'tambah') {
            session()->flash('show_tambah_modal', true);
        }

        $this->updateStatusReservasi();
        $now = Carbon::now();
        $meja=Meja::orderby('nomor_meja','asc')->with('reservasi')->get();

        $reservasi=Reservasi::whereIn('proses', ['0', '1'])->where('jam_berakhir', '>=', $now)->orderBy('jam_mulai', 'asc')->get();
        return view('backend.v_beranda.beranda', [
            'judul'=>'Dashboard',
            'index'=>$meja,
            'now'=>$now,
            'view'=>$reservasi,
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

        $now = Carbon::now();

        // Cek apakah ada reservasi aktif di meja yang sama dan waktu saling tumpang tindih
        $conflict = Reservasi::where('meja_id', $request->meja_id)
        ->where('proses', '!=', '2') // Abaikan yang sudah selesai
        ->where(function ($query) use ($jamMulai, $jamBerakhir) {
            $query->where(function ($q) use ($jamMulai, $jamBerakhir) {
                $q->where('jam_mulai', '<', $jamBerakhir)
                ->where('jam_berakhir', '>', $jamMulai);
            });
        })
        ->exists();
        // $conflict = Reservasi::where('meja_id', $request->meja_id)
        //     ->where(function ($query) use ($jamMulai, $jamBerakhir) {
        //         $query->whereBetween('jam_mulai', [$jamMulai, $jamBerakhir])
        //             ->orWhereBetween('jam_berakhir', [$jamMulai, $jamBerakhir])
        //             ->orWhere(function ($query) use ($jamMulai, $jamBerakhir) {
        //                 $query->where('jam_mulai', '<=', $jamMulai)
        //                         ->where('jam_berakhir', '>=', $jamBerakhir);
        //             });
        //     })
        //     ->where('proses', '!=', '2') // Abaikan yang sudah selesai
        //     ->exists();

        // if ($conflict) {
        //     return redirect()->back()->withErrors(['meja_id' => 'Meja sedang digunakan pada rentang waktu tersebut. Silakan pilih waktu lain.'])->withInput();
        // }
        $existingReservasi = Reservasi::where('meja_id', $request->meja_id)
            ->where('proses', '!=', '2')
            ->orderBy('jam_berakhir', 'desc')
            ->first();

        if (!$conflict) {
            $proses = '1'; // on going karena meja tidak ada yang pakai di waktu ini
        } else {
            if ($existingReservasi && $jamMulai->greaterThanOrEqualTo($existingReservasi->jam_berakhir)) {
                // mulai setelah reservasi terakhir selesai => pending
                $proses = '0';
            } else {
                return redirect()->back()->withErrors(['meja_id' => 'Meja sedang digunakan pada rentang waktu tersebut. Silakan pilih waktu lain.'])->withInput();
            }
        }
        // $existingReservasi = Reservasi::where('meja_id', $request->meja_id)
        //     ->where('proses', '!=', '2')
        //     ->orderBy('jam_berakhir', 'desc')
        //     ->first();

        // $proses = '1'; // default on-going

        // if ($conflict) {
        //     if ($existingReservasi && $jamMulai->greaterThan($existingReservasi->jam_berakhir)) {
        //         // Tidak tumpang tindih, hanya belum mulai => status pending
        //         $proses = '0';
        //     } else {
        //         return redirect()->back()->withErrors(['meja_id' => 'Meja sedang digunakan pada rentang waktu tersebut. Silakan pilih waktu lain.'])->withInput();
        //     }
        // }

        // Tentukan proses awal
        if ($jamMulai->lessThanOrEqualTo($now)) {
            $proses = '1'; // on-going langsung
        } else {
            $proses = '0'; // pending
        }

        $meja = Meja::findOrFail($request->meja_id); // ambil data meja

        $totalharga = Meja::with('kategori')->findOrFail($request->meja_id);
        $hargaPerJam = $totalharga->kategori->harga; // Ambil harga dari relasi kategori
        $durasi = (int)$request->durasi;
        $totalBayar = $hargaPerJam * $durasi;

        // Cari data waiting list dengan nama yang sama, urut dari yang paling awal
        $waiting = Waiting::whereHas('pelanggan', function ($query) use ($request) {
            $query->where('nama', $request->pelanggan_id);
        })->orderBy('jam_daftar', 'asc')->first();

        if ($waiting) {
            // Jika ditemukan di waiting list, ambil ID pelanggan dari situ
            $pelanggan = Pelanggan::find($waiting->pelanggan_id);

            // Hapus entry dari waiting list
            $waiting->delete();
        } else {
        // Cari atau buat pelanggan berdasarkan nama (untuk pelanggan langsung)
        $pelanggan = Pelanggan::firstOrCreate(
            ['nama' => $request->pelanggan_id],
            ['user_id' => Auth::id()]
        );}

        // Hapus dari waiting list jika pelanggan ini ada
        // Waiting::where('pelanggan_id', $pelanggan->id)->delete();

        // Simpan data reservasi
        $reservasi = Reservasi::create([
            'kategori_id'  => $meja->kategori_id,
            'pelanggan_id' => $pelanggan->id,
            'user_id'      => Auth::user()->id,
            'meja_id'      => $request->meja_id,
            'jam_mulai'    => $jamMulai,
            'jam_berakhir' => $jamBerakhir,
            'durasi'       => $durasi,
            'proses'       => $proses, 
            'total'        => $totalBayar,
            'status_bayar' => '0', // Belum dibayar
        ]);

        // Update status meja menjadi 1 (on-going)
        $meja = Meja::find($request->meja_id);
        if ($meja) {
            $meja->status = $proses == '1' ? '1' : '2'; // 1 = digunakan, 2 = dipesan
            $meja->save();
        }
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
