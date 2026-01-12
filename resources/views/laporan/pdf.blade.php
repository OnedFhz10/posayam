<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            margin-top: 20px;
            text-align: right;
        }

        .total-box span {
            font-weight: bold;
            font-size: 12pt;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>{{ config('app.name', 'POS Ayam') }}</h2>
        <p>Laporan Penjualan</p>
        <p>
            Periode:
            {{ $startDate ? date('d-m-Y', strtotime($startDate)) : 'Awal' }} s/d
            {{ $endDate ? date('d-m-Y', strtotime($endDate)) : 'Hari Ini' }}
        </p>
    </div>

    <table style="width: 100%; margin-bottom: 20px; border: none;">
        <tr style="border: none;">
            <td style="border: none; background: #eef2f7; padding: 15px;">
                <strong>Total Omzet</strong><br>
                <span style="font-size: 14pt;">Rp {{ number_format($total_omzet, 0, ',', '.') }}</span>
            </td>
            <td style="border: none; background: #eef2f7; padding: 15px;">
                <strong>Total Transaksi</strong><br>
                <span style="font-size: 14pt;">{{ $total_transaksi }}</span>
            </td>
        </tr>
    </table>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal & Jam</th>
                <th>Kasir</th>
                <th>Item</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>
                        {{-- Loop detail item biar ketahuan beli apa aja --}}
                        @foreach ($trx->details as $detail)
                            <small>{{ $detail->produk->nama ?? 'X' }} ({{ $detail->jumlah }}), </small>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

</body>

</html>
