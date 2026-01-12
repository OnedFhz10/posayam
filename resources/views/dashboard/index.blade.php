@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 pt-3">

        {{-- Header Sederhana --}}
        <div class="d-flex align-items-center mb-4">
            <div>
                <h5 class="fw-bold text-dark mb-0">Ringkasan Hari Ini</h5>
                <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
            </div>
        </div>

        <div class="row g-3">
            {{-- KARTU 1: OMZET HARI INI --}}
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-4 text-white position-relative">
                        {{-- Hiasan Background --}}
                        <i class="fas fa-coins position-absolute"
                            style="font-size: 8rem; opacity: 0.1; right: -20px; top: -20px;"></i>

                        <div class="position-relative z-1">
                            <p class="mb-1 text-white-50 fw-bold small text-uppercase ls-1">Omzet Penjualan</p>
                            <h2 class="fw-bold mb-0 display-5">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</h2>
                            <div class="mt-3 badge bg-white bg-opacity-25 fw-normal px-3 py-2 rounded-pill">
                                <i class="fas fa-calendar-day me-1"></i> Hari Ini
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: TRANSAKSI HARI INI --}}
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between position-relative z-1">
                            <div>
                                <p class="mb-1 text-muted fw-bold small text-uppercase ls-1">Transaksi Sukses</p>
                                <h2 class="fw-bold mb-0 text-dark display-5">{{ number_format($transaksiHariIni) }}</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted small">
                                <i class="fas fa-check-circle text-success me-1"></i> Data real-time
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Akses Cepat (Opsional) --}}
        <div class="mt-5">
            <h6 class="fw-bold text-dark mb-3">Menu Cepat</h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('transaksi.index') }}"
                        class="btn btn-white border shadow-sm w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2">
                        <i class="fas fa-cash-register fa-2x text-primary"></i>
                        <span class="small fw-bold text-dark">Kasir</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('laporan.index') }}"
                        class="btn btn-white border shadow-sm w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2">
                        <i class="fas fa-file-invoice fa-2x text-success"></i>
                        <span class="small fw-bold text-dark">Laporan</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
