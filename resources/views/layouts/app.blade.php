<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'KasirApp') }}</title>

    {{-- CSS & Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- 1. WAJIB: Livewire Styles --}}
    @livewireStyles

    {{-- 2. Alpine JS (Untuk input uang otomatis) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-color: #f4f6f9;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            padding-bottom: 90px;
        }

        /* Navbar Bawah */
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
            color: #764ba2;
        }

        /* Tombol Tengah (Kasir) */
        .nav-item-center {
            position: relative;
            bottom: 25px;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .nav-item-center .circle {
            background: var(--primary-gradient);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 8px 15px rgba(118, 75, 162, 0.4);
            color: white;
            border: 4px solid #fff;
        }
    </style>
</head>

<body>
    <div id="app">
        <main>
            {{-- 3. KUNCI UTAMA: Slot untuk Livewire & Yield untuk halaman biasa --}}
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @auth
            <div class="bottom-navbar">
                <a href="{{ url('/dashboard') }}" class="nav-item-mobile {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
                <a href="{{ route('produk.index') }}"
                    class="nav-item-mobile {{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="fa fa-box"></i> <span>Produk</span>
                </a>
                <a href="{{ route('transaksi.index') }}" class="nav-item-center">
                    <div class="circle"><i class="fa fa-cash-register fa-lg"></i></div>
                    <span class="small font-weight-bold text-dark mt-1 d-block">Kasir</span>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="nav-item-mobile {{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="fa fa-chart-pie"></i> <span>Laporan</span>
                </a>
                <a href="{{ route('profil.index') }}"
                    class="nav-item-mobile {{ request()->is('profil*') ? 'active' : '' }}">
                    <i class="fa fa-user-circle"></i> <span>Profil</span>
                </a>
            </div>
        @endauth
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 4. WAJIB: Livewire Scripts --}}
    @livewireScripts
</body>

</html>
