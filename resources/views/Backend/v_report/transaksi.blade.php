@extends('backend.v_layouts.app')
@section('content')

<div class="col-12">
    <div class="card mt-3">
      <div class="card-header">
        <h3><b>{{ $judul }}</b></h3>
      </div>
      <div class="card-body">
        <form method="get" action="{{ route('transaksi') }}">
          <div class="row mb-3 align-items-center">
            <div class="col-md-3">
              <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ request('start_date') }}" placeholder="Tanggal Awal">
              @error('start_date')
                  <div class="invalid-feedback alert-danger">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-md-3">
              <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ request('end_date') }}" placeholder="Tanggal Akhir">
              @error('end_date')
                  <div class="invalid-feedback alert-danger">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary" type="submit" name="action" value="filter"><i class="fas fa-filter"></i></button>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
              <button class="btn btn-danger" type="submit" name="action" value="export_pdf"><i class="fas fa-file-pdf"></i> Cetak</button>
            </div>
          </div>
        </form>        
        <div class="table-responsive table-hover mt-3">
          <table
            id="dataTable"
            class="table table-striped table-bordered"
          >
            <thead>
              <tr>
                  <th>No</th>
                  <th>Table</th>
                  <th>Pelanggan</th>
                  <th>Total Harga</th>
                  <th>Durasi</th>
                  <th>Metode Bayar</th>
                  <th>Tanggal Transaksi</th>
                  <th>Dibuat Oleh</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($index as $row)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td style="white-space: nowrap;">Table {{ $row->meja->nomor_meja }} - {{ $row->kategori->nama_kategori }}</td>
                      <td>{{ $row->pelanggan->nama }}</td>
                      <td style="max-width: 300px; overflow-x: auto; white-space: nowrap;">Rp {{ number_format($row->total), 0, ',', '.' }}</td>
                      <td style="white-space: nowrap;">{{ $row->durasi }} Jam</td>
                      <td style="white-space: nowrap;">
                        @if ($row->metode_bayar == 0)
                                <span class="badge bg-success"><i class="fas fa-money-bill-wave"> Cash</i></span>    
                            @elseif ($row->metode_bayar == 1)
                                <span class="badge bg-primary"><i class="fas fa-university"> Bank</i></span>    
                            @elseif ($row->metode_bayar == 2)
                                <span class="badge bg-secondary"><i class="fas fa-wallet"> E-Wallet</i></span>    
                            @endif
                      </td>
                      </td>
                      <td style="white-space: nowrap;">{{ $row->created_at }}</td>
                      <td style="white-space: nowrap;">
                        @if ($row->role == 1)
                                <span class="badge bg-warning" style="color: white">Operator</span>    
                            @elseif ($row->role == 0)
                                <span class="badge bg-success" style="color: white">Admin</span>    
                            @elseif ($row->role == 3)
                                <span class="badge bg-success" style="color: white">Owner</span>    
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

<script>
    flatpickr("#tanggal_range", {
      mode: "range",
      dateFormat: "Y-m-d",
    });
  </script>
@endsection