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
                  <th>Nama Pelanggan</th>
                  <th>Kategori</th>
                  <th style="white-space: nowrap;">Meja</th>
                  <th>Status</th>
                  <th style="white-space: nowrap;">Total</th>
                  <th style="white-space: nowrap;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $row)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $row->pelanggan->nama }}</td>
                      <td>{{ $row->kategori->nama_kategori }}</td>
                      <td style="white-space: nowrap;">Table {{ $row->meja->nomor_meja }}</td>
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
                          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $row->id }}"><i class="fas fa-money-bill-wave"></i> Bayar</button>
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