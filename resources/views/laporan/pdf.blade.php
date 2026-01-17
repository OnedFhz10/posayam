<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 9pt;
            color: #666;
        }

        .meta-info {
            margin-bottom: 15px;
            width: 100%;
        }

        .meta-info td {
            padding: 3px 0;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: space-between;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        table.data th {
            background-color: #eee;
            font-weight: bold;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 8pt;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Penjualan Harian</h2>
        <p>Aplikasi POS Ayam</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Cabang</strong></td>
            <td>: {{ $nama_cabang }}</td>
            <td width="15%"><strong>Periode</strong></td>
            <td class="text-right">: {{ date('d M Y', strtotime($startDate)) }} s/d
                {{ date('d M Y', strtotime($endDate)) }}</td>
        </tr>
        <tr>
            <td><strong>Dicetak Oleh</strong></td>
            <td>: {{ $dicetak_oleh }}</td>
            <td><strong>Waktu Cetak</strong></td>
            <td class="text-right">: {{ date('d M Y, H:i') }}</td>
        </tr>
    </table>

    {{-- RINGKASAN --}}
    <div style="margin-bottom: 20px; border: 1px solid #333; padding: 10px;">
        <table width="100%">
            <tr>
                <td align="center">
                    <small>TOTAL TRANSAKSI</small><br>
                    <strong style="font-size: 14pt;">{{ $total_transaksi }}</strong>
                </td>
                <td align="center" style="border-left: 1px solid #ccc;">
                    <small>TOTAL OMZET</small><br>
                    <strong style="font-size: 14pt;">Rp {{ number_format($total_omzet, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    {{-- MENU TERLARIS --}}
    <h4>Menu Terlaris (Top 5)</h4>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th width="15%" class="text-center">Terjual</th>
                <th width="20%" class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk_terjual->take(5) as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td class="text-center">{{ $item->total_qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DETAIL TRANSAKSI --}}
    <h4>Riwayat Transaksi Lengkap</h4>
    <table class="data">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>No Transaksi</th>
                <th>Kasir</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $trx)
                <tr>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->nomor_transaksi }}</td>
                    <td>{{ $trx->user->name }}</td>
                    <td class="text-right">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem POS Ayam.
    </div>

</body>

</html>
