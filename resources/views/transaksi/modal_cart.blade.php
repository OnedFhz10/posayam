<div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="offcanvasCart" style="height: 90vh;">

    <div class="offcanvas-header bg-white border-bottom">
        <div>
            <h5 class="offcanvas-title fw-bold">🛒 Pesanan</h5>
            <small class="text-muted">Periksa kembali sebelum bayar</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0 bg-light">

        <div class="flex-grow-1 overflow-auto p-3">
            @if (session('cart'))
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <ul class="list-group list-group-flush">
                        @foreach (session('cart') as $id => $details)
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark">{{ $details['name'] }}</span>
                                    <span class="fw-bold text-primary">Rp
                                        {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">@ Rp
                                        {{ number_format($details['price'], 0, ',', '.') }}</small>

                                    <div class="bg-light rounded-pill border d-flex align-items-center p-1">
                                        <a href="{{ route('transaksi.decrease', $id) }}"
                                            class="btn btn-sm btn-white rounded-circle shadow-sm py-0 px-2 text-danger fw-bold">-</a>
                                        <span class="mx-3 fw-bold small">{{ $details['quantity'] }}</span>
                                        <a href="{{ route('transaksi.add', $id) }}"
                                            class="btn btn-sm btn-primary rounded-circle shadow-sm py-0 px-2 fw-bold">+</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-5 mt-5">
                    <i class="fas fa-shopping-basket fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Keranjang kosong</p>
                </div>
            @endif
        </div>

        <div class="p-3 bg-white border-top shadow-lg rounded-top-4">
            <form action="{{ route('transaksi.store') }}" method="POST" id="formBayar">
                @csrf

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold">TOTAL TAGIHAN</span>
                    <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($total_belanja, 0, ',', '.') }}</h3>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">UANG DITERIMA (CASH)</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-0 fw-bold">Rp</span>
                        <input type="number" name="bayar" class="form-control bg-light border-0 fw-bold"
                            placeholder="0" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm"
                    {{ $total_belanja == 0 ? 'disabled' : '' }}
                    onclick="return confirm('Proses pembayaran? Transaksi tidak bisa diubah setelah ini.')">
                    <i class="fas fa-print me-2"></i> PROSES & CETAK
                </button>
            </form>
        </div>
    </div>
</div>
