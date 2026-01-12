@extends('layouts.app')

@section('content')
    <style>
        /* Style yang sama dengan halaman Create */
        body {
            background-color: #f4f6f9;
        }

        .form-control-lg {
            font-size: 1rem;
            padding: 1.2rem 1rem;
        }

        /* Style Chips Kategori */
        .radio-chip {
            display: none;
        }

        .radio-label {
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .radio-chip:checked+.radio-label {
            border-color: #764ba2;
            background-color: #764ba2;
            color: white;
            box-shadow: 0 4px 10px rgba(118, 75, 162, 0.3);
            transform: translateY(-1px);
        }

        /* Mobile Card Style */
        .mobile-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 15px;
        }

        /* Custom Switch untuk Status */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-left: -2.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .form-switch .form-check-input:checked {
            background-color: #198754;
            /* Warna hijau sukses */
            border-color: #198754;
        }
    </style>

    <div class="container-fluid px-0">

        {{-- 1. HEADER (Sticky Top) --}}
        <div class="d-flex align-items-center px-3 pt-3 pb-2 bg-white shadow-sm sticky-top" style="z-index: 1020;">
            <a href="{{ route('produk.index') }}" class="btn btn-light rounded-circle text-muted mr-3"
                style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="font-weight-bold mb-0 text-dark">Edit Menu</h5>
                <small class="text-muted">Perbarui data menu penjualan</small>
            </div>
        </div>

        {{-- 2. FORM AREA --}}
        <div class="container px-3 py-4 pb-5 mb-5">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Kartu 1: Info Dasar --}}
                <div class="card mobile-card">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-tag mr-2"></i>Informasi Menu
                        </h6>

                        {{-- Input Nama --}}
                        <div class="form-group mb-4">
                            <label class="text-muted small font-weight-bold mb-2">NAMA MENU</label>
                            <input type="text" name="nama"
                                class="form-control form-control-lg border-0 bg-light rounded shadow-sm text-dark font-weight-bold"
                                value="{{ old('nama', $produk->nama) }}" required>
                        </div>

                        {{-- Pilihan Kategori --}}
                        <div class="form-group mb-0">
                            <label class="text-muted small font-weight-bold mb-2">KATEGORI</label>
                            <div class="d-flex flex-wrap">
                                @foreach (['Ayam', 'Paket', 'Topping', 'Minuman'] as $index => $kat)
                                    <input type="radio" class="radio-chip" name="kategori"
                                        id="cat_edit_{{ $index }}" value="{{ $kat }}"
                                        {{ $produk->kategori == $kat ? 'checked' : '' }}>
                                    <label class="radio-label"
                                        for="cat_edit_{{ $index }}">{{ $kat }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 2: Harga & Stok --}}
                <div class="card mobile-card">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-coins mr-2"></i>Penjualan
                        </h6>

                        <div class="row">
                            {{-- Harga --}}
                            <div class="col-7 pr-2">
                                <div class="form-group mb-0">
                                    <label class="text-muted small font-weight-bold mb-2">HARGA (RP)</label>
                                    <input type="number" inputmode="numeric" name="harga"
                                        class="form-control form-control-lg border-0 bg-light rounded shadow-sm font-weight-bold"
                                        value="{{ old('harga', $produk->harga) }}" required>
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="col-5 pl-2">
                                <div class="form-group mb-0">
                                    <label class="text-muted small font-weight-bold mb-2">STOK SAAT INI</label>
                                    <input type="number" inputmode="numeric" name="stok"
                                        class="form-control form-control-lg border-0 bg-light rounded shadow-sm text-center font-weight-bold"
                                        value="{{ old('stok', $produk->stok) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 3: Status Menu (Khusus Halaman Edit) --}}
                <div class="card mobile-card">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1">Status Menu</h6>
                            <small class="text-muted">Jika dimatikan, menu tidak muncul di kasir.</small>
                        </div>
                        <div class="form-check form-switch ml-3">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="is_active"
                                value="1" {{ $produk->is_active ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Tombol Update (Besar & Jelas) --}}
                <button type="submit"
                    class="btn btn-primary btn-lg btn-block rounded-pill font-weight-bold py-3 shadow mt-4 mb-5"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <i class="fas fa-save mr-2"></i> UPDATE MENU
                </button>

                {{-- Spacer --}}
                <div style="height: 50px;"></div>

            </form>
        </div>
    </div>
@endsection
