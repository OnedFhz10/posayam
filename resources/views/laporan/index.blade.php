@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* 1. GLOBAL STYLE */
        body {
            background-color: #f4f7fc;
            /* Warna background abu sangat muda */
            font-family: 'Poppins', sans-serif;
            color: #495057;
        }

        /* 2. CARD MODERN (Kotak Putih) */
        .card-modern {
            border: none;
            border-radius: 20px;
            /* Sudut sangat membulat */
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            /* Bayangan sangat halus */
            transition: transform 0.3s ease;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            /* Efek naik dikit pas di-hover */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
        }

        /* 3. INPUT FILTER */
        .form-control-modern {
            border-radius: 12px;
            border: 2px solid #edf2f7;
            background-color: #f8fafc;
            padding: 12px 15px;
            font-size: 0.9rem;
            color: #2d3748;
            transition: all 0.3s;
        }

        .form-control-modern:focus {
            background-color: #fff;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-label-modern {
            font-size: 0.8rem;
            font-weight: 600;
            color: #718096;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* 4. TOMBOL KEREN */
        .btn-modern {
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            transition: all 0.3s;
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-2px);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #feada6 100%);
            /* Peach gradient */
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5253 100%);
            /* Red gradient */
            color: white;
            box-shadow: 0 4px 15px rgba(238, 82, 83, 0.3);
        }

        .btn-gradient-danger:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* 5. STATS CARD (Total Omzet) */
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white !important;
        }

        /* 6. TABEL CANTIK */
        .table-custom thead th {
            border-top: none;
            border-bottom: none;
            background-color: #f8fafc;
            color: #a0aec0;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px;
        }

        .table-custom tbody tr {
            background-color: white;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-custom td {
            padding: 18px 20px;
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .badge-soft {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
            margin-right: 12px;
        }
    </style>

    <div class="container-fluid pb-5">

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="font-weight-bold text-dark mb-0">Laporan Transaksi</h2>
                <p class="text-muted small mb-0">Pantau performa penjualanmu hari ini.</p>
            </div>
        </div>

        <div class="card-modern px-4 py-4">
            <form action="{{ route('laporan.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label-modern">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control-modern" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label-modern">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control-modern" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex">
                        <button type="submit" class="btn btn-modern btn-gradient-primary w-50 mr-2">
                            <i class="fa fa-filter"></i> Terapkan
                        </button>
                        <a href="{{ route('laporan.export_pdf', request()->all()) }}"
                            class="btn btn-modern btn-gradient-danger w-50">
                            <i class="fa fa-file-pdf"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card-modern bg-gradient-info text-white p-4"
                    style="min-height: 200px; display: flex; flex-direction: column; justify-content: center;">
                    <div class="d-flex align-items-center mb-3">
                        <div style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px;">
                            <i class="fa fa-wallet fa-2x"></i>
                        </div>
                        <div class="ml-3">
                            <h6 class="mb-0" style="opacity: 0.9; font-weight: 300;">Total Pemasukan</h6>
                        </div>
                    </div>
                    <h2 class="font-weight-bold mb-1">Rp {{ number_format($total_omzet, 0, ',', '.') }}</h2>
                    <p class="mb-0 small" style="opacity: 0.8;">Dari total {{ $total_transaksi }} transaksi berhasil.</p>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-modern" style="min-height: 200px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="font-weight-bold text-dark mb-0">Menu Terlaris</h5>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="table-responsive" style="max-height: 140px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @forelse($terjualPerItem as $item)
                                        <tr>
                                            <td class="pl-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        style="width: 8px; height: 8px; background: #667eea; border-radius: 50%; margin-right: 10px;">
                                                    </div>
                                                    <span
                                                        class="font-weight-600 text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                                </div>
                                            </td>
                                            <td class="pr-4 py-3 text-right">
                                                <span class="badge badge-light text-primary font-weight-bold px-3 py-2"
                                                    style="font-size: 0.9rem;">
                                                    {{ $item->total_qty }} Pcs
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">Belum ada data penjualan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-modern mt-2">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold text-dark">Riwayat Transaksi Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4">Waktu</th>
                                <th>Kasir</th>
                                <th class="text-right">Total Belanja</th>
                                <th class="text-center pr-4">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $transaksi)
                                <tr>
                                    <td class="pl-4">
                                        <div class="font-weight-bold text-dark">
                                            {{ $transaksi->created_at->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $transaksi->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <span
                                                class="font-weight-600">{{ $transaksi->user->name ?? 'User Hapus' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold" style="color: #2d3748;">
                                        Rp {{ number_format($transaksi->total_belanja, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pr-4">
                                        <a href="#" class="btn btn-sm btn-light text-muted"
                                            style="border-radius: 50%; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="https://img.icons8.com/ios/100/e2e8f0/empty-box.png"
                                            style="width: 60px; margin-bottom: 15px;" />
                                        <p class="text-muted">Tidak ada data transaksi ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
