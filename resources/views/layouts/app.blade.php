<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KasirApp') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-color: #f4f7fc;
            --active-color: #764ba2;
            --inactive-color: #a0aec0;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: #495057;
            font-size: 0.9rem;
            padding-bottom: 80px;
        }

        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0 15px 0;
            /* Padding bawah lebih besar untuk HP layar poni */
            z-index: 1000;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .nav-item-mobile {
            text-align: center;
            color: var(--inactive-color);
            text-decoration: none;
            flex: 1;
            transition: all 0.3s;
        }

        .nav-item-mobile i {
            display: block;
            font-size: 1.4rem;
            margin-bottom: 2px;
        }

        .nav-item-mobile span {
            font-size: 0.7rem;
            font-weight: 500;
        }

        .nav-item-mobile.active {
            color: var(--active-color);
        }

        .nav-item-mobile.active i {
            transform: translateY(-2px);
        }

        /* --- STYLE DESKTOP (SIDEBAR) --- */
        /* Sembunyikan Sidebar di HP */
        @media (max-width: 768px) {
            .desktop-sidebar {
                display: none !important;
            }

            .desktop-navbar {
                display: none !important;
            }

            /* Opsional: Sembunyikan navbar atas di HP jika mau full app feel */
            .mobile-header {
                display: block !important;
            }
        }

        /* Sembunyikan Bottom Nav di Desktop */
        @media (min-width: 769px) {
            .bottom-navbar {
                display: none !important;
            }

            .mobile-header {
                display: none !important;
            }

            body {
                padding-bottom: 0;
            }

            /* Reset padding di desktop */
        }

        /* --- CARD & ELEMENT STYLE --- */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            background: #fff;
            margin-bottom: 15px;
        }

        .btn {
            border-radius: 10px;
        }

        .form-control {
            border-radius: 10px;
            padding: 20px 15px;
        }

        /* Mobile Header Simple */
        .mobile-header {
            background: #fff;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div id="app">

        <div class="mobile-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold" style="color: #764ba2;">
                <i class="fa fa-store mr-1"></i> {{ config('app.name', 'Kasir') }}
            </h5>
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle"
                        style="width: 35px; height: 35px;">
                        <i class="fa fa-power-off"></i>
                    </button>
                </form>
            @endauth
        </div>

        <nav class="navbar navbar-expand-md navbar-light bg-white desktop-navbar">
            <div class="container">
                <a class="navbar-brand font-weight-bold text-primary" href="{{ url('/') }}">
                    {{ config('app.name', 'KasirApp') }}
                </a>
                <div class="ml-auto">
                    @auth
                        <span>Halo, {{ Auth::user()->name }}</span>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="py-2">
            <div class="container-fluid">
                <div class="row justify-content-center">

                    @auth
                        <div class="col-md-3 desktop-sidebar mb-4">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="nav flex-column nav-pills">
                                        <a class="nav-link {{ request()->is('/') || request()->is('home*') ? 'active bg-primary text-white' : '' }}"
                                            href="{{ url('/') }}">
                                            <i class="fa fa-home mr-2"></i> Dashboard
                                        </a>
                                        <a class="nav-link" href="#">
                                            <i class="fa fa-shopping-cart mr-2"></i> Transaksi
                                        </a>
                                        <a class="nav-link" href="#">
                                            <i class="fa fa-box mr-2"></i> Produk
                                        </a>
                                        <a class="nav-link {{ request()->is('laporan*') ? 'active bg-primary text-white' : '' }}"
                                            href="{{ route('laporan.index') }}">
                                            <i class="fa fa-file-invoice mr-2"></i> Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <div class="col-md-9 col-12">
                        @yield('content')
                    </div>

                </div>
            </div>
        </main>

        @auth
            <div class="bottom-navbar">

                <a href="{{ url('/') }}"
                    class="nav-item-mobile {{ request()->is('/') || request()->is('home') ? 'active' : '' }}">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>

                <a href="#" class="nav-item-mobile {{ request()->is('transaksi*') ? 'active' : '' }}">
                    <i class="fa fa-cash-register"></i>
                    <span>Kasir</span>
                </a>

                <a href="#" class="nav-item-mobile">
                    <div
                        style="background: var(--primary-gradient); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: -25px auto 5px auto; box-shadow: 0 5px 10px rgba(118, 75, 162, 0.4); color: white;">
                        <i class="fa fa-plus" style="font-size: 1.2rem; margin-bottom: 0;"></i>
                    </div>
                    <span>Produk</span>
                </a>

                <a href="{{ route('laporan.index') }}"
                    class="nav-item-mobile {{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="fa fa-chart-pie"></i>
                    <span>Laporan</span>
                </a>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-btm').submit();"
                    class="nav-item-mobile">
                    <i class="fa fa-user"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-btm" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

            </div>
        @endauth

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
