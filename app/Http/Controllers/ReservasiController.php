<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->where('updated_at', '>=', $now->subMinutes(10)) 
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

    public function reservasiCreate()
    {
        $meja=Meja::orderby('nomor_meja','asc')->get();
        return view('Frontend.v_reservasi.index', [
            'judul'=>'Dashboard',
            'index'=>$meja,
        ]);
    }

    public function reservasiStore(Request $request)
    {
        $request->validate([
            'meja_id' => 'required',
            'jam_mulai' => 'required',
            'durasi' => 'required|numeric|min:1',
            'metode_bayar' => 'required|in:0,1,2', // Cash, Bank, E-wallet
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

        // Cari atau buat pelanggan berdasarkan nama (untuk pelanggan langsung)
        $user = Auth::user();
        $pelanggan = Pelanggan::firstOrCreate(
            ['user_id' => $user->id],
            ['nama' => $user->name] // Sesuaikan dengan kolom nama di tabel users
        );

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
            'status_bayar' => '1', // dibayar
            'metode_bayar' => $request->metode_bayar, // Tambahkan ini
        ]);
        
        // Update status meja menjadi 1 (on-going)
        $meja = Meja::find($request->meja_id);
        if ($meja) {
            $meja->status = $proses == '1' ? '1' : '2'; // 1 = digunakan, 2 = dipesan
            $meja->save();
        }

        return redirect()->route('beranda')->with('success', 'Data Berhasil Tersimpan');
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

        session(['struk_data.' . $reservasi->id => [
            'dibayarkan' => $request->dibayar,
            'kembalian' => $request->kembalian]
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari reservasi berdasarkan ID
        $reservasi = Reservasi::findOrFail($id);

        // Ambil data meja terkait
        $meja = Meja::find($reservasi->meja_id);

        // Hapus reservasi
        $reservasi->delete();

        // Update status meja setelah reservasi dihapus
        // Logika status meja bisa disesuaikan dengan kebutuhan
        if ($meja) {
            // Cek apakah ada reservasi lain yang masih aktif (proses != 2)
            $aktif = Reservasi::where('meja_id', $meja->id)
                ->where('proses', '!=', '2') // selain selesai
                ->exists();

            if ($aktif) {
                // Jika masih ada reservasi aktif, meja dianggap digunakan (status = 1)
                $meja->status = '1';
            } else {
                // Jika tidak ada reservasi aktif, meja kembali tersedia (status = 0)
                $meja->status = '0';
            }
            $meja->save();
        }

        return redirect()->back()->with('success', 'Reservasi berhasil dihapus dan status meja diperbarui.');
    }

    public function transaksi(Request $request)
    {
        // if ($request->action == 'export_pdf') {
        //     $query = Reservasi::orderby('updated_at', 'desc')->where('status_bayar', '1');
        
        //     if ($request->start_date && $request->end_date) {
        //         $query->whereBetween('created_at', [
        //             $request->start_date . ' 00:00:00',
        //             $request->end_date . ' 23:59:59',
        //         ]);
        //     }
        
        //     $data = $query->get();
        //     $judul = 'Transaction'; // ← Tambahkan variabel ini
        
        //     $pdf = Pdf::loadView('backend.v_report.cetak', [
        //         'data' => $data,
        //         'judul' => $judul, // ← Sertakan ke dalam array
        //     ]);
        if ($request->action == 'export_pdf') {
            $query = Reservasi::orderby('updated_at', 'desc')->where('status_bayar', '1');
        
            $tanggalAwal = $request->start_date;
            $tanggalAkhir = $request->end_date;
        
            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('created_at', [
                    $tanggalAwal . ' 00:00:00',
                    $tanggalAkhir . ' 23:59:59',
                ]);
            }
        
            $data = $query->get();
            
            $pdf = Pdf::loadView('backend.v_report.cetak', [
                'data' => $data,
                'judul' => 'Transaction',
                'tanggalAwal' => $tanggalAwal,
                'tanggalAkhir' => $tanggalAkhir,
            ]);
            
            return $pdf->stream('Transaksi.pdf');
        }

        $query=Reservasi::orderby('updated_at','desc')->where('status_bayar', '1');

        if ($request->start_date && $request->end_date) {
            try {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59',
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Format tanggal salah.');
            }
        }
        return view('backend.v_report.transaksi', [
            'judul'=>'Transaction',
            'index'=>$query->latest()->get(),
        ]);
    }
}
