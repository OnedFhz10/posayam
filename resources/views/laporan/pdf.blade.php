<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Periode: {{ $startDate ?? 'Semua' }} s/d {{ $endDate ?? 'Semua' }}</p>
    </div>

    <h3>Rincian Menu Terjual</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th style="text-align: center;">Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($terjualPerItem as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk ?? 'Produk (Data Terhapus)' }}</td>
                    <td style="text-align: center;">{{ $item->total_qty }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Tidak ada penjualan item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Riwayat Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Waktu</th>
                <th>Kasir / Cabang</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $transaksi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaksi->user->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_belanja, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3" class="text-right">Grand Total Omzet</td>
                <td class="text-right">Rp {{ number_format($total_omzet, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
