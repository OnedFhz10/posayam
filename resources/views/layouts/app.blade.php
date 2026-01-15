<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KasirApp') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    @livewireStyles

    <style>
        /* === GLOBAL STYLE === */
        body {
            background-color: #e3f2fd;
            font-family: 'Poppins', sans-serif;
            color: #444;
        }

        /* === 1. MOBILE BOTTOM NAVBAR (Hanya muncul di HP) === */
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            padding: 10px 0;
            z-index: 1000;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .nav-item-mobile {
            text-align: center;
            color: #a0aec0;
            flex: 1;
            padding-bottom: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .nav-item-mobile i {
            display: block;
            font-size: 1.3rem;
            margin-bottom: 2px;
        }

        .nav-item-mobile span {
            font-size: 0.7rem;
            font-weight: 500;
            display: block;
        }

        .nav-item-mobile.active {
            color: #1abc9c;
        }

        /* Tombol Kasir Bulat (Mobile) */
        .nav-item-center {
            position: relative;
            bottom: 25px;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .nav-item-center .circle {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 8px 15px rgba(26, 188, 156, 0.4);
            color: white;
            border: 4px solid #fff;
            transition: transform 0.2s;
        }

        /* === 2. DESKTOP SIDEBAR (Hanya muncul di Laptop) === */
        .desktop-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #fff;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1abc9c;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            padding-left: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 5px;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: #f1f5f9;
            color: #1abc9c;
        }

        .sidebar-menu a.active {
            background-color: #e0f2f1;
            color: #1abc9c;
            font-weight: 600;
        }

        .sidebar-menu a.active i {
            color: #1abc9c;
        }

        /* === 3. RESPONSIVE CONTROLLER === */

        /* Default (Mobile): Sidebar Hilang, Main Full */
        .desktop-sidebar {
            display: none;
        }

        main {
            padding-bottom: 90px;
        }

        /* Jarak untuk navbar bawah */

        /* Layar Besar (Desktop >= 768px) */
        @media (min-width: 768px) {
            .bottom-navbar {
                display: none !important;
            }

            /* Sembunyikan Nav Bawah */
            .desktop-sidebar {
                display: flex;
            }

            /* Tampilkan Sidebar */

            /* Geser konten utama ke kanan agar tidak tertutup sidebar */
            main {
                margin-left: 250px;
                padding-bottom: 0;
            }
        }
    </style>
</head>

<body>
    <div id="app">

        @auth
            {{-- A. SIDEBAR DESKTOP (Kiri) --}}
            <div class="desktop-sidebar">
                <div class="sidebar-brand">
                    <i class="fas fa-cash-register"></i> KasirApp
                </div>

                <div class="sidebar-menu flex-grow-1">
                    <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home fa-fw"></i> Dashboard
                    </a>

                    <a href="{{ route('transaksi.index') }}" class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register fa-fw"></i> Menu Kasir
                    </a>

                    <a href="{{ route('produk.index') }}" class="{{ request()->is('produk*') ? 'active' : '' }}">
                        <i class="fas fa-box fa-fw"></i> Kelola Produk
                    </a>

                    <a href="{{ route('laporan.index') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie fa-fw"></i> Laporan
                    </a>
                </div>

                <div class="sidebar-menu border-top pt-3">
                    {{-- Profil / Logout --}}
                    <a href="{{ Route::has('profil.index') ? route('profil.index') : '#' }}"
                        class="{{ request()->is('profil*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle fa-fw"></i> Akun Saya
                    </a>
                </div>
            </div>
        @endauth

        {{-- B. KONTEN UTAMA --}}
        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @auth
            {{-- C. NAVBAR MOBILE (Bawah) --}}
            <div class="bottom-navbar">
                <a href="{{ url('/dashboard') }}"
                    class="nav-item-mobile {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> <span>Home</span>
                </a>
                <a href="{{ route('produk.index') }}"
                    class="nav-item-mobile {{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> <span>Produk</span>
                </a>
                <a href="{{ route('transaksi.index') }}" class="nav-item-center">
                    <div class="circle"><i class="fas fa-cash-register fa-lg"></i></div>
                    <span class="small fw-bold text-dark mt-1 d-block" style="font-size: 0.7rem;">Kasir</span>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="nav-item-mobile {{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> <span>Laporan</span>
                </a>
                <a href="{{ Route::has('profil.index') ? route('profil.index') : '#' }}"
                    class="nav-item-mobile {{ request()->is('profil*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> <span>Profil</span>
                </a>
            </div>
        @endauth
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
