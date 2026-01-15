<div class="d-flex flex-column" style="min-height: 100vh; background-color: #e3f2fd; font-family: 'Poppins', sans-serif;">

    <style>
        /* === TEAL THEME VARIABLES === */
        :root {
            --teal-primary: #1abc9c;
            --teal-dark: #16a085;
            --bg-light: #e3f2fd;
        }

        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Header Sticky */
        .pos-header {
            background-color: var(--bg-light);
            backdrop-filter: blur(10px);
            padding-top: 15px;
            padding-bottom: 10px;
        }

        /* Search Input */
        .search-input {
            border: none;
            border-radius: 50px;
            background: white;
            padding: 12px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            color: #555;
        }

        .search-input:focus {
            box-shadow: 0 4px 20px rgba(26, 188, 156, 0.15);
            outline: 2px solid var(--teal-primary);
        }

        /* Filter Chips */
        .btn-filter {
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            transition: 0.2s;
            cursor: pointer;
        }

        .btn-filter:hover {
            background-color: #f1f2f6;
        }

        .btn-filter.active-filter {
            background-color: var(--teal-primary);
            color: white;
            border-color: var(--teal-primary);
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
        }

        /* Product Card */
        .pos-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
            cursor: pointer;
            overflow: hidden;
            height: 100%;
        }

        .pos-card:active {
            transform: scale(0.96);
        }

        .pos-img-container {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 10px;
        }

        /* Cart Container (Desktop) */
        .desktop-cart {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: calc(100vh - 100px);
            position: sticky;
            top: 90px;
            display: flex;
            flex-direction: column;
        }

        /* Buttons */
        .btn-qty {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-qty-minus {
            background: #fee2e2;
            color: #ef4444;
        }

        .btn-qty-plus {
            background: #ccfbf1;
            color: #0f766e;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--teal-primary) 0%, var(--teal-dark) 100%);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            padding: 12px 25px;
            box-shadow: 0 5px 15px rgba(26, 188, 156, 0.3);
            transition: 0.3s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.4);
        }

        .btn-checkout:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Tombol Uang Cepat */
        .btn-money {
            border: 1px solid #e2e8f0;
            background: white;
            color: #555;
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 8px 10px;
            transition: 0.2s;
            font-weight: 600;
            flex: 1;
        }

        .btn-money:hover {
            background: #e0f2f1;
            color: var(--teal-dark);
            border-color: var(--teal-dark);
        }

        .btn-money-pas {
            background: #e0f2f1;
            color: var(--teal-dark);
            border: 1px solid var(--teal-dark);
        }

        /* Mobile Bottom Bar (NEW) */
        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            z-index: 1030;
        }

        @media (max-width: 768px) {
            .mobile-bottom-spacer {
                height: 120px;
            }
        }
    </style>

    {{-- ALERT --}}
    <div class="fixed-top px-3 mt-3 w-100 d-flex justify-content-center" style="z-index: 2000; pointer-events: none;">
        <div class="col-12 col-md-6">
            @if (session()->has('error'))
                <div class="alert alert-danger shadow-lg rounded-4 small py-2 mb-2 text-center fw-bold"
                    style="pointer-events: auto;">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success shadow-lg rounded-4 small py-2 mb-2 text-center fw-bold"
                    style="pointer-events: auto;">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    {{-- HEADER KASIR --}}
    <div class="sticky-top pos-header px-3" style="z-index: 1020;">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0 bg-white rounded-start-pill ps-3"
                        style="box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" wire:model.live="search" class="form-control search-input rounded-end-pill"
                        placeholder="Cari menu...">
                </div>
                <div class="d-flex gap-2 overflow-auto py-1 no-scrollbar">
                    @foreach (['Semua', 'Paket', 'Ayam', 'Topping', 'Minuman'] as $kat)
                        <button wire:click="gantiKategori('{{ $kat }}')"
                            class="btn-filter {{ $kategoriPilihan == $kat ? 'active-filter' : '' }}">
                            {{ $kat }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-end">
                <h5 class="fw-bold mb-0" style="color: #16a085;"><i class="fas fa-cash-register me-2"></i>Kasir</h5>
            </div>
        </div>
    </div>

    {{-- GRID PRODUK & CART --}}
    <div class="container-fluid px-3 pt-3 mb-5">
        <div class="row g-3">

            {{-- KOLOM KIRI: DAFTAR PRODUK --}}
            <div class="col-12 col-md-8">
                <div class="row g-2">
                    @forelse($produks as $produk)
                        <div class="col-6 col-md-4 col-lg-3" wire:key="produk-{{ $produk->id }}">
                            <div class="pos-card p-3 d-flex flex-column align-items-center justify-content-center text-center position-relative"
                                wire:click="addToCart({{ $produk->id }})">
                                <span
                                    class="position-absolute top-0 end-0 badge {{ $produk->stok > 0 ? 'bg-success' : 'bg-danger' }} m-2 rounded-pill shadow-sm"
                                    style="z-index: 10;">
                                    {{ $produk->stok }}
                                </span>
                                <div class="pos-img-container shadow-sm">
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
                                            loading="lazy" style="width: 40px; height: 40px; object-fit: contain;">
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1 small text-truncate w-100">{{ $produk->nama }}</h6>
                                <p class="mb-0 small fw-bold" style="color: #1abc9c;">Rp
                                    {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-3x mb-3 text-secondary opacity-25"></i>
                            <p class="text-muted fw-bold">Menu tidak ditemukan.</p>
                        </div>
                    @endforelse
                </div>
                {{-- Spacer agar konten terakhir tidak tertutup bottom bar --}}
                <div class="mobile-bottom-spacer"></div>
            </div>

            {{-- KOLOM KANAN: KERANJANG DESKTOP --}}
            <div class="col-md-4 d-none d-md-block">
                <div class="desktop-cart">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-receipt me-2" style="color: #1abc9c;"></i>Pesanan</h6>
                        <span class="badge bg-light text-dark">{{ count($cart) }} Item</span>
                    </div>

                    <div class="flex-grow-1 overflow-auto p-3" style="max-height: 400px;">
                        @if (count($cart) > 0)
                            @foreach ($cart as $id => $item)
                                <div
                                    class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-dashed">
                                    <div class="text-truncate pe-2" style="max-width: 60%;">
                                        <h6 class="mb-0 fw-bold small text-truncate">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Rp
                                            {{ number_format($item['price'], 0, ',', '.') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                                        <button wire:click="decreaseCart({{ $id }})"
                                            class="btn-qty btn-qty-minus">-</button>
                                        <span class="mx-2 fw-bold small">{{ $item['quantity'] }}</span>
                                        <button wire:click="addToCart({{ $id }})"
                                            class="btn-qty btn-qty-plus">+</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted opacity-50">
                                <i class="fas fa-shopping-basket fa-2x mb-2"></i>
                                <p>Keranjang Kosong</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Bayar Desktop --}}
                    <div class="p-3 border-top bg-light rounded-bottom-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-muted">Total</span>
                            <span class="fw-bold fs-5 text-dark">Rp
                                {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                        </div>

                        <div class="input-group mb-2">
                            <span class="input-group-text border-0 bg-white ps-3">Rp</span>
                            <input type="number" wire:model.live.debounce.500ms="bayar"
                                class="form-control border-0 bg-white fw-bold text-end" placeholder="0">
                        </div>

                        <div class="d-flex gap-2 justify-content-end mb-3">
                            <button wire:click="setBayar('pas')" class="btn-money btn-money-pas">Uang Pas</button>
                            <button wire:click="setBayar(50000)" class="btn-money">50k</button>
                            <button wire:click="setBayar(100000)" class="btn-money">100k</button>
                        </div>

                        @if ($kembalian >= 0 && $bayar > 0)
                            <div
                                class="alert alert-success border-0 rounded-3 py-2 mb-3 d-flex justify-content-between fw-bold small">
                                <span>Kembali:</span>
                                <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button wire:click="store" class="btn-checkout w-100"
                            {{ count($cart) == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-print me-2"></i> BAYAR SEKARANG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE BOTTOM BAR (SIMPLE) --}}
    @if ($totalItem > 0)
        <div class="mobile-bottom-bar d-md-none">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block" style="font-size: 0.75rem;">Total Tagihan</span>
                    <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h4>
                    <small class="text-muted">{{ $totalItem }} Item</small>
                </div>

                {{-- Tombol Bayar Langsung --}}
                <button class="btn btn-checkout rounded-pill d-flex align-items-center gap-2"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                    <span>Bayar</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- MOBILE CART OFFCANVAS (FORM PEMBAYARAN) --}}
    <div wire:ignore.self class="offcanvas offcanvas-bottom rounded-top-4 d-md-none" tabindex="-1"
        id="offcanvasCart" style="height: 85vh;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">Rincian Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body pb-5 d-flex flex-column">

            {{-- List Item Mobile --}}
            <div class="flex-grow-1 overflow-auto mb-3">
                @foreach ($cart as $id => $item)
                    <div
                        class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-dashed">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $item['name'] }}</h6>
                            <small class="text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                            <button wire:click="decreaseCart({{ $id }})"
                                class="btn-qty btn-qty-minus">-</button>
                            <span class="mx-3 fw-bold small">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCart({{ $id }})"
                                class="btn-qty btn-qty-plus">+</button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form Input Uang Mobile --}}
            <div class="bg-light p-3 rounded-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold text-muted">Total</span>
                    <span class="fw-bold fs-5 text-dark">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>

                {{-- Input Manual --}}
                <div class="input-group mb-3">
                    <span class="input-group-text border-0 bg-white ps-3">Rp</span>
                    <input type="number" wire:model.live.debounce.500ms="bayar"
                        class="form-control border-0 bg-white fw-bold text-end" placeholder="0">
                </div>

                {{-- Tombol Cepat (Uang Pas, 50k, 100k) --}}
                <div class="d-flex gap-2 justify-content-between mb-3">
                    <button wire:click="setBayar('pas')" class="btn-money btn-money-pas text-center">Uang Pas</button>
                    <button wire:click="setBayar(50000)" class="btn-money text-center">50k</button>
                    <button wire:click="setBayar(100000)" class="btn-money text-center">100k</button>
                </div>

                @if ($kembalian >= 0 && $bayar > 0)
                    <div
                        class="alert alert-success border-0 rounded-3 d-flex justify-content-between fw-bold py-2 mb-3">
                        <span>Kembalian:</span>
                        <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif

                <button wire:click="store" class="btn-checkout w-100" {{ count($cart) == 0 ? 'disabled' : '' }}>
                    SELESAIKAN TRANSAKSI
                </button>
            </div>
        </div>
    </div>

    {{-- Script Tutup Offcanvas --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('transaksi-berhasil', (event) => {
                var el = document.getElementById('offcanvasCart');
                if (el) {
                    var bsOffcanvas = bootstrap.Offcanvas.getInstance(el);
                    if (bsOffcanvas) bsOffcanvas.hide();
                }
            });
        });
    </script>
</div>
