<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 12px;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h3>Data Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Table</th>
                <th>Nama Pelanggan</th>
                <th>Total Harga</th>
                <th>Durasi</th>
                <th>Metode Bayar</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>Table {{ $item->meja->nomor_meja }} - {{ $item->kategori->nama_kategori }}</td>
                <td>{{ $item->pelanggan->nama }}</td>
                <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                <td>{{ $item->durasi }} Jam</td>
                <td>
                    @if ($item->metode_bayar == 0)
                        Cash  
                    @elseif ($item->metode_bayar == 1)
                        Bank   
                    @elseif ($item->metode_bayar == 2)
                        E-Wallet   
                    @endif
                </td>
                <td>{{ $item->created_at}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
