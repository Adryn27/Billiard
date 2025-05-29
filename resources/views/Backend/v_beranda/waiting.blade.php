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
                      @if ($row->status_bayar == 0)
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#bayarModal{{ $row->id }}"><i class="fas fa-money-bill-wave"></i> Bayar</button>
                      @else
                        <button class="btn btn-success btn-sm" disabled><i class="fas fa-check-circle"></i> Bayar</button>
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