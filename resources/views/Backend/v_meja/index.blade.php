@extends('backend.v_layouts.app')
@section('content')

<div class="col-12">
    <div class="card mt-3">
      <div class="card-header">
        <h3><b>{{ $judul }}</b></h3>
      </div>
      <div class="card-body">
        <div class="col d-flex justify-content-end">
          <a href="#">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMejaModal"><i class="fas fa-plus"></i> Tambah</button>
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
                  <th>No. Meja</th>
                  <th>Kategori</th>
                  <th style="white-space: nowrap;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $row)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $row->nomor_meja }}</td>
                      <td>{{ $row->kategori->nama_kategori}}</td>
                      <td style="white-space: nowrap;">
                          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMejaModal{{ $row->id }}"><i class="far fa-edit"></i> Edit</button>
                          <form action="{{ route('backend.table.destroy', $row->id) }}" method="POST" style="display: inline-block">
                              @method('delete')
                              @csrf

                              <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nomor_meja }}"><i class="fas fa-trash"> Hapus</i></button>
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

{{-- Modal Tambah Kategori--}}
<div class="modal fade" id="tambahMejaModal" tabindex="-1" aria-labelledby="tambahMejaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahMejaModalLabel"><b>Tambah Meja</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.table.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label>Nomor Meja</label>
                                <input type="number" onkeypress="return hanyaAngka(event)" name="nomor_meja" value="{{ old('nomor_meja') }}"
                                class="form-control @error('nomor_meja') is-invalid @enderror" placeholder="Masukkan Nomor Meja" onkeypress="return hanyaAngka(event)">
                                @error('nomor_meja')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
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
@foreach ($index as $row)
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
@endforeach


@endsection