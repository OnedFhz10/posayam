@extends('layouts.app')

@section('content')
    <style>
        /* Agar Search & Tab lengket di atas */
        .sticky-pos-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #f4f6f8;
            /* Samakan warna body */
            padding-top: 10px;
            padding-bottom: 5px;
        }

        /* Tombol Bayar Melayang (Di atas Bottom Nav) */
        .float-cart-bar {
            position: fixed;
            bottom: 85px;
            /* Sesuai tinggi navbar bawah */
            left: 15px;
            right: 15px;
            background: #2d3436;
            color: white;
            padding: 15px 20px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            z-index: 1030;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .float-cart-bar:active {
            transform: scale(0.98);
        }
    </style>

    <div class="px-1" style="padding-bottom: 150px;">
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 small py-2 mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 small py-2 mb-2">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="sticky-pos-header">
            <div class="input-group shadow-sm mb-3">
                <span class="input-group-text bg-white border-0 ps-3 rounded-start-4"><i
                        class="fas fa-search text-muted"></i></span>
                <input type="text" id="posSearch" class="form-control border-0 py-3 rounded-end-4"
                    placeholder="Cari menu (cth: Geprek)..." onkeyup="filterPos()">
            </div>

            <div class="overflow-auto pb-2" style="scrollbar-width: none;">
                <ul class="nav nav-pills flex-nowrap" id="pills-tab" role="tablist" style="gap: 10px;">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill border px-4 fw-bold" id="tab-semua"
                            data-bs-toggle="pill" data-bs-target="#pills-semua" onclick="resetSearch()">
                            🍽️ Semua
                        </button>
                    </li>
                    <li class="nav-item"><button class="nav-link rounded-pill border px-4 fw-bold" data-bs-toggle="pill"
                            data-bs-target="#pills-paket">🍱 Paket</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill border px-4 fw-bold" data-bs-toggle="pill"
                            data-bs-target="#pills-ayam">🍗 Ayam</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill border px-4 fw-bold" data-bs-toggle="pill"
                            data-bs-target="#pills-topping">🧀 Topping</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill border px-4 fw-bold" data-bs-toggle="pill"
                            data-bs-target="#pills-minuman">🥤 Minum</button></li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-semua">
                <div class="row g-2">
                    @foreach ($produks as $produk)
                        @include('transaksi.item_card', ['produk' => $produk])
                    @endforeach
                </div>
                <div id="empty-state" class="text-center py-5 d-none">
                    <i class="fas fa-search fa-3x text-muted opacity-25 mb-2"></i>
                    <p class="text-muted small">Menu tidak ditemukan.</p>
                </div>
            </div>

            @foreach (['Paket', 'Ayam', 'Topping', 'Minuman'] as $kategori)
                <div class="tab-pane fade" id="pills-{{ strtolower($kategori) }}">
                    <div class="row g-2">
                        @foreach ($produks->where('kategori', $kategori) as $produk)
                            @include('transaksi.item_card', ['produk' => $produk])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    @if (count($cart) > 0)
        <div class="float-cart-bar d-flex justify-content-between align-items-center" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasCart">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-dark rounded-circle fw-bold d-flex align-items-center justify-content-center me-3"
                    style="width: 40px; height: 40px; font-size: 1.1rem;">
                    {{ $total_item }}
                </div>
                <div class="d-flex flex-column">
                    <small class="opacity-75" style="font-size: 0.7rem;">TOTAL ESTIMASI</small>
                    <span class="fw-bold fs-5">Rp {{ number_format($total_belanja, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center fw-bold text-warning">
                Lihat Pesanan <i class="fas fa-chevron-up ms-2"></i>
            </div>
        </div>
    @endif

    @include('transaksi.modal_cart')

    <script>
        function filterPos() {
            let input = document.getElementById('posSearch').value.toLowerCase();

            // Paksa pindah ke tab "Semua" saat mencari agar hasil pencarian global
            if (input.length > 0) {
                let tabSemua = new bootstrap.Tab(document.querySelector('#tab-semua'));
                tabSemua.show();
            }

            let items = document.querySelectorAll('#pills-semua .col-6'); // Ambil item di tab semua
            let visibleCount = 0;

            items.forEach(function(item) {
                let name = item.querySelector('.card-title').innerText.toLowerCase();
                if (name.includes(input)) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Tampilkan pesan kosong
            let emptyState = document.getElementById('empty-state');
            if (visibleCount === 0) emptyState.classList.remove('d-none');
            else emptyState.classList.add('d-none');
        }

        function resetSearch() {
            document.getElementById('posSearch').value = '';
            filterPos(); // Reset filter
        }
    </script>
@endsection
