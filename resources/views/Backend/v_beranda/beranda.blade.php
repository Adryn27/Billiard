@extends('backend.v_layouts.app')
@section('content')

<div class="col-12">
    <div class="card mt-3">
      <div class="card-header">
        <h3><b>{{ $judul }}</b></h3>
      </div>
      <div class="card-body">
        <div class="row align-items-center mb-3">
            <div class="col d-flex justify-content-start gap-2">
                <a href="{{ route('backend.reservasilist.index') }}">
                    <button type="button" class="btn btn-secondary" >
                        <i class="fas fa-list"></i> Reservation List
                    </button>
                </a>
                <a href="{{ route('backend.waitinglist.index') }}">
                    <button type="button" class="btn btn-secondary" >
                        <i class="fas fa-list"></i> Waiting List
                    </button>
                </a>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="#">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="fas fa-plus"></i> Tambah</button>
                </a>
            </div>
        </div>
        <div class="row g-3">
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
                        $bgColor = 'bg-warning';
                    } elseif ($now->between($mulai, $berakhir)) {
                        $bgColor = 'bg-danger';
                    } else {
                        $bgColor = 'bg-success';
                    }
                } else {
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
            <div class="col-md-3">
                <div class="card mt-3">
                    <div class="card-header {{ $bgColor }} text-white">
                      <b>Table {{ $row->nomor_meja }} ({{ $row->kategori->nama_kategori }}) </b>
                    </div>
                    <div class="card-body">
                        @if ($highlight)
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Waktu: {{ \Carbon\Carbon::parse($highlight->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($highlight->jam_berakhir)->format('H:i') }}</strong>
                                <span id="waktu_mulai_berakhir"></span>
                            </div>
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Durasi: {{ $highlight->durasi }} Jam</strong><span id="durasi"></span>
                            </div>
                            <div class="bg-light rounded p-2 text-center">
                                <div class="text-muted small">Sisa Waktu</div>
                                <h4 class="fw-bold text-danger countdown" 
                                    id="countdown-{{ $row->id }}" 
                                    data-starttime="{{ \Carbon\Carbon::parse($highlight->jam_mulai)->format('Y-m-d\TH:i:s') }}"
                                    data-endtime="{{ \Carbon\Carbon::parse($highlight->jam_berakhir)->format('Y-m-d\TH:i:s') }}">
                                    --:--:--
                                </h4>
                            </div>
                        @else
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Waktu: -</strong><span id="waktu_mulai_berakhir"></span>
                            </div>
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Durasi: - </strong><span id="durasi"></span>
                            </div>
                            <div class="bg-light rounded p-2 text-center">
                                <div class="text-muted small">Sisa Waktu</div>
                                <h4 class="fw-bold text-danger countdown">
                                    --:--:--
                                </h4>
                            </div>
                        @endif
                        {{-- @if ($items->isEmpty())
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Waktu: -</strong><span id="waktu_mulai_berakhir"></span>
                            </div>
                            <div class="mb-2 text-secondary">
                                <i class="fas fa-clock me-2 text-dark"></i>
                                <strong>Durasi: - </strong><span id="durasi"></span>
                            </div>
                            <div class="bg-light rounded p-2 text-center">
                                <div class="text-muted small">Sisa Waktu</div>
                                <h4 class="fw-bold text-danger countdown">
                                    --:--:--
                                </h4>
                            </div>
                        @endif
                        @foreach ($view->where('meja_id', $row->id) as $look)
                        <div class="mb-2 text-secondary">
                            <i class="fas fa-clock me-2 text-dark"></i>
                            <strong>Waktu: {{ \Carbon\Carbon::parse($look->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($look->jam_berakhir)->format('H:i') }}</strong><span id="waktu_mulai_berakhir"></span>
                        </div> --}}
                        {{-- <div class="mb-2 text-secondary">
                            <i class="fas fa-clock me-2 text-dark"></i>
                            <strong>Durasi: {{ $look->durasi }} Jam</strong><span id="durasi"></span>
                        </div>
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted small">Sisa Waktu</div>
                            <h4 class="fw-bold text-danger countdown" 
                                id="countdown-{{ $row->id }}" 
                                data-starttime="{{ \Carbon\Carbon::parse($look->jam_mulai)->format('Y-m-d\TH:i:s') }}"
                                data-endtime="{{ \Carbon\Carbon::parse($look->jam_berakhir)->format('Y-m-d\TH:i:s') }}">
                                --:--:--
                            </h4>
                        </div>
                        @endforeach --}}
                      {{-- <span id="countdown-{{ $row->id }}"
                        class="countdown"
                        data-endtime="{{ ($jam_berakhir && $jam_berakhir->gt($now)) ? $jam_berakhir->format('Y-m-d\TH:i:s') : '' }}">
                      </span> --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
      </div>
    </div>
</div>

{{-- Modal Tambah--}}
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel"><b>Tambah</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="form-tambah" action="{{ route('backend.dashboard.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Pelanggan</label>
                                <input type="text" name="pelanggan_id" value="{{ old('pelanggan_id') }}"
                                class="form-control @error('pelanggan_id') is-invalid @enderror" placeholder="Masukkan Nama Pelanggan">
                                @error('pelanggan_id')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Meja</label>
                                <select name="meja_id" class="form-control @error('meja_id') is-invalid @enderror">
                                    <option value="" {{ old('meja_id') == '' ? 'selected' : '' }}>- Pilih Meja -</option>
                                    @foreach ( $index as $row ) 
                                    <option value="{{ $row->id }}">{{ $row->nomor_meja }} ({{ $row->kategori->nama_kategori }})</option>
                                    @endforeach
                                </select>
                                @error('meja_id')
                                    <div class="invalid-feedback alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{-- Waktu Mulai --}}
                                <label>Waktu Mulai</label>
                                <input type="datetime-local" class="form-control @error('jam_mulai') is-invalid @enderror" name="jam_mulai" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                                {{-- Durasi Main (jam) --}}
                                <label>Durasi Main (/Jam)</label>
                                <input type="number" class="form-control @error('durasi') is-invalid @enderror" step="1" name="durasi" min="1" required placeholder="Masukkan Durasi">
                                @error('durasi')
                                    <div class="invalid-feedback alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card mt-3">
      <div class="card-body">
        <div class="table-responsive table-hover mt-3">
          <table
            id="dataTable"
            class="table table-striped table-bordered"
          >
            <thead>
              <tr>
                  <th>No</th>
                  <th>Meja</th>
                  <th>Pelanggan</th>
                  <th>Durasi</th>
                  <th>Waktu</th>
                  <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $meja)
              @php
                $pelangganList = $view->where('meja_id', $meja->id);
              @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>Table {{ $meja->nomor_meja }} - {{ $meja->kategori->nama_kategori }}</td>
                <td>
                  @if ($pelangganList->isNotEmpty())
                    @foreach ($view->where('meja_id', $meja->id) as $row)
                      {{ $row->pelanggan->nama }} <br>
                    @endforeach
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if ($pelangganList->isNotEmpty())
                    @foreach ($view->where('meja_id', $meja->id) as $row)
                      {{ $row->durasi }} Jam<br>
                    @endforeach
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if ($pelangganList->isNotEmpty())
                    @foreach ($view->where('meja_id', $meja->id) as $row)
                      {{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($row->jam_berakhir)->format('H:i') }}<br>
                    @endforeach
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if ($pelangganList->isNotEmpty())
                    @foreach ($view->where('meja_id', $meja->id) as $row)
                      @if($row->proses == '0')
                        <span class="badge bg-warning" style="color: white">Pending</span><br>
                      @elseif($row->proses == '1')
                        <span class="badge bg-danger" style="color: white">On-Going</span><br>
                      @else
                        <span class="badge bg-success" style="color: white">Completed</span><br>
                      @endif
                    @endforeach
                  @else
                    -
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>

@endsection