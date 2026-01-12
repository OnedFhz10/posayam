@extends('layouts.app')

@section('content')
    <style>
        /* Tombol Tambah Melayang */
        .fab-add {
            position: fixed;
            bottom: 100px;
            /* Di atas navbar bawah */
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
            z-index: 1050;
            font-size: 1.5rem;
            transition: transform 0.2s;
        }

        .fab-add:active {
            transform: scale(0.95);
        }

        .search-sticky {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: #f8f9fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>

    <div class="container-fluid px-3">

        {{-- Search & Tabs --}}
        <div class="search-sticky">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 small mb-2">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <h5 class="fw-bold mb-3">Kelola Menu</h5>

            <ul class="nav nav-pills nav-fill bg-white p-1 rounded-pill shadow-sm mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active rounded-pill fw-bold small" id="pills-aktif-tab" data-toggle="pill"
                        href="#pills-aktif" role="tab">
                        Aktif ({{ count($produks_aktif) }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-bold small" id="pills-arsip-tab" data-toggle="pill"
                        href="#pills-arsip" role="tab">
                        Arsip
                    </a>
                </li>
            </ul>
        </div>

        {{-- Content --}}
        <div class="tab-content pb-5" id="pills-tabContent">

            {{-- TAB AKTIF --}}
            <div class="tab-pane fade show active" id="pills-aktif" role="tabpanel">
                <div class="row g-2">
                    @foreach ($produks_aktif as $produk)
                        <div class="col-12 mb-2">
                            <div class="card shadow-sm rounded-4 border-0">
                                <div class="card-body p-3 d-flex align-items-center">
                                    {{-- Icon Kategori --}}
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        @if ($produk->kategori == 'Minuman')
                                            <i class="fas fa-coffee text-warning"></i>
                                        @elseif($produk->kategori == 'Paket')
                                            <i class="fas fa-box text-primary"></i>
                                        @else
                                            <i class="fas fa-drumstick-bite text-danger"></i>
                                        @endif
                                    </div>

                                    {{-- Info Produk --}}
                                    <div class="flex-grow-1 ml-3">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $produk->nama }}</h6>
                                        <div class="text-muted small">Stok: <span
                                                class="fw-bold {{ $produk->stok <= 5 ? 'text-danger' : 'text-success' }}">{{ $produk->stok }}</span>
                                        </div>
                                        <div class="text-primary fw-bold">Rp
                                            {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                    </div>

                                    {{-- Action Button --}}
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button"
                                            data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right border-0 shadow rounded-4">
                                            <a class="dropdown-item py-2" href="{{ route('produk.edit', $produk->id) }}">
                                                <i class="fas fa-edit text-primary w-25"></i> Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus/Arsipkan menu ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i class="fas fa-trash w-25"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($produks_aktif) == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted opacity-25 mb-2"></i>
                        <p class="text-muted small">Belum ada menu aktif.</p>
                    </div>
                @endif
            </div>

            {{-- TAB ARSIP --}}
            <div class="tab-pane fade" id="pills-arsip" role="tabpanel">
                <div class="row g-2">
                    @foreach ($produks_nonaktif as $produk)
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm bg-light opacity-75">
                                <div class="card-body p-3 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-muted text-decoration-line-through">{{ $produk->nama }}</h6>
                                        <small class="text-muted">Non-Aktif</small>
                                    </div>
                                    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="restore" value="1">
                                        {{-- Kirim data dummy utk validasi --}}
                                        <input type="hidden" name="nama" value="{{ $produk->nama }}">
                                        <input type="hidden" name="kategori" value="{{ $produk->kategori }}">
                                        <input type="hidden" name="harga" value="{{ $produk->harga }}">
                                        <input type="hidden" name="stok" value="{{ $produk->stok }}">

                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="fas fa-undo me-1"></i> Restore
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Floating Add Button --}}
        <a href="{{ route('produk.create') }}" class="fab-add">
            <i class="fas fa-plus"></i>
        </a>
    </div>
@endsection
