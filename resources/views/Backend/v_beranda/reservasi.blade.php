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
                          <button class="btn btn-secondary btn-sm"><i class="fas fa-print" onclick="printStruk()"></i> Cetak</button>
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
            <form action="{{ route('backend.reservasilist.update', $row->id) }}" id="formBayar{{ $row->id }}" method="POST">
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
                      <input type="hidden" name="kembalian" id="kembalian_hidden{{ $row->id }}">
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

{{-- Struk --}}
<div id="struk" class="d-none">
  <style>
      .struk-container {
          width: 300px;
          padding: 20px;
          margin: 0 auto;
          border: 1px solid #ddd;
          border-radius: 5px;
          font-family: 'Courier New', Courier, monospace;
          background-color: #fff;
          color: #333;
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .struk-header {
          text-align: center;
          font-size: 18px;
          font-weight: bold;
          margin-bottom: 15px;
      }

      .struk-header h1 {
          margin: 0;
          font-size: 24px;
      }

      .struk-header p {
          margin: 5px 0;
          font-size: 12px;
      }
      .struk-header img {
          max-width: 30px; 
          margin-bottom: 10px;
      }

      
      .struk-info {
          margin-bottom: 20px;
      }
      
      .struk-info p {
          margin: 5px 0;
          font-size: 14px;
          line-height: 1.5;
      }

      .struk-info .bold {
          font-weight: bold;
      }

      .struk-detail {
          margin-bottom: 20px;
          border-top: 1px dashed #ddd;
          padding-top: 10px;
      }
      
      .struk-detail p{
          text-align: center;
          margin: 5px 0;
          font-size: 14px;
          line-height: 1.5;
      }
      .struk-detail table {
          width: 100%;
          border-collapse: collapse;
      }

      .struk-detail th,
      .struk-detail td {
          padding: 8px 0;
          text-align: left;
          font-size: 14px;
      }

      .struk-detail th {
          text-align: center;
      }

      .total {
          font-weight: bold;
          font-size: 14px;
      }
      
      .struk-bawah {
          display: flex;
          justify-content: space-between; 
          align-items: center;           
      }

      .kiri {
          font-weight: bold;
          font-size: 14px;
      }

      .kanan {
          font-size: 14px;
      }

      .struk-footer {
          text-align: center;
          margin-top: 20px;
          font-size: 12px;
      }

      .struk-footer p {
          margin: 5px 0;
          color: #666;
      }
      @media print {
          .struk-container {
              width: 250px;
              padding: 10px;
              margin: 0;
              border-radius: 0;
              box-shadow: none;
          }

          .struk-header h1 {
              font-size: 22px;
          }

          .struk-footer {
              font-size: 10px;
          }
      }
  </style>
  <div class="struk-container">
      <div class="struk-header">
          <img src="{{ asset('Backend/Gambar/logo3.png') }}">
          <h1>Cue Town Reserve</h1>
          <p>Jl. Raya Bantar Gebang - Setu, Padurenan, Kec. Setu, Kota Bks, Jawa Barat</p>
      </div>
      <div class="struk-info">  
          <p><span class="bold">Info  :</span>{{ $row->pelanggan->nama }}</p>
          <p><span class="bold">Waktu:</span>{{ $row->created_at }} </p>
          <p><span class="bold">Kasir:</span>{{ $row->user->nama }}</p>
      </div>
      <div class="struk-detail">
          <table>
              <thead>
                  <tr>
                      <th>Kategori</th>
                      <th>Durasi</th>
                      <th>Subtotal</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>{{ $row->kategori->nama_kategori }}</td>
                      <td>{{ $row->durasi }} Jam</td>
                      <td>Rp{{ number_format($row->total), 0, ',', '.' }}</td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="struk-bawah">
          <div class="kiri">Total</div>
          <div class="total">Rp{{ number_format($row->total), 0, ',', '.' }}</div>
      </div>
      <div class="struk-bawah">
          <div class="kiri">Bayar</div>
          <div class="kanan">Rp{{ number_format(session('dibayarkan')), 0, ',', '.' }}</div>
      </div>
      <div class="struk-bawah">
          <div class="kiri">Kembalian</div>
          <div class="kanan">Rp{{ number_format(session('kembalian')), 0, ',', '.' }}</div>
      </div>
      <div class="struk-footer">
          <p>Terima kasih atas kunjungan Anda!</p>
          <hr>
          <p>Cue Town Reserve, Our Billiard Pool Hall</p>
      </div>
  </div>
</div>
@endforeach

<script>
  function hitungKembalian(id) {
      const total = parseInt(document.getElementById('total' + id).value);
      const dibayar = parseInt(document.getElementById('dibayar' + id).value) || 0;
      const kembalianField = document.getElementById('kembalian' + id);
      const kembalianHidden = document.getElementById('kembalian_hidden' + id);
      const btnBayar = document.getElementById('btnBayar' + id);

      if (dibayar >= total) {
          const kembalian = dibayar - total;
          kembalianField.value = kembalian.toLocaleString('id-ID');
          kembalianHidden.value = kembalian;
          btnBayar.disabled = false;
      } else {
          kembalianField.value = 0;
          btnBayar.disabled = true;
      }
  }
</script>

<script>
  function printStruk() {
      var struk = document.getElementById("struk").innerHTML;

      var print = document.createElement('iframe');
      print.style.display = 'none';
      document.body.appendChild(print);
      print.contentDocument.write(struk);
      print.contentWindow.print();
      document.body.removeChild(print);
  }

  // Auto cetak setelah redirect
  // window.onload = function() {
  //     printStruk();
  // }
</script>

@endsection