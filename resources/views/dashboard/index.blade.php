@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="font-weight-bold text-dark">Dashboard Ringkasan</h3>
            <p class="text-muted">Selamat datang kembali, <b>{{ Auth::user()->name }}</b>! Berikut performa toko hari ini.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none; border-radius:15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8; font-size: 0.85rem; letter-spacing: 1px;">
                                Transaksi Hari Ini</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $transaksi_hari_ini ?? '0' }}</h2>
                        </div>
                        <div
                            style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small style="opacity: 0.8;"><i class="fa fa-arrow-up"></i> Data real-time</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border:none; border-radius:15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8; font-size: 0.85rem; letter-spacing: 1px;">
                                Omzet Hari Ini</h6>
                            <h2 class="mb-0 font-weight-bold">Rp {{ number_format($omzet_hari_ini ?? 0, 0, ',', '.') }}</h2>
                        </div>
                        <div
                            style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-wallet fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small style="opacity: 0.8;"><i class="fa fa-check-circle"></i> Pemasukan bersih</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #feada6 100%); border:none; border-radius:15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8; font-size: 0.85rem; letter-spacing: 1px;">
                                Total Menu</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $total_produk ?? '0' }}</h2>
                        </div>
                        <div
                            style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-utensils fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small style="opacity: 0.8;"><i class="fa fa-box-open"></i> Item tersedia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="font-weight-bold text-dark">Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('laporan.index') }}"
                                class="btn btn-light btn-block p-3 text-left shadow-sm border">
                                <i class="fa fa-file-invoice text-primary mr-2"></i> Lihat Laporan
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#" class="btn btn-light btn-block p-3 text-left shadow-sm border">
                                <i class="fa fa-plus-circle text-success mr-2"></i> Tambah Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
