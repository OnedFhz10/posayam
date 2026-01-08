<div class="col-12 menu-item">
    <div
        class="card border-0 shadow-sm rounded-4 p-2 h-100 {{ $type == 'archive' ? 'bg-secondary bg-opacity-10' : 'bg-white' }}">
        <div class="d-flex align-items-center">

            @php
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
                if ($type == 'archive') {
                    $bgClass = 'bg-white text-secondary';
                }
            @endphp

            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0 {{ $bgClass }}"
                style="width: 50px; height: 50px; font-size: 1.5rem;">
                {{ $icon }}
            </div>

            <div class="flex-grow-1">
                <h6 class="fw-bold mb-0 text-dark menu-name text-truncate">{{ $produk->nama }}</h6>
                <div class="d-flex align-items-center mt-1">
                    <span class="badge {{ $type == 'archive' ? 'bg-secondary' : 'bg-light text-dark border' }} me-2">
                        {{ $produk->kategori }}
                    </span>
                    <small class="fw-bold {{ $type == 'archive' ? 'text-muted' : 'text-primary' }}">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </small>
                </div>
            </div>

            <div class="text-end ps-2">
                @if ($type == 'active')
                    <div class="mb-2">
                        @if ($produk->stok <= 5)
                            <span class="badge bg-danger rounded-pill px-2">Sisa {{ $produk->stok }}</span>
                        @else
                            <small class="text-muted fw-bold">{{ $produk->stok }} Stok</small>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('produk.edit', $produk->id) }}"
                            class="btn btn-sm btn-light text-primary rounded-circle shadow-sm"
                            style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;"><i
                                class="fas fa-pen small"></i></a>
                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                            onsubmit="return confirm('Hapus/Arsipkan menu ini?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                                style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;"><i
                                    class="fas fa-trash small"></i></button>
                        </form>
                    </div>
                @else
                    <div class="mb-2"><small class="text-muted fst-italic">Non-Aktif</small></div>

                    <a href="{{ route('produk.edit', $produk->id) }}"
                        class="btn btn-sm btn-white border text-success shadow-sm rounded-pill px-3 py-1 fw-bold"
                        style="font-size: 0.7rem;">
                        <i class="fas fa-undo me-1"></i> Aktifkan
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
