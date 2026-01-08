<div class="card bg-light border-0 p-3 rounded-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <label class="fw-bold text-dark small mb-0">STATUS PRODUK</label>
            <small class="text-muted d-block" style="font-size: 0.7rem;">Matikan jika tidak ingin dijual</small>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active_dummy"
                {{ $produk->is_active ? 'checked' : '' }} disabled>
            <label class="form-check-label fw-bold {{ $produk->is_active ? 'text-success' : 'text-danger' }}">
                {{ $produk->is_active ? 'AKTIF' : 'NON-AKTIF' }}
            </label>
        </div>
    </div>
    @if (!$produk->is_active)
        <div class="mt-2 pt-2 border-top">
            <small class="text-muted">Ingin menjual kembali?</small><br>
            <button type="submit" name="restore" value="1" class="btn btn-sm btn-success rounded-pill px-3 mt-1">
                <i class="fas fa-check me-1"></i> Aktifkan Sekarang
            </button>
        </div>
    @endif
</div>
