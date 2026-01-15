@extends('layouts.app')

@section('content')
    <style>
        /* === TEAL THEME SETUP === */
        :root {
            --teal-primary: #1abc9c;
            --teal-dark: #16a085;
            --bg-light: #e3f2fd;
        }

        body {
            background-color: var(--bg-light);
        }

        .header-sticky {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: var(--bg-light);
            padding-top: 15px;
            padding-bottom: 0px;
            backdrop-filter: blur(5px);
        }

        /* Tab Navigasi Utama (Aktif/Arsip) */
        .nav-pills .nav-link {
            color: #7f8c8d;
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 20px;
            transition: all 0.3s;
            background: white;
            margin-right: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .nav-pills .nav-link.active {
            background-color: var(--teal-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
        }

        /* === BARU: FILTER KATEGORI (Chips) === */
        .filter-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 15px;
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .filter-scroll::-webkit-scrollbar {
            display: none;
        }

        .btn-filter {
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            border-radius: 50px;
            padding: 6px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            transition: 0.2s;
            cursor: pointer;
        }

        .btn-filter:hover {
            background-color: #f1f2f6;
        }

        .btn-filter.active {
            background-color: var(--teal-dark);
            color: white;
            border-color: var(--teal-dark);
            box-shadow: 0 4px 10px rgba(22, 160, 133, 0.3);
        }

        /* Kartu Produk */
        .product-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .img-container {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .price-tag {
            color: var(--teal-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .stock-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 8px;
        }

        .fab-add {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--teal-primary) 0%, var(--teal-dark) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.4);
            z-index: 1050;
            font-size: 1.5rem;
            transition: transform 0.2s;
            text-decoration: none;
        }

        .fab-add:hover {
            color: white;
            transform: scale(1.1);
        }

        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            color: #95a5a6;
            transition: 0.2s;
            border: none;
        }

        .btn-action:hover {
            background: #e0f2f1;
            color: var(--teal-dark);
        }

        @media (max-width: 768px) {
            .fab-add {
                bottom: 100px;
                right: 20px;
                width: 55px;
                height: 55px;
            }
        }
    </style>

    <div class="container-fluid px-4">

        {{-- 1. STICKY HEADER --}}
        <div class="header-sticky">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold text-dark mb-0">Daftar Produk</h4>
                    <small class="text-muted">Kelola menu restoran anda</small>
                </div>
            </div>

            {{-- Tabs Utama (Aktif / Arsip) --}}
            <ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-aktif-tab" data-bs-toggle="pill" href="#pills-aktif"
                        role="tab">
                        <i class="fas fa-box-open me-1"></i> Aktif ({{ count($produks_aktif) }})
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-arsip-tab" data-bs-toggle="pill" href="#pills-arsip" role="tab">
                        <i class="fas fa-archive me-1"></i> Arsip
                    </a>
                </li>
            </ul>
        </div>

        {{-- 2. KONTEN --}}
        <div class="tab-content pb-5" id="pills-tabContent">

            {{-- TAB AKTIF --}}
            <div class="tab-pane fade show active" id="pills-aktif" role="tabpanel">

                {{-- BARU: Filter Kategori --}}
                <div class="filter-scroll">
                    <button class="btn-filter active" onclick="filterProduk('Semua', this)">Semua</button>
                    <button class="btn-filter" onclick="filterProduk('Ayam', this)">Ayam</button>
                    <button class="btn-filter" onclick="filterProduk('Paket', this)">Paket</button>
                    <button class="btn-filter" onclick="filterProduk('Topping', this)">Topping</button>
                    <button class="btn-filter" onclick="filterProduk('Minuman', this)">Minuman</button>
                </div>

                <div class="row g-3" id="produk-container">
                    @foreach ($produks_aktif as $produk)
                        {{-- Tambahkan data-kategori agar bisa difilter --}}
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 item-produk" data-kategori="{{ $produk->kategori }}">
                            <div class="product-card p-3 d-flex align-items-center h-100">

                                {{-- GAMBAR --}}
                                <div class="img-container me-3">
                                    @if ($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                                            loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        @php
                                            $defaultIcons = [
                                                'Ayam' => 'https://cdn-icons-png.flaticon.com/128/10573/10573607.png',
                                                'Paket' => 'https://cdn-icons-png.flaticon.com/128/685/685352.png',
                                                'Minuman' => 'https://cdn-icons-png.flaticon.com/128/2405/2405451.png',
                                                'Topping' => 'https://cdn-icons-png.flaticon.com/128/4436/4436034.png',
                                            ];
                                            $fallback = 'https://cdn-icons-png.flaticon.com/128/2771/2771401.png';
                                        @endphp
                                        <img src="{{ $defaultIcons[$produk->kategori] ?? $fallback }}" alt="icon"
                                            style="width: 35px; height: 35px; object-fit: contain;">
                                    @endif
                                </div>

                                {{-- INFO --}}
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-1 fw-bold text-dark text-truncate">{{ $produk->nama }}</h6>
                                    <div class="d-flex align-items-center mb-1">
                                        <span
                                            class="badge bg-light text-secondary border me-2">{{ $produk->kategori }}</span>
                                        <span
                                            class="badge {{ $produk->stok <= 5 ? 'bg-danger' : 'bg-success' }} stock-badge">
                                            Stok: {{ $produk->stok }}
                                        </span>
                                    </div>
                                    <div class="price-tag">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                </div>

                                {{-- DROPDOWN --}}
                                <div class="ms-2">
                                    <div class="dropdown dropstart">
                                        <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2">
                                            <li>
                                                <a class="dropdown-item rounded-3 py-2 fw-bold text-secondary"
                                                    href="{{ route('produk.edit', $produk->id) }}">
                                                    <i class="fas fa-edit me-2 text-warning"></i> Edit Menu
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus/mengarsipkan menu ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item rounded-3 py-2 fw-bold text-danger">
                                                        <i class="fas fa-trash me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Empty State (Muncul jika produk kosong) --}}
                @if (count($produks_aktif) == 0)
                    <div class="text-center py-5">
                        <div class="bg-white rounded-circle d-inline-flex p-4 shadow-sm mb-3">
                            <i class="fas fa-box-open fa-3x text-secondary opacity-50"></i>
                        </div>
                        <p class="text-muted fw-bold">Belum ada menu yang aktif.</p>
                    </div>
                @endif
            </div>

            {{-- TAB ARSIP --}}
            <div class="tab-pane fade" id="pills-arsip" role="tabpanel">
                <div class="row g-3">
                    @foreach ($produks_nonaktif as $produk)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="product-card p-3 d-flex align-items-center h-100 opacity-75 bg-light">
                                <div class="img-container me-3" style="filter: grayscale(100%);">
                                    @if ($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-box text-muted fa-lg"></i>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted text-decoration-line-through">{{ $produk->nama }}</h6>
                                    <small class="text-danger fw-bold">Non-Aktif</small>
                                </div>

                                <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="restore" value="1">
                                    <input type="hidden" name="nama" value="{{ $produk->nama }}">
                                    <input type="hidden" name="kategori" value="{{ $produk->kategori }}">
                                    <input type="hidden" name="harga" value="{{ $produk->harga }}">
                                    <input type="hidden" name="stok" value="{{ $produk->stok }}">

                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill fw-bold">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Floating Add --}}
        <a href="{{ route('produk.create') }}" class="fab-add" title="Tambah Menu Baru">
            <i class="fas fa-plus"></i>
        </a>

    </div>

    {{-- SCRIPT FILTER --}}
    <script>
        function filterProduk(kategori, btn) {
            // 1. Ubah tampilan tombol aktif
            document.querySelectorAll('.btn-filter').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            // 2. Filter item
            let items = document.querySelectorAll('.item-produk');

            items.forEach(item => {
                if (kategori === 'Semua' || item.getAttribute('data-kategori') === kategori) {
                    item.style.display = ''; // Tampilkan (reset display)
                } else {
                    item.style.display = 'none'; // Sembunyikan
                }
            });
        }
    </script>
@endsection
