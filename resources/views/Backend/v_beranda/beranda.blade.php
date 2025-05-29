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
                // Tentukan warna background berdasarkan status
                $bgColor = match($row->status) {
                    '0' => 'bg-success',
                    '1' => 'bg-danger',
                    '2' => 'bg-warning',
                    default => '0'

                };
                $jam_berakhir = $row->reservasi?->jam_berakhir ?? null;
                $now = \Carbon\Carbon::now();
                
            @endphp
            <div class="col-md-3">
                <div class="card mt-3">
                    <div class="card-header {{ $bgColor }} text-white">
                      <b>Table {{ $row->nomor_meja }} ({{ $row->kategori->nama_kategori }}) </b>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 text-secondary">
                            <i class="fas fa-clock me-2 text-dark"></i>
                            <strong>Waktu: </strong><span id="waktu_mulai_berakhir"></span>
                        </div>
                        <div class="mb-2 text-secondary">
                            <i class="fas fa-clock me-2 text-dark"></i>
                            <strong>Durasi: </strong><span id="durasi"></span>
                        </div>
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted small">Sisa Waktu</div>
                            <h4 class="fw-bold text-danger" id="countdown">--:--:--</h4>
                        </div>
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

{{-- Modal Edit Kategori --}}
{{-- @foreach ($index as $row)
    <div class="modal fade" id="editMejaModal{{ $row->id }}" tabindex="-1" aria-labelledby="editMejaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMejaModalLabel{{ $row->id }}"><b>Edit Meja</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.table.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                                        <option value="" selected> - Pilih Katagori - </option>
                                        @foreach ($create as $c)
                                          @if (old('kategori_id', $row->kategori_id) == $c->id)
                                            <option value="{{ $c->id }}" selected> {{ $c->nama_kategori }} </option>
                                          @else
                                            <option value="{{ $c->id }}"> {{ $c->nama_kategori }} </option>
                                          @endif
                                        @endforeach
                                      </select>
                                      @error('kategori_id')
                                        <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                      @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nomor Meja</label>
                                    <input type="number" name="nomor_meja" class="form-control @error('nomor_meja') is-invalid @enderror" value="{{ old('nomor_meja', $row->nomor_meja) }}"></input>
                                    @error('nomor_meja')
                                        <div class="invalid-feedback alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbaharui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach --}}

@endsection