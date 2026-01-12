@extends('layouts.app')

@section('content')
    {{-- Font & Animate.css (Opsional jika belum ada di layout) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* GLOBAL STYLE */
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
            color: #495057;
        }

        /* CARD MODERN */
        .card-modern {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
        }

        /* INPUT FILTER STYLE */
        .form-control-modern {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 12px 15px;
            font-size: 0.9rem;
            color: #2d3748;
            transition: all 0.2s;
        }

        .form-control-modern:focus {
            background-color: #fff;
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-label-modern {
            font-size: 0.75rem;
            font-weight: 700;
            color: #a0aec0;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* TOMBOL GRADASI */
        .btn-modern {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            border: none;
            transition: transform 0.2s;
        }

        .btn-modern:active {
            transform: scale(0.96);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(118, 75, 162, 0.3);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5253 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(238, 82, 83, 0.3);
        }

        /* CARD OMZET (Gradient Biru) */
        .bg-gradient-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white !important;
        }

        /* STYLE KHUSUS LIST TRANSAKSI (Mobile Friendly) */
        .transaction-list-item {
            padding: 15px;
            border-bottom: 1px dashed #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .transaction-list-item:last-child {
            border-bottom: none;
        }

        .transaction-list-item:hover {
            background-color: #f8fafc;
        }

        .trx-icon {
            width: 45px;
            height: 45px;
            background: #f3f4f6;
            color: #667eea;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        /* Tombol Hapus Kecil */
        .btn-delete-mini {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #fff5f5;
            color: #e53e3e;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.2s;
        }

        .btn-delete-mini:hover {
            background: #e53e3e;
            color: white;
        }
    </style>

    <div class="container-fluid pb-5 px-3 pt-2">

        {{-- ALERT SUKSES (Floating) --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3 animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- JUDUL HALAMAN --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="font-weight-bold text-dark mb-0">Laporan</h4>
                <p class="text-muted small mb-0">Ringkasan penjualan</p>
            </div>
            {{-- Tombol Filter Mobile --}}
            <button class="btn btn-white shadow-sm rounded-circle text-primary" type="button" data-toggle="collapse"
                data-target="#collapseFilter" style="width: 40px; height: 40px;">
                <i class="fas fa-filter"></i>
            </button>
        </div>

        {{-- FILTER SECTION (Collapsible) --}}
        <div class="collapse mb-3" id="collapseFilter">
            <div class="card-modern p-3">
                <form action="{{ route('laporan.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label-modern">Dari</label>
                            <input type="date" name="start_date" class="form-control-modern" value="{{ $startDate }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label-modern">Sampai</label>
                            <input type="date" name="end_date" class="form-control-modern" value="{{ $endDate }}">
                        </div>
                        <div class="col-12 mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-modern btn-gradient-primary w-100">
                                Filter
                            </button>
                            <a href="{{ route('laporan.export_pdf', request()->all()) }}"
                                class="btn btn-modern btn-gradient-danger w-100">
                                PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- KARTU OMZET & STATISTIK --}}
        <div class="row g-3 mb-3">
            {{-- Kartu Omzet --}}
            <div class="col-12 col-md-5">
                <div class="card-modern bg-gradient-info p-4 text-white position-relative">
                    <i class="fas fa-coins position-absolute"
                        style="right: -10px; top: -10px; font-size: 6rem; opacity: 0.1;"></i>
                    <p class="mb-1 small text-uppercase" style="opacity: 0.8; letter-spacing: 1px;">Omzet Penjualan</p>
                    <h2 class="font-weight-bold mb-0">Rp {{ number_format($total_omzet, 0, ',', '.') }}</h2>
                    <div class="mt-2 badge bg-white bg-opacity-25 fw-normal px-2 rounded-pill">
                        {{ $total_transaksi }} Transaksi Berhasil
                    </div>
                </div>
            </div>

            {{-- Kartu Menu Terlaris (List Compact) --}}
            <div class="col-12 col-md-7">
                <div class="card-modern h-100">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-trophy text-warning mr-2"></i>Top Menu
                        </h6>
                    </div>
                    <div class="p-2">
                        @forelse($terjualPerItem->take(3) as $index => $item)
                            <div class="d-flex align-items-center p-2 rounded hover-bg-light">
                                <div class="font-weight-bold text-muted text-center" style="width: 25px;">
                                    {{ $index + 1 }}</div>
                                <div class="flex-grow-1 mx-2">
                                    <div class="font-weight-bold text-dark small text-truncate">
                                        {{ $item->produk->nama ?? 'Dihapus' }}</div>
                                    <div class="progress" style="height: 4px; width: 80%;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ ($item->total_qty / ($terjualPerItem->max('total_qty') ?: 1)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-primary border">{{ $item->total_qty }}</span>
                            </div>
                        @empty
                            <p class="text-center text-muted small py-3">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT TRANSAKSI (LIST VIEW - BUKAN TABEL) --}}
        <div class="card-modern">
            <div class="p-3 border-bottom bg-white sticky-top" style="top: 0; z-index: 10;">
                <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-history text-secondary mr-2"></i>Riwayat
                    Transaksi</h6>
            </div>

            <div class="transaction-list">
                @forelse ($transaksis as $transaksi)
                    <div class="transaction-list-item">
                        {{-- Kiri: Icon & Info --}}
                        <div class="d-flex align-items-center overflow-hidden">
                            <div class="trx-icon flex-shrink-0">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div style="min-width: 0;">
                                <h6 class="mb-0 font-weight-bold text-dark text-truncate">Rp
                                    {{ number_format($transaksi->total_belanja, 0, ',', '.') }}</h6>
                                <div class="small text-muted text-truncate">
                                    <i class="far fa-clock mr-1"></i> {{ $transaksi->created_at->format('H:i') }}
                                    <span class="mx-1">•</span>
                                    <i class="far fa-user mr-1"></i> {{ explode(' ', $transaksi->user->name)[0] }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.65rem;">
                                    {{ $transaksi->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Tombol Hapus --}}
                        <div class="ml-2">
                            <form onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.');"
                                action="{{ route('laporan.destroy', $transaksi->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-mini shadow-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted small">Belum ada transaksi pada periode ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
