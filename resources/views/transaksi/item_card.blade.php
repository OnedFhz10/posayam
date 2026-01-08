<div class="col-6 col-md-4 col-lg-3">
    @if ($produk->stok > 0)
        <a href="{{ route('transaksi.add', $produk->id) }}" class="text-decoration-none item-link">
        @else
            <div class="cursor-not-allowed">
    @endif

    @php
        // Logic Warna & Icon
        $bgClass = match ($produk->kategori) {
            'Paket' => 'bg-soft-red',
            'Ayam' => 'bg-soft-orange',
            'Minuman' => 'bg-soft-blue',
            'Topping' => 'bg-soft-green',
            default => 'bg-soft-gray',
        };
        $icon = match ($produk->kategori) {
            'Minuman' => '🥤',
            'Topping' => '🧀',
            'Paket' => '🍱',
            'Ayam' => '🍗',
            default => '🍴',
        };
    @endphp

    <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden {{ $produk->stok <= 0 ? 'grayscale' : 'bg-white' }}"
        style="transition: transform 0.1s;">
        <div class="card-body p-3 text-center d-flex flex-column align-items-center">

            <div class="rounded-circle mb-2 d-flex align-items-center justify-content-center {{ $bgClass }}"
                style="width: 50px; height: 50px; font-size: 1.5rem;">
                {{ $icon }}
            </div>

            <div class="w-100">
                <h6 class="card-title fw-bold text-dark mb-1 small text-truncate lh-sm">{{ $produk->nama }}</h6>
                <p class="mb-0 fw-bold text-primary small">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </p>

                @if ($produk->stok <= 5 && $produk->stok > 0)
                    <small class="text-danger fw-bold" style="font-size: 0.65rem;">Sisa {{ $produk->stok }}!</small>
                @endif
            </div>

            @if ($produk->stok <= 0)
                <div
                    class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center">
                    <span class="badge bg-dark rounded-pill px-3 py-2">HABIS</span>
                </div>
            @endif

        </div>

        @if ($produk->stok > 0)
            <div class="card-footer bg-transparent border-0 p-0 pb-2">
                <small class="text-muted" style="font-size: 0.7rem;">Tambah +</small>
            </div>
        @endif
    </div>

    @if ($produk->stok > 0)
        </a>
    @else
</div>
@endif
</div>

<style>
    .grayscale {
        filter: grayscale(100%);
        opacity: 0.7;
    }

    .item-link:active .card {
        transform: scale(0.95);
        background-color: #f8f9fa;
    }
</style>
