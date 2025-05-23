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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal"><i class="fas fa-plus"></i> Tambah</button>
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
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Fasilitas</th>
                  <th style="white-space: nowrap;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $row)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $row->nama_kategori }}</td>
                      <td>Rp {{ number_format($row->harga), 0, ',', '.' }}</td>
                      <td style="max-width: 300px; overflow-x: auto; white-space: nowrap;">
                        <div style="overflow-x: auto;">{{ $row->fasilitas }}</div>
                      </td>
                      <td style="white-space: nowrap;">
                          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $row->id }}"><i class="far fa-edit"></i> Edit</button>
                          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#showKategoriModal{{ $row->id }}"><i class="far fa-image"></i> Show</button>
                          <form action="{{ route('backend.category.destroy', $row->id) }}" method="POST" style="display: inline-block">
                              @method('delete')
                              @csrf

                              <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_kategori }}"><i class="fas fa-trash"> Hapus</i></button>
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
</div>

{{-- Modal Tambah Kategori--}}
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahUserModalLabel"><b>Tambah Kategori</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto</label>
                                <img class="foto-preview mb-2" style="max-width: 100%;">
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                                class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Masukkan Nama Kategori">
                                @error('nama_kategori')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Fasilitas</label>
                                <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror"></textarea>
                                @error('fasilitas')
                                    <div class="invalid-feedback alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" onkeypress="return hanyaAngka(event)" name="harga" value="{{ old('harga') }}" class="form-control @error('harga') is-invalid @enderror" placeholder="Masukkan Harga" onkeypress="return hanyaAngka(event)">
                                @error('harga')
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
@foreach ($index as $row)
    <div class="modal fade" id="editKategoriModal{{ $row->id }}" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel{{ $row->id }}"><b>Edit Kategori</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.category.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    {{-- view image --}}
                                    <img src="{{ asset('storage/img-kategori/' . $row->foto) }}" class="foto-preview" width="100%">
                                    <p></p>
                                    {{-- file foto --}}
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
            
                                    @error('foto')
                                        <div class="invalid-feedback alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $row->nama_kategori) }}"
                                    class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Masukkan Nama Kategori">
                                    @error('nama_kategori')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror">
                                        {{ old('fasilitas', $row->fasilitas) }}
                                    </textarea>
                                    @error('fasilitas')
                                        <div class="invalid-feedback alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" onkeypress="return hanyaAngka(event)" name="harga" value="{{ old('harga', $row->harga) }}" class="form-control @error('harga') is-invalid @enderror" placeholder="Masukkan Harga" onkeypress="return hanyaAngka(event)">
                                    @error('harga')
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

{{-- Modal Show Gambar --}}
@foreach ($index as $row)
    <div class="modal fade" id="showKategoriModal{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="showKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-body text-center p-0">
            <img src="{{ asset('storage/img-kategori/' . $row->foto) }}" alt="Foto" class="img-fluid" style="max-height: 500vh; object-fit: contain;">
            </div>
        </div>
    </div>
@endforeach

@endsection