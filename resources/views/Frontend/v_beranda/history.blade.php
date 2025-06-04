@extends('frontend.v_layout.app')
@section('content')

<style>
    .hero-section {
        position: relative;
        background-image: linear-gradient(to bottom, rgba(44, 62, 80, 0.7) 0%, rgba(44, 62, 80, 0.7) 100%),url('frontend/assets/img/bg1.jfif');
        background-size: cover;
        background-position: center;
        height: 40vh;
        clip-path: ellipse(100% 90% at 50% 0%); /* Membuat lengkung keluar bawah */
    }

    .hero-section .overlay {
        background: rgba(0, 0, 0, 0.5); /* gelapkan background agar teks terlihat */
        z-index: 1;
    }

    .hero-section .container {
        z-index: 2;
        padding-top: 10vh;
    }
    .btn-light:hover {
        background-color: var(--bs-primary);
        color: white;
        border-color: var(--bs-primary);
        transition: background-color 0.2s ease;
    }
</style>

<div class="hero-section d-flex align-items-center justify-content-center text-center">
    <div class="overlay w-100 h-100 position-absolute top-0 start-0"></div>

    <div class="container position-relative">
        <div class="d-flex align-items-center justify-content-center position-relative">
            <a href="{{ route('beranda') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center position-absolute start-0"
               style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="text-white fw-semibold mb-0">{{ $judul }}</h3>
        </div>
        <hr class="divider my-3" />
    </div>
    
</div>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-lg-8 col-xl-6 text-center">
            <p class="text-muted mb-5">
                Lihat semua reservasi Anda di sini. Cek status reservasi, durasi main, dan total biaya. Pembatalan hanya bisa dilakukan sebelum reservasi dimulai (Pending).
            </p>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="table-responsive table-hover mt-3">
            <table
              id="myTable"
              class="table table-striped table-bordered"
            >
              <thead>
                <tr>
                    <th>No</th>
                    <th style="white-space: nowrap;">Meja</th>
                    <th>Durasi</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th style="white-space: nowrap;">Total</th>
                    <th style="white-space: nowrap;">Aksi</th>
                    <th>Tanggal Transaksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($index as $row)
                    @php
                      $mulai = \Carbon\Carbon::parse($row->jam_mulai, 'Asia/Jakarta');
                      $berakhir = \Carbon\Carbon::parse($row->jam_berakhir, 'Asia/Jakarta');
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="white-space: nowrap;">Table {{ $row->meja->nomor_meja }} - {{ $row->kategori->nama_kategori }}</td>
                        <td>{{ $row->durasi }} Jam</td>
                        <td>{{ $mulai->format('H:i') }} - {{ $berakhir->format('H:i') }}</td>
                        <td>
                          @if($row->proses == '0')
                          <span class="badge bg-warning" style="color: white">Pending</span>
                          @elseif($row->proses == '1')
                          <span class="badge bg-danger" style="color: white">On-Going</span>
                          @else
                          <span class="badge bg-success" style="color: white">Completed</span>
                          @endif
                        </td>
                        <td style="white-space: nowrap;">Rp {{ number_format($row->total), 0, ',', '.' }}</td>
                        <td style="white-space: nowrap;">
                            @if ($row->proses == '0')
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalCancel-{{ $row->id }}"><i class="fas fa-times" style="color: white"> Cancel</i></button>
                            {{-- <form action="{{ route('backend.reservasilist.destroy', $row->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning btn-sm show_confirm" data-konf-delete="{{ $row->pelanggan->nama }}"><i class="fas fa-times" style="color: white"> Cancel</i></button>
                            </form> --}}
                            @endif
                        </td>
                        <td class="text-muted">{{ $row->created_at }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
</div>

@foreach ($index as $row)
<div class="modal fade" id="modalCancel-{{ $row->id }}" tabindex="-1" aria-labelledby="modalCancelLabel-{{ $row->id }}" aria-hidden="true"> 
    <div class="modal-dialog"> 
        <div class="modal-content"> 
            <form action="{{ route('backend.reservasilist.destroy', $row->id) }}" method="POST"> 
                @csrf 
                @method('DELETE') 
                <div class="modal-header bg-warning text-white"> 
                    <h5 class="modal-title" id="modalCancelLabel-{{ $row->id }}"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Pembatalan </h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                </div> 
                <div class="modal-body"> 
                    <p>Apakah Anda yakin ingin membatalkan reservasi ini</strong>?</p> 
                    <p class="text-danger">Uang tidak dapat dikembalikan jika reservasi dibatalkan.</p> 
                </div> 
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button> 
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button> 
                </div> 
            </form> 
        </div> 
    </div> 
</div>
@endforeach

@endsection