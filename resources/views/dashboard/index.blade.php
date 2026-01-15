@extends('layouts.app')

@section('content')
    <style>
        /* === TEAL THEME === */
        body {
            background-color: #e3f2fd;
            font-family: 'Poppins', sans-serif;
        }

        /* Cards */
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            background: #fff;
            overflow: hidden;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        /* Omzet Card Gradient */
        .card-omzet {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: white;
        }

        /* Icon Circles */
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .icon-bg-white {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .icon-bg-teal {
            background-color: #e0f2f1;
            color: #16a085;
        }

        /* Menu Cepat */
        .btn-menu-cepat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: #555;
            transition: all 0.3s;
            height: 100%;
        }

        .btn-menu-cepat:hover {
            background-color: #1abc9c;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(26, 188, 156, 0.3);
        }

        .btn-menu-cepat i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #1abc9c;
            transition: color 0.3s;
        }

        .btn-menu-cepat:hover i {
            color: white;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>

    <div class="container-fluid px-4 py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Ringkasan Hari Ini</h4>
                <small class="text-muted">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</small>
            </div>
        </div>

        {{-- KARTU STATISTIK (HARI INI) --}}
        <div class="row g-4 mb-4">
            {{-- Kartu Omzet --}}
            <div class="col-12 col-md-6">
                <div class="dashboard-card card-omzet p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75 fw-bold">OMZET PENJUALAN</p>
                            <h2 class="fw-bold mb-0">Rp {{ number_format($omzet ?? 0, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon-circle icon-bg-white">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kartu Transaksi --}}
            <div class="col-12 col-md-6">
                <div class="dashboard-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted fw-bold small">TRANSAKSI SUKSES</p>
                            <h2 class="fw-bold text-dark mb-0">{{ $transaksi_sukses ?? 0 }}</h2>
                        </div>
                        <div class="icon-circle icon-bg-teal">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- AREA GRAFIK (BARU) --}}
        <div class="row g-4 mb-5">
            {{-- Grafik Penjualan (Line Chart) --}}
            <div class="col-12 col-lg-8">
                <div class="dashboard-card p-4">
                    <h6 class="fw-bold text-dark mb-3">Tren Pendapatan (7 Hari)</h6>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Produk Terlaris (Doughnut Chart) --}}
            <div class="col-12 col-lg-4">
                <div class="dashboard-card p-4">
                    <h6 class="fw-bold text-dark mb-3">5 Menu Terlaris</h6>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="productChart"></canvas>
                    </div>
                    <div class="text-center mt-3 small text-muted">
                        Berdasarkan jumlah item terjual
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Cepat --}}
        <h5 class="fw-bold text-dark mb-3 ps-1 border-start border-4 border-success ps-2">Menu Cepat</h5>
        <div class="row g-3">
            <div class="col-6 col-md-6">
                <a href="{{ route('transaksi.index') }}" class="btn-menu-cepat">
                    <i class="fas fa-cash-register"></i>
                    <span class="fw-bold">Kasir</span>
                </a>
            </div>
            <div class="col-6 col-md-6">
                <a href="{{ route('laporan.index') }}" class="btn-menu-cepat">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="fw-bold">Laporan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. GRAFIK OMZET (Line Chart)
        const ctxSales = document.getElementById('salesChart').getContext('2d');

        // Data dari Controller
        const labels = {!! json_encode($chartLabels) !!};
        const dataOmzet = {!! json_encode($chartOmzet) !!};

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: dataOmzet,
                    borderColor: '#1abc9c', // Warna Garis Teal
                    backgroundColor: 'rgba(26, 188, 156, 0.1)', // Warna Area bawah garis
                    borderWidth: 3,
                    pointBackgroundColor: '#16a085',
                    pointRadius: 4,
                    tension: 0.4, // Membuat garis melengkung halus
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    } // Sembunyikan legenda agar bersih
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. GRAFIK PRODUK TERLARIS (Doughnut Chart)
        const ctxProduct = document.getElementById('productChart').getContext('2d');

        // Data dari Controller
        const productNames = {!! json_encode($namaProduk) !!};
        const productQty = {!! json_encode($jumlahProduk) !!};

        new Chart(ctxProduct, {
            type: 'doughnut',
            data: {
                labels: productNames,
                datasets: [{
                    data: productQty,
                    backgroundColor: [
                        '#1abc9c', // Teal Utama
                        '#2ecc71', // Hijau
                        '#3498db', // Biru
                        '#f1c40f', // Kuning
                        '#e74c3c' // Merah
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
@endsection
