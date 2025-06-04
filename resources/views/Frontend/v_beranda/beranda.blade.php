@extends('frontend.v_layout.app')
@section('content')
<style>
    .shadow-orange {
    box-shadow: 0 4px 10px rgba(255, 102, 0, 0.4); /* bayangan oranye */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .shadow-orange:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 18px rgba(255, 102, 0, 0.6); /* bayangan oranye lebih besar saat hover */
    }
    .countdown {
      font-size: 1.5rem;
    }
    .card:hover {
        transform: translateY(-5px);
        transition: 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(255, 165, 0, 0.5);
    }
    .card-horizontal {
        display: flex;
        flex-direction: row;
        height: 180px; 
    }
    .card-horizontal img {
        width: 40%;
        object-fit: cover;
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    .card-horizontal .card-body {
        width: 60%;
        padding: 1rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center; /* teks di tengah secara vertikal */
    }

    /* Responsif: di layar kecil jadi vertikal */
    @media (max-width: 576px) {
        .card-horizontal {
        flex-direction: column;
        height: auto;
        }
        .card-horizontal img {
        width: 100%;
        height: 180px;
        border-radius: 0.375rem 0.375rem 0 0;
        }
        .card-horizontal .card-body {
        width: 100%;
        padding: 1rem;
        text-align: center;
        }
    }
    </style>
    
<header class="masthead">
    <div class="container d-flex flex-column justify-content-center align-items-center px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center pt-5 pb-6">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white font-weight-bold">Cue Town Reserve, Our Billiard Pool Hall</h1>
                <hr class="divider" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <a class="btn btn-primary btn-xl" href="{{ route('reservasi') }}">Reservation</a>
            </div>
        </div>
    </div>
</header>

<!-- Table-->
<section class="page-section" id="table">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Table</h2>
        <hr class="divider" />
        <div class="row gx-4 gx-lg-5 justify-content-center">
                @foreach ($index as $row)
                @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
    
                    $reservasiMeja = $view->where('meja_id', $row->id)
                                        ->whereIn('proses', [0, 1])
                                        ->sortBy('jam_mulai')
                                        ->first();
    
                    if ($reservasiMeja) {
                        $mulai = \Carbon\Carbon::parse($reservasiMeja->jam_mulai, 'Asia/Jakarta');
                        $berakhir = \Carbon\Carbon::parse($reservasiMeja->jam_berakhir, 'Asia/Jakarta');
    
                        if ($now->lt($mulai)) {
                            $text    = 'Reserved';
                            $bgColor = 'bg-warning';
                        } elseif ($now->between($mulai, $berakhir)) {
                            $text    = 'On-Going';
                            $bgColor = 'bg-danger';
                        } else {
                            $text    = 'Available';
                            $bgColor = 'bg-success';
                        }
                    } else {
                        $text    = 'Available';
                        $bgColor = 'bg-success';
                    }
                    // $items = $view->where('meja_id', $row->id);
                    $highlight = $view
                    ->where('meja_id', $row->id)
                    ->filter(function($item) use ($now) {
                        return in_array($item->proses, [0,1]) && $now->lte(\Carbon\Carbon::parse($item->jam_berakhir));
                    })
                    ->sortBy('jam_mulai')
                    ->first();
                    
                @endphp
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card custom-card shadow-orange border-0 rounded-4">
                      <div class="card-body text-center p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <h5 class="card-title mb-0 fw-bold">Table {{ $row->nomor_meja }}</h5>
                          <span class="badge {{ $bgColor }}">{{ $text }}</span>
                        </div>
                        <p class="text-muted mb-2">{{ $row->kategori->nama_kategori }}</p>
                        @if ($highlight)
                        <div class="bg-light rounded py-3">
                            <div class="text-muted small mb-1">Sisa Waktu</div>
                            <h4 class="fw-bold text-danger countdown" id="countdown-1" 
                                data-starttime="{{ \Carbon\Carbon::parse($highlight->jam_mulai)->format('Y-m-d\TH:i:s') }}" 
                                data-endtime="{{ \Carbon\Carbon::parse($highlight->jam_berakhir)->format('Y-m-d\TH:i:s') }}">--:--:--</h4>
                        </div>
                        @else 
                        <div class="bg-light rounded py-3">
                            <div class="text-muted small mb-1">Sisa Waktu</div>
                            <h4 class="fw-bold text-danger countdown">--:--:--</h4>
                        </div>
                        @endif
                        <button class="btn btn-outline-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $row->id }}">
                          <i class="bi bi-info-circle"></i> Detail
                        </button>
                      </div>
                    </div>
                  </div>
                @endforeach
        </div>
    </div>
</section>

<!-- Category-->
<section class="page-section bg-primary" id="kategori">
    <div class="container px-4 px-lg-5">
      <div class="text-center mb-5">
        <h2 class="text-white mt-0">Table Category</h2>
        <hr class="divider divider-light" />
        <p class="text-white-75 mb-4">Berbagai kategori meja billiard dengan harga dan fasilitas yang berbeda.</p>
      </div>
      <div class="row gx-4 gx-lg-5">
        @foreach ($kategori as $item)
        <div class="col-lg-6 col-md-6 mb-5">
          <div class="card shadow-lg border-0 h-100 card-horizontal">
            @if ($item->foto)
            <img src="{{ asset('storage/img-kategori/' . $item->foto) }}" alt="{{ $item->nama_kategori }}">
            @endif
            <div class="card-body">
              <h5 class="card-title text-primary">{{ $item->nama_kategori }}</h5>
              <h6 class="card-subtitle mb-2 text-muted">Rp {{ number_format($item->harga, 0, ',', '.') }}/jam</h6>
              <p class="card-text text-dark">{{ $item->fasilitas }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</section>

{{-- Modal Detail --}}
@foreach ($index as $row)
  <div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $row->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetailLabel{{ $row->id }}">
            Reservation Details - Table {{ $row->nomor_meja }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          @php
              $reservasiList = $view->where('meja_id', $row->id);
          @endphp

          @if ($reservasiList->isNotEmpty())
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Durasi</th>
                    <th>Jam</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($reservasiList as $res)
                    @php
                      $mulai = \Carbon\Carbon::parse($res->jam_mulai, 'Asia/Jakarta');
                      $berakhir = \Carbon\Carbon::parse($res->jam_berakhir, 'Asia/Jakarta');
                    @endphp
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $res->durasi }} Jam</td>
                      <td>{{ $mulai->format('H:i') }} - {{ $berakhir->format('H:i') }}</td>
                      <td>
                        @if($res->proses == '0')
                            <span class="badge bg-warning">Pending</span><br>
                        @elseif($res->proses == '1')
                            <span class="badge bg-danger">On-Going</span><br>
                        @else
                            <span class="badge bg-success">Completed</span><br>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <p class="text-muted text-center">Tidak ada data reservasi untuk meja ini.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach
          
@endsection