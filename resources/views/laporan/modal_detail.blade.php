<div class="modal fade receipt-modal" id="modalDetail{{ $trx->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Header Receipt --}}
            <div class="receipt-header">
                <div class="mb-2">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <h5 class="fw-bold mb-0">Detail Transaksi</h5>
                <small class="text-muted">{{ $trx->created_at->format('d F Y, H:i') }}</small>
            </div>

            {{-- Body Receipt --}}
            <div class="receipt-body">
                <div class="text-center mb-3">
                    <p class="mb-0 fw-bold">{{ config('app.name') }}</p>
                    <small class="text-muted">No: {{ $trx->nomor_transaksi }}</small>
                    <br>
                    <small class="text-muted">Kasir: {{ $trx->user->name ?? 'Admin' }}</small>
                </div>

                <div class="receipt-divider"></div>

                {{-- List Produk --}}
                @foreach ($trx->details as $detail)
                    <div class="receipt-row">
                        <span>{{ $detail->produk->nama ?? 'Produk Dihapus' }} <br>
                            <small class="text-muted">{{ $detail->jumlah }} x
                                {{ number_format($detail->harga_satuan) }}</small>
                        </span>
                        <span>{{ number_format($detail->subtotal) }}</span>
                    </div>
                @endforeach

                <div class="receipt-divider"></div>

                <div class="receipt-row receipt-total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-row text-muted">
                    <span>Bayar</span>
                    <span>{{ number_format($trx->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-row text-muted">
                    <span>Kembali</span>
                    <span>{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="modal-footer border-0 bg-light rounded-bottom-4 d-flex justify-content-between">

                {{-- KIRI: Tombol Hapus --}}
                <form action="{{ route('laporan.destroy', $trx->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data ini? Stok akan dikembalikan.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-3 btn-sm">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>

                {{-- KANAN: Tutup & Cetak --}}
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-3 text-muted btn-sm"
                        data-bs-dismiss="modal">Tutup</button>
                    {{-- Tombol Cetak (Hanya Hiasan / Bisa dikoneksikan ke printer) --}}
                    <button type="button" class="btn btn-primary rounded-pill px-3 btn-sm"
                        style="background: var(--teal-dark); border:none;" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
