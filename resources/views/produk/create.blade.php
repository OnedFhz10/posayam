@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('produk.index') }}" class="btn btn-light rounded-circle shadow-sm me-3"
            style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h5 class="fw-bold mb-0">Tambah Menu Baru</h5>
    </div>

    <div class="card border-0 shadow-sm p-4 rounded-4">
        <form action="{{ route('produk.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">NAMA MENU</label>
                <input type="text" name="nama" class="form-control form-control-lg bg-light border-0 rounded-4 fs-6"
                    placeholder="Contoh: Ayam Bakar Madu" required>
            </div>

            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label small fw-bold text-muted">KATEGORI</label>
                    <select name="kategori" class="form-select form-select-lg bg-light border-0 rounded-4 fs-6">
                        <option value="Ayam">Ayam</option>
                        <option value="Paket">Paket</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Topping">Topping</option>
                    </select>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label small fw-bold text-muted">STOK AWAL</label>
                    <input type="number" name="stok"
                        class="form-control form-control-lg bg-light border-0 rounded-4 fs-6" value="10" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">HARGA JUAL (RP)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-4 fw-bold">Rp</span>
                    <input type="number" name="harga"
                        class="form-control form-control-lg bg-light border-0 rounded-end-4 fs-6 fw-bold" placeholder="0"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow">
                SIMPAN MENU
            </button>
        </form>
    </div>
@endsection
