<!DOCTYPE html>
<html>

<head>
    <title>Struk #{{ $transaksi->nomor_transaksi }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 58mm;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ config('app.name') }}</h3>
        <p>Jl. Ayam Goreng No. 1<br>Telp: 0812-3456-7890</p>
    </div>

    <div class="line"></div>
    <div>
        No: {{ $transaksi->nomor_transaksi }}<br>
        Kasir: {{ $transaksi->user->name }}<br>
        Tgl: {{ $transaksi->created_at->format('d/m/Y H:i') }}
    </div>
    <div class="line"></div>

    @foreach ($transaksi->details as $detail)
        <div style="margin-bottom: 2px;">
            <div class="bold">{{ $detail->produk->nama }}</div>
            <div class="flex">
                <span>{{ $detail->jumlah }} x {{ number_format($detail->harga_satuan) }}</span>
                <span>{{ number_format($detail->subtotal) }}</span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>
    <div class="flex bold">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksi->total_belanja) }}</span>
    </div>
    <div class="flex">
        <span>BAYAR</span>
        <span>Rp {{ number_format($transaksi->bayar) }}</span>
    </div>
    <div class="flex">
        <span>KEMBALI</span>
        <span>Rp {{ number_format($transaksi->kembalian) }}</span>
    </div>
    <div class="line"></div>

    <div class="text-center" style="margin-top: 10px;">
        Terima Kasih<br>Selamat Menikmati
    </div>
</body>

</html>
