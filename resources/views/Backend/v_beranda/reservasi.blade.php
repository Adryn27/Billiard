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

{{-- Modal Bayar --}}
@foreach ($index as $row)
<div class="modal fade" id="bayarModal{{ $row->id }}" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="bayarModalLabel"><b>Pembayaran</b></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
            <form action="{{ route('backend.reservasilist.update', $row->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-body row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" class="form-control" value="{{ old('nama_pelanggan', $row->pelanggan->nama) }}" readonly>
                  </div>
                  <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" class="form-control" value="{{ old('nama_kategori', $row->kategori->nama_kategori) }}" readonly>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Metode Bayar</label>
                    <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror">
                        <option value="" selected> - Pilih Metode Bayar - </option>
                        <option value="0" {{ old('metode_bayar', $row->metode_bayar) == '0' ? 'selected' : '' }}>Cash</option>
                        <option value="1" {{ old('metode_bayar', $row->metode_bayar) == '1' ? 'selected' : '' }}>Bank</option>
                        <option value="2" {{ old('metode_bayar', $row->metode_bayar) == '1' ? 'selected' : '' }}>E-Wallet</option>
                      </select>
                      @error('metode_bayar')
                        <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label>Total Bayar</label>
                      <input type="text" class="form-control" value="{{ number_format($row->total, 0, ',', '.') }}" readonly>
                      <input type="hidden" id="total{{ $row->id }}" value="{{ $row->total }}">
                  </div>
                  <div class="form-group">
                      <label>Dibayarkan</label>
                      <input type="number" name="dibayar" class="form-control" id="dibayar{{ $row->id }}" required oninput="hitungKembalian({{ $row->id }})">
                  </div>
                  <div class="form-group">
                      <label>Kembalian</label>
                      <input type="text" class="form-control" id="kembalian{{ $row->id }}" readonly>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btnBayar{{ $row->id }}" disabled>Bayar</button>
              </div>
            </form>
      </div>
  </div>
</div>
@endforeach

<script>
  function hitungKembalian(id) {
      const total = parseInt(document.getElementById('total' + id).value);
      const dibayar = parseInt(document.getElementById('dibayar' + id).value) || 0;
      const kembalianField = document.getElementById('kembalian' + id);
      const btnBayar = document.getElementById('btnBayar' + id);

      if (dibayar >= total) {
          const kembalian = dibayar - total;
          kembalianField.value = kembalian.toLocaleString('id-ID');
          btnBayar.disabled = false;
      } else {
          kembalianField.value = 0;
          btnBayar.disabled = true;
      }
  }
</script>
@endsection