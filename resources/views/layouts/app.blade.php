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
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            color: #444;
            padding-bottom: 100px;
            /* Space for bottom nav */
        }

        /* === 1. MOBILE BOTTOM NAVBAR (Khusus HP) === */
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-evenly;
            /* Meratakan item */
            align-items: center;
            padding: 0 15px 15px 15px;
            /* Padding bawah agar tidak mepet layar HP baru */
            height: 70px;
            z-index: 1050;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }

        .nav-item-mobile {
            text-align: center;
            color: #a0aec0;
            text-decoration: none;
            flex: 1;
            /* Membagi ruang sama rata */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            position: relative;
        }

        .nav-item-mobile i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            transition: 0.3s;
        }

        .nav-item-mobile span {
            font-size: 0.7rem;
            font-weight: 500;
        }

        .nav-item-mobile.active {
            color: #1abc9c;
        }

        .nav-item-mobile.active i {
            transform: translateY(-3px);
        }

        /* Tombol Kasir Bulat (Tengah) */
        .nav-item-center {
            position: relative;
            bottom: 35px;
            /* Naik ke atas */
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            text-decoration: none;
            z-index: 1060;
        }

        .nav-item-center .circle {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.4);
            color: white;
            border: 5px solid #f4f6f9;
            /* Border warna background body agar terlihat melayang */
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .nav-item-center .circle:active {
            transform: scale(0.95);
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
        }

        .nav-item-center span {
            font-size: 0.75rem;
            font-weight: 700;
            color: #444;
            margin-top: 5px;
        }

        /* === 2. DESKTOP SIDEBAR === */
        .desktop-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: #fff;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.03);
            z-index: 1000;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1abc9c;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding: 0 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: #718096;
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background-color: #f7fafc;
            color: #1abc9c;
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background-color: #e6fffa;
            color: #1abc9c;
            font-weight: 600;
        }

        /* Responsive Controller */
        .desktop-sidebar {
            display: none;
        }

        @media (min-width: 992px) {
            .bottom-navbar {
                display: none !important;
            }

            .desktop-sidebar {
                display: flex;
            }

            main {
                margin-left: 260px;
                padding-bottom: 30px;
            }

            body {
                padding-bottom: 0;
            }
        }
    </style>
</head>

<body>
    <div id="app">

        @auth
            {{-- A. SIDEBAR DESKTOP --}}
            <div class="desktop-sidebar">
                <div class="sidebar-brand">
                    <i class="fas fa-chicken"></i> PosAyam
                </div>

                <div class="sidebar-menu flex-grow-1">
                    {{-- 1. DASHBOARD (Admin Only) --}}
                    @if (auth()->user()->role == 'admin')
                        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="fas fa-th-large fa-fw"></i> Dashboard
                        </a>
                    @endif

                    {{-- 2. KASIR (Kasir Only) --}}
                    @if (auth()->user()->role == 'kasir')
                        <a href="{{ route('transaksi.index') }}" class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register fa-fw"></i> Menu Kasir
                        </a>
                    @endif

                    {{-- 3. LAPORAN (Semua) --}}
                    <a href="{{ route('laporan.index') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie fa-fw"></i> Laporan
                    </a>

                    {{-- 4. MENU ADMIN --}}
                    @if (auth()->user()->role == 'admin')
                        <div class="small fw-bold text-uppercase text-muted mt-4 mb-2 px-3"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Master Data</div>

                        <a href="{{ route('produk.index') }}" class="{{ request()->is('produk*') ? 'active' : '' }}">
                            <i class="fas fa-box-open fa-fw"></i> Produk
                        </a>

                        <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog fa-fw"></i> Karyawan
                        </a>
                    @endif
                </div>

                <div class="sidebar-menu border-top pt-3">
                    <a href="{{ route('profil.index') }}" class="{{ request()->is('profil*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle fa-fw"></i> Profil Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-danger">
                            <i class="fas fa-sign-out-alt fa-fw"></i> Keluar
                        </a>
                    </form>
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

                {{-- 1. Dashboard (HANYA ADMIN) --}}
                @if (auth()->user()->role == 'admin')
                    <a href="{{ url('/dashboard') }}"
                        class="nav-item-mobile {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Home</span>
                    </a>

                    <a href="{{ route('produk.index') }}"
                        class="nav-item-mobile {{ request()->is('produk*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> <span>Produk</span>
                    </a>
                @endif

                {{-- 2. Menu Laporan (Kasir: Kiri, Admin: Kanan) --}}
                <a href="{{ route('laporan.index') }}"
                    class="nav-item-mobile {{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> <span>Laporan</span>
                </a>

                {{-- 3. TOMBOL TENGAH: KASIR (Hanya Kasir) --}}
                @if (auth()->user()->role == 'kasir')
                    <a href="{{ route('transaksi.index') }}" class="nav-item-center">
                        <div class="circle">
                            <i class="fas fa-cash-register fa-lg"></i>
                        </div>
                        <span>Kasir</span>
                    </a>
                @endif

                {{-- 4. Profil (Selalu Ada) --}}
                <a href="{{ route('profil.index') }}"
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
