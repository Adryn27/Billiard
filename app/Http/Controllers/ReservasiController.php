<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
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
        if ($request->action == 'export_pdf') {
            $query = Reservasi::orderby('updated_at', 'desc')->where('status_bayar', '1');
        
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59',
                ]);
            }
        
            $data = $query->get();
            $judul = 'Transaction'; // ← Tambahkan variabel ini
        
            $pdf = Pdf::loadView('backend.v_report.cetak', [
                'data' => $data,
                'judul' => $judul, // ← Sertakan ke dalam array
            ]);
            
            return $pdf->download('transaksi.pdf');
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
