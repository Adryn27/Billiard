@extends('backend.v_layouts.app')
@section('content')

<div class="col-12">
  <div class="card mt-3">
    <div class="card-header d-flex align-items-center">
      <a href="{{ route('backend.dashboard.index') }}">
        <button class="btn btn-outline-secondary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
          <i class="fas fa-arrow-left"></i>
        </button>
      </a>
      <h3 class="mb-0"><b>{{ $judul }}</b></h3>
    </div>     
    <div class="card-body">
      <div class="col d-flex justify-content-end">
        <a href="#">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahWaitingModal"><i class="fas fa-plus"></i> Tambah</button>
        </a>
      </div>
      <div class="table-responsive table-hover mt-3">
        <table
          id="dataTable"
          class="table table-striped table-bordered"
        >
          <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Kategori</th>
                <th style="white-space: nowrap;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($index as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->pelanggan->nama }}</td>
                    <td>{{ $row->kategori->nama_kategori }}</td>
                    <td style="white-space: nowrap;">
                      <a href="{{ route('backend.dashboard.index', ['showModal' => 'tambah']) }}">
                        <button class="btn btn-warning btn-sm"><i class="far fa-edit"></i> Reservasi</button>
                      </a>
                      <form action="{{ route('backend.waitinglist.destroy', $row->id) }}" method="POST" style="display: inline-block">
                          @method('delete')
                          @csrf

                          <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->pelanggan->nama }}"><i class="fas fa-trash"> Hapus</i></button>
                      </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah Waiting--}}
<div class="modal fade" id="tambahWaitingModal" tabindex="-1" aria-labelledby="tambahWaitingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tambahWaitingModalLabel"><b>Tambah Waiting List</b></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
              <form action="{{ route('backend.waitinglist.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                                <option value="" {{ old('kategori_id') == '' ? 'selected' : '' }}>- Pilih Kategori -</option>
                                @foreach ( $create as $row ) 
                                <option value="{{ $row->id }}">{{ $row->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"></input>
                            @error('nama_pelanggan')
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
@if(session('show_reservasi_modal'))
<script>
    window.onload = function() {
        var myModal = new bootstrap.Modal(document.getElementById('tambahModal'));
        myModal.show();
    };
</script>
@endif


@endsection