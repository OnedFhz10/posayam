<div class="d-flex flex-column" style="min-height: 100vh; background-color: #f4f6f9;">

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Layout Desktop: Cart Sticky di Kanan */
        @media (min-width: 768px) {
            .desktop-cart-container {
                position: sticky;
                top: 90px;
                height: calc(100vh - 100px);
                overflow-y: auto;
            }

            /* Sembunyikan Nav Bawah di Desktop */
            .mobile-bottom-nav {
                display: none !important;
            }
        }
    </style>

    {{-- ALERT (Floating) --}}
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

    {{-- HEADER --}}
    <div class="sticky-top bg-white shadow-sm pb-2 pt-3 px-3" style="z-index: 1020;">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-light border-0 ps-3 rounded-start-pill"><i
                            class="fas fa-search"></i></span>
                    <input type="text" wire:model.live="search"
                        class="form-control bg-light border-0 rounded-end-pill py-2" placeholder="Cari menu...">
                </div>
                <div class="d-flex gap-2 overflow-auto py-2 no-scrollbar">
                    @foreach (['Semua', 'Paket', 'Ayam', 'Topping', 'Minuman'] as $kat)
                        <button wire:click="gantiKategori('{{ $kat }}')"
                            class="btn btn-sm rounded-pill px-3 fw-bold flex-shrink-0 border {{ $kategoriPilihan == $kat ? 'btn-dark' : 'bg-white text-muted' }}">
                            {{ $kat }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-end">
                <h5 class="fw-bold text-dark m-0"><i class="fas fa-cash-register text-primary me-2"></i>Kasir</h5>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA (GRID) --}}
    <div class="container-fluid px-3 pt-3 pb-5 mb-5">
        <div class="row g-3">

            {{-- KOLOM KIRI: PRODUK --}}
            <div class="col-12 col-md-8">
                <div class="row g-2">
                    @forelse($produks as $produk)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                                wire:click="addToCart({{ $produk->id }})" style="cursor: pointer;">
                                <span
                                    class="position-absolute top-0 end-0 badge {{ $produk->stok > 0 ? 'bg-success' : 'bg-danger' }} m-2 rounded-pill">
                                    {{ $produk->stok }}
                                </span>
                                <div
                                    class="card-body p-3 text-center d-flex flex-column justify-content-center align-items-center">
                                    <div class="mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 50px; height: 50px;">
                                        @if ($produk->kategori == 'Minuman')
                                            <i class="fas fa-coffee text-warning"></i>
                                        @elseif($produk->kategori == 'Paket')
                                            <i class="fas fa-box text-primary"></i>
                                        @else
                                            <i class="fas fa-drumstick-bite text-danger"></i>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 small text-truncate w-100">{{ $produk->nama }}
                                    </h6>
                                    <p class="text-primary fw-bold mb-0 small">Rp
                                        {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">Menu tidak ditemukan.</div>
                    @endforelse
                </div>
            </div>

            {{-- KOLOM KANAN: KERANJANG DESKTOP (Hidden on Mobile) --}}
            <div class="col-md-4 d-none d-md-block">
                <div class="card border-0 shadow-sm rounded-4 desktop-cart-container bg-white">
                    <div class="card-header bg-white border-bottom pt-3">
                        <h6 class="fw-bold"><i class="fas fa-receipt me-2 text-primary"></i>Pesanan</h6>
                    </div>
                    <div class="card-body d-flex flex-column h-100">
                        <div class="flex-grow-1 overflow-auto pe-2" style="max-height: 400px;">
                            @if (count($cart) > 0)
                                @foreach ($cart as $id => $item)
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-dashed">
                                        <div>
                                            <h6 class="mb-0 fw-bold small">{{ $item['name'] }}</h6>
                                            <small class="text-muted">Rp
                                                {{ number_format($item['price'], 0, ',', '.') }}</small>
                                        </div>
                                        <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                                            <button wire:click="decreaseCart({{ $id }})"
                                                class="btn btn-sm btn-white rounded-circle text-danger shadow-sm py-0"
                                                style="width: 25px; height: 25px;">-</button>
                                            <span class="mx-2 fw-bold small">{{ $item['quantity'] }}</span>
                                            <button wire:click="addToCart({{ $id }})"
                                                class="btn btn-sm btn-primary rounded-circle text-white shadow-sm py-0"
                                                style="width: 25px; height: 25px;">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5 text-muted opacity-50">Keranjang Kosong</div>
                            @endif
                        </div>

                        {{-- Form Bayar Desktop --}}
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                            </div>

                            {{-- Input Uang --}}
                            <div class="input-group mb-2">
                                <span class="input-group-text border-0 bg-light rounded-start-pill ps-3">Rp</span>
                                {{-- PERBAIKAN INPUT: Type Number agar data pasti masuk --}}
                                <input type="number" wire:model.live.debounce.500ms="bayar"
                                    class="form-control border-0 bg-light rounded-end-pill fw-bold fs-5 text-end"
                                    placeholder="0">
                            </div>

                            {{-- Kembalian --}}
                            @if ($kembalian >= 0 && $bayar > 0)
                                <div
                                    class="alert alert-success border-0 rounded-3 d-flex justify-content-between fw-bold py-2 mb-3">
                                    <span>Kembali:</span>
                                    <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <button wire:click="store" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm"
                                {{ count($cart) == 0 ? 'disabled' : '' }}>
                                BAYAR SEKARANG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE CART (Offcanvas) - Hidden on Desktop --}}
    @if ($totalItem > 0)
        <div class="fixed-bottom p-3 d-md-none" style="z-index: 1030; padding-bottom: 90px !important;">
            <button
                class="btn btn-dark w-100 rounded-pill py-3 px-3 d-flex justify-content-between align-items-center shadow-lg"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3"
                        style="width: 30px; height: 30px;">{{ $totalItem }}</div>
                    <div class="text-start lh-1">
                        <small class="d-block text-white-50">Total</small>
                        <span class="fs-5 fw-bold text-white">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="fw-bold text-warning">Bayar <i class="fas fa-chevron-up"></i></div>
            </button>
        </div>
    @endif

    <div wire:ignore.self class="offcanvas offcanvas-bottom rounded-top-4 d-md-none" tabindex="-1" id="offcanvasCart"
        style="height: 85vh;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">Pesanan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body pb-5">
            {{-- Cart Items Mobile --}}
            <div class="mb-4">
                @foreach ($cart as $id => $item)
                    <div
                        class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-dashed">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $item['name'] }}</h6>
                            <small class="text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                            <button wire:click="decreaseCart({{ $id }})"
                                class="btn btn-sm btn-white rounded-circle text-danger shadow-sm py-0"
                                style="width: 28px; height: 28px;">-</button>
                            <span class="mx-3 fw-bold small">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCart({{ $id }})"
                                class="btn btn-sm btn-primary rounded-circle text-white shadow-sm py-0"
                                style="width: 28px; height: 28px;">+</button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form Bayar Mobile --}}
            <div class="card bg-light border-0 rounded-4 p-3 mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Total Tagihan</span>
                    <span class="fw-bold fs-5">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text border-0 bg-white rounded-start-pill ps-3">Rp</span>
                    <input type="number" wire:model.live.debounce.500ms="bayar"
                        class="form-control border-0 bg-white rounded-end-pill fw-bold fs-5 text-end" placeholder="0">
                </div>

                {{-- Quick Buttons --}}
                <div class="d-flex gap-2 justify-content-end mb-2">
                    <button wire:click="$set('bayar', {{ $totalBelanja }})"
                        class="btn btn-sm btn-outline-secondary rounded-pill">Pas</button>
                    <button wire:click="$set('bayar', 50000)"
                        class="btn btn-sm btn-outline-secondary rounded-pill">50k</button>
                    <button wire:click="$set('bayar', 100000)"
                        class="btn btn-sm btn-outline-secondary rounded-pill">100k</button>
                </div>

                @if ($kembalian >= 0 && $bayar > 0)
                    <div
                        class="alert alert-success border-0 rounded-3 d-flex justify-content-between fw-bold py-2 mb-0">
                        <span>Kembalian:</span>
                        <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <button wire:click="store" class="btn btn-primary w-100 rounded-pill py-3 fw-bold fs-5 shadow mb-5">
                PROSES BAYAR
            </button>
        </div>
    </div>

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
