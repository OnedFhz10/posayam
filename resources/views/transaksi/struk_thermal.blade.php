<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->nomor_transaksi }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font struk jadul */
            width: 58mm;
            /* Ukuran kertas thermal standar */
            margin: 0;
            padding: 5px;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
        }

        .my-2 {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Hilangkan elemen browser saat print */
        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin:0;">POS AYAM</h3>
        <p style="margin:0; font-size:10px;">Cabang: {{ $transaksi->cabang->nama ?? 'Pusat' }}</p>
        <p style="margin:0; font-size:10px;">Jl. Contoh No. 123</p>
    </div>

    <div class="border-bottom"></div>

    <div>
        No: {{ $transaksi->nomor_transaksi }}<br>
        Kasir: {{ $transaksi->user->name }}<br>
        Tgl: {{ $transaksi->created_at->format('d/m/Y H:i') }}
    </div>

    <div class="border-bottom"></div>

    @foreach ($transaksi->details as $item)
        <div style="margin-bottom: 3px;">
            <div class="fw-bold">{{ $item->produk->nama ?? 'Item Hapus' }}</div>
            <div class="d-flex">
                <span>{{ $item->jumlah }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
    @endforeach

    <div class="border-bottom"></div>

    <div class="d-flex fw-bold">
        <span>TOTAL</span>
        <span>{{ number_format($transaksi->total_belanja, 0, ',', '.') }}</span>
    </div>
    <div class="d-flex">
        <span>TUNAI</span>
        <span>{{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
    </div>
    <div class="d-flex">
        <span>KEMBALI</span>
        <span>{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
    </div>

    <div class="border-bottom"></div>
    <div class="text-center my-2">
        <small>Terima Kasih & Selamat Menikmati!</small>
    </div>

</body>

</html>
