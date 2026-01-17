@extends('layouts.app')

@section('content')
    <style>
        /* === TEAL THEME VARIABLES === */
        :root {
            --teal-primary: #1abc9c;
            --teal-dark: #16a085;
            --bg-light: #e3f2fd;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
        }

        /* Header Sticky */
        .header-sticky {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: var(--bg-light);
            backdrop-filter: blur(5px);
            padding-top: 15px;
            padding-bottom: 15px;
        }

        /* Kartu Ringkasan */
        .summary-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            padding: 20px;
            transition: transform 0.2s;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .summary-card:hover {
            transform: translateY(-3px);
        }

        .summary-teal {
            background: linear-gradient(135deg, var(--teal-primary) 0%, var(--teal-dark) 100%);
            color: white;
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .icon-white-bg {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .icon-teal-bg {
            background: #e0f2f1;
            color: var(--teal-dark);
        }

        /* Input Date Modern */
        .form-control-pill {
            border-radius: 50px;
            border: none;
            padding: 10px 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            color: #555;
        }

        .form-control-pill:focus {
            outline: 2px solid var(--teal-primary);
            box-shadow: 0 4px 15px rgba(26, 188, 156, 0.2);
        }

        .btn-filter {
            border-radius: 50px;
            background: var(--teal-dark);
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(22, 160, 133, 0.3);
            transition: 0.2s;
        }

        .btn-filter:hover {
            background: #149174;
            transform: translateY(-2px);
        }

        /* Tabel & List Style */
        .custom-table thead th {
            background-color: #e0f2f1;
            color: var(--teal-dark);
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .custom-table tbody td {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 15px;
            vertical-align: middle;
        }

        .custom-table tbody tr:first-child td:first-child {
            border-top-left-radius: 15px;
        }

        .custom-table tbody tr:first-child td:last-child {
            border-top-right-radius: 15px;
        }

        .custom-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 15px;
        }

        .custom-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 15px;
        }

        /* Mobile Transaction Card */
        .trx-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid var(--teal-primary);
            cursor: pointer;
            transition: 0.2s;
        }

        .trx-card:active {
            transform: scale(0.98);
            background: #f8f9fa;
        }
    </style>

    <div class="container-fluid px-4">

        {{-- 1. HEADER & FILTER --}}
        <div class="header-sticky">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-0">Laporan Penjualan</h4>
                    <small class="text-muted">Pantau performa bisnis anda</small>
                </div>
                {{-- Tombol Print (Optional) --}}
                <button class="btn btn-sm btn-light rounded-circle shadow-sm text-secondary d-none d-md-block"
                    onclick="window.print()">
                    <i class="fas fa-print"></i>
                </button>
            </div>

            {{-- Form Filter Tanggal --}}
            <form action="{{ route('laporan.index') }}" method="GET" class="row g-2 align-items-center mb-2">
                <div class="col-6 col-md-3">
                    <input type="date" name="start_date" class="form-control form-control-pill"
                        value="{{ request('start_date', date('Y-m-01')) }}">
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="end_date" class="form-control form-control-pill"
                        value="{{ request('end_date', date('Y-m-d')) }}">
                </div>

                {{-- Tombol Filter --}}
                <div class="col-6 col-md-3">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>

                {{-- TOMBOL EXPORT PDF (BARU) --}}
                <div class="col-6 col-md-3">
                    <a href="{{ route('laporan.export_pdf', ['start_date' => request('start_date', date('Y-m-01')), 'end_date' => request('end_date', date('Y-m-d'))]) }}"
                        class="btn btn-danger w-100"
                        style="border-radius: 50px; font-weight: 600; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-file-pdf me-2"></i> PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- 2. RINGKASAN --}}
        <div class="row g-3 mb-4">
            {{-- Total Omzet --}}
            <div class="col-12 col-md-6">
                <div class="summary-card summary-teal">
                    <div class="summary-icon icon-white-bg">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <small class="opacity-75 fw-bold">TOTAL OMZET (PERIODE INI)</small>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($total_omzet ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total Transaksi --}}
            <div class="col-12 col-md-6">
                <div class="summary-card">
                    <div class="summary-icon icon-teal-bg">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold">TOTAL TRANSAKSI</small>
                        <h3 class="fw-bold text-dark mb-0">{{ $total_transaksi ?? 0 }} Transaksi</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2.5. MENU TERLARIS --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-crown text-warning me-2"></i>Menu Terlaris
                </h5>
                <small class="text-muted">Rincian produk (termasuk isi paket) yang terjual</small>
            </div>
            <div class="card-body p-4">

                {{-- Tampilan Tabel (Desktop) --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-borderless align-middle">
                        <thead class="text-secondary small border-bottom">
                            <tr>
                                <th width="5%">RANK</th>
                                <th width="40%">NAMA MENU</th>
                                <th width="15%">KATEGORI</th>
                                <th width="20%">TERJUAL</th>
                                <th width="20%" class="text-end">PENDAPATAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produk_terjual as $index => $item)
                                <tr>
                                    <td class="fw-bold text-center">
                                        @if ($index == 0)
                                            🥇
                                        @elseif($index == 1)
                                            🥈
                                        @elseif($index == 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">{{ $item->nama }}</td>
                                    <td>
                                        <span
                                            class="badge bg-light text-secondary border rounded-pill px-3">{{ $item->kategori }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">{{ $item->total_qty }}</span>
                                            {{-- Progress Bar Visual --}}
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 100px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ ($item->total_qty / ($produk_terjual->max('total_qty') > 0 ? $produk_terjual->max('total_qty') : 1)) * 100 }}%; background-color: var(--teal-primary);">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-dark">Rp
                                        {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data penjualan produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tampilan List (Mobile) --}}
                <div class="d-md-none">
                    @forelse($produk_terjual as $index => $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom border-dashed py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3 fw-bold text-secondary" style="width: 20px;">#{{ $index + 1 }}</div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $item->nama }}</h6>
                                    <small class="text-muted">{{ $item->kategori }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="d-block fw-bold fs-5 text-dark"
                                    style="color: var(--teal-dark) !important;">{{ $item->total_qty }} <small
                                        class="fs-6 text-muted">Pcs</small></span>
                                <small class="text-muted" style="font-size: 0.75rem;">Rp
                                    {{ number_format($item->total_pendapatan, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Belum ada data penjualan.</div>
                    @endforelse
                </div>

            </div>
        </div>
        {{-- 3. DAFTAR TRANSAKSI (DENGAN TOMBOL HAPUS) --}}

        {{-- Tampilan Desktop (Tabel) --}}
        <div class="d-none d-md-block mb-5">
            <div class="table-responsive">
                <table class="table custom-table table-borderless">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                            <tr>
                                <td class="fw-bold text-secondary">{{ $trx->nomor_transaksi }}</td>
                                <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $trx->user->name ?? 'Kasir' }}</td>
                                <td class="fw-bold text-dark">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                <td>
                                    {{-- UPDATE: TOMBOL AKSI --}}
                                    <div class="d-flex gap-1">
                                        {{-- Tombol Detail (Bisa dilihat Semua Role) --}}
                                        <button
                                            class="btn btn-sm btn-light rounded-pill text-primary fw-bold px-3 shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalDetail{{ $trx->id }}">
                                            Detail
                                        </button>

                                        {{-- Tombol Hapus (HANYA ADMIN) --}}
                                        @if (auth()->user()->role == 'admin')
                                            <form action="{{ route('laporan.destroy', $trx->id) }}" method="POST"
                                                onsubmit="return confirm('HAPUS TRANSAKSI INI?\n\nStok produk akan dikembalikan otomatis.\nData yang dihapus tidak bisa dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-light rounded-circle text-danger shadow-sm"
                                                    title="Hapus Laporan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Detail (Include di dalam loop) --}}
                            @include('laporan.modal_detail', ['trx' => $trx])

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-search fa-2x mb-3 opacity-25"></i>
                                    <p>Tidak ada transaksi pada periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tampilan Mobile (List Card) --}}
        <div class="d-md-none pb-5">
            <h6 class="fw-bold text-muted mb-3">Riwayat Transaksi</h6>
            @forelse($transaksis as $trx)
                <div class="trx-card" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $trx->id }}">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</h6>
                        <small class="text-muted">{{ $trx->nomor_transaksi }} •
                            {{ $trx->created_at->format('H:i') }}</small>
                    </div>
                    <div class="text-secondary">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                {{-- Modal Detail Mobile (Include) --}}
                @include('laporan.modal_detail', ['trx' => $trx])

            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                    <p>Belum ada data.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
