@extends('layouts.app')

@section('content')
    <style>
        /* === TEAL THEME === */
        body {
            background-color: #e3f2fd;
        }

        /* Container Tengah agar rapi di Desktop */
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Kartu Form */
        .custom-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: white;
            margin-bottom: 20px;
            overflow: hidden;
        }

        /* Input Fields */
        .form-control-lg {
            font-size: 1rem;
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8f9fa;
        }

        .form-control-lg:focus {
            background-color: #fff;
            border-color: #1abc9c;
            box-shadow: 0 0 0 4px rgba(26, 188, 156, 0.1);
        }

        /* Kategori Chips (Radio Button) */
        .radio-chip {
            display: none;
        }

        .radio-label {
            display: inline-block;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
            margin-right: 5px;
            margin-bottom: 8px;
        }

        .radio-chip:checked+.radio-label {
            background-color: #1abc9c;
            border-color: #1abc9c;
            color: white;
            box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
            transform: translateY(-2px);
        }

        /* Area Upload Gambar */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: 0.3s;
            position: relative;
        }

        .upload-area:hover {
            background-color: #e0f2f1;
            border-color: #1abc9c;
        }

        .img-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            display: none;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tombol Simpan Gradient */
        .btn-save {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(26, 188, 156, 0.3);
            transition: 0.3s;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.4);
        }
    </style>

    <div class="container-fluid px-0">

        {{-- HEADER STICKY --}}
        <div class="sticky-top px-3 pt-3 pb-2 shadow-sm d-flex align-items-center"
            style="background: #e3f2fd; backdrop-filter: blur(10px); z-index: 1020;">
            <a href="{{ route('produk.index') }}" class="btn btn-white rounded-circle shadow-sm me-3 text-secondary"
                style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; background: white;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-bold text-dark mb-0">Menu Baru</h5>
                <small class="text-muted">Tambah menu jualan anda</small>
            </div>
        </div>

        {{-- FORM AREA --}}
        <div class="container px-3 py-4 pb-5 mb-5 form-container">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. Info Produk --}}
                <div class="custom-card p-4">
                    <h6 class="fw-bold text-teal mb-3" style="color: #16a085;">
                        <i class="fas fa-tag me-2"></i>Informasi Dasar
                    </h6>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NAMA MENU</label>
                        <input type="text" name="nama" class="form-control form-control-lg fw-bold text-dark"
                            placeholder="Contoh: Ayam Bakar Madu" required>
                    </div>

                    <div class="mb-0">
                        <label class="small fw-bold text-muted mb-2">KATEGORI</label>
                        <div class="d-flex flex-wrap">
                            @foreach (['Ayam', 'Paket', 'Topping', 'Minuman'] as $index => $kat)
                                <input type="radio" class="radio-chip" name="kategori" id="cat_{{ $index }}"
                                    value="{{ $kat }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="radio-label" for="cat_{{ $index }}">{{ $kat }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 2. Harga & Stok --}}
                <div class="custom-card p-4">
                    <h6 class="fw-bold text-teal mb-3" style="color: #16a085;">
                        <i class="fas fa-coins me-2"></i>Penjualan
                    </h6>
                    <div class="row g-3">
                        <div class="col-7">
                            <label class="small fw-bold text-muted mb-1">HARGA (RP)</label>
                            <input type="number" inputmode="numeric" name="harga"
                                class="form-control form-control-lg fw-bold" placeholder="0" required>
                        </div>
                        <div class="col-5">
                            <label class="small fw-bold text-muted mb-1">STOK AWAL</label>
                            <input type="number" inputmode="numeric" name="stok"
                                class="form-control form-control-lg fw-bold text-center" value="100" required>
                        </div>
                    </div>
                </div>

                {{-- 3. Upload Gambar --}}
                <div class="custom-card p-4">
                    <h6 class="fw-bold text-teal mb-3" style="color: #16a085;">
                        <i class="fas fa-camera me-2"></i>Foto Menu
                    </h6>

                    <img id="preview" class="img-preview" src="#" alt="Preview">

                    <div class="upload-area" onclick="document.getElementById('gambar').click()">
                        <input type="file" id="gambar" name="gambar" accept="image/*" hidden
                            onchange="previewImage(event)">
                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted fw-bold small" id="uploadLabel">Ketuk untuk upload foto</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Max 2MB (JPG/PNG)</small>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <button type="submit" class="btn btn-save w-100 mt-2 mb-5">
                    <i class="fas fa-check-circle me-2"></i> SIMPAN MENU
                </button>

            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('uploadLabel').innerText = event.target.files[0].name;
            }
        }
    </script>
@endsection
