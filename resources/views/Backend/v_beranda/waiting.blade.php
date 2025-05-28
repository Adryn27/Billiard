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

@endsection