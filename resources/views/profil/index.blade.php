@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Header Profil dengan Gradasi Teal (Sesuai Tema Aplikasi) */
        .profile-header {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            padding-bottom: 70px;
            /* Ruang lebih untuk kartu yang menumpuk */
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            color: white;
            text-align: center;
            padding-top: 40px;
            position: relative;
        }

        /* Tombol Kembali di Pojok Kiri Atas */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: 0.3s;
        }

        .btn-back:hover {
            opacity: 1;
            transform: translateX(-3px);
            color: white;
        }

        /* Avatar Inisial Bulat */
        .avatar-circle {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 auto 15px auto;
            color: white;
            backdrop-filter: blur(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Kartu Pengaturan (Overlap ke atas header) */
        .settings-card {
            margin-top: -50px;
            /* Efek overlap */
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: white;
            margin-bottom: 20px;
            overflow: hidden;
        }

        /* Style Input Form yang Bersih */
        .form-control-lg {
            background-color: #f8f9fa;
            border: 1px solid #eee;
            font-size: 0.95rem;
            padding: 15px 20px;
            border-radius: 12px;
        }

        .form-control-lg:focus {
            background-color: #fff;
            border-color: #1abc9c;
            box-shadow: 0 0 0 4px rgba(26, 188, 156, 0.1);
        }

        /* Badge Role */
        .badge-role {
            background: rgba(255, 255, 255, 0.25);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
    </style>

    <div class="container-fluid px-0 mb-5 pb-5">

        {{-- 1. HEADER PROFIL --}}
        <div class="profile-header">
            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="avatar-circle">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>

            <div class="mb-2">
                <span class="badge-role">
                    <i class="fas fa-user-tag"></i>
                    {{ ucfirst($user->role ?? 'User') }}
                </span>
                @if ($user->cabang)
                    <span class="badge-role ms-1">
                        <i class="fas fa-store"></i>
                        {{ $user->cabang->nama }}
                    </span>
                @else
                    <span class="badge-role ms-1">
                        <i class="fas fa-building"></i>
                        Pusat / Semua Cabang
                    </span>
                @endif
            </div>

            <p class="small opacity-75 mb-0">{{ $user->email }}</p>
        </div>

        <div class="container px-3">

            {{-- Alert Sukses Update --}}
            @if (session('success'))
                <div
                    class="alert alert-success border-0 shadow-sm rounded-4 text-center small mt-3 mb-2 animate__animated animate__fadeInDown">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 small mt-3 mb-2">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- 2. KARTU EDIT DATA DIRI --}}
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-user-edit text-primary me-2"></i>Edit Informasi
                        </h6>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted ms-1 mb-1">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-0">
                            <label class="small fw-bold text-muted ms-1 mb-1">ALAMAT EMAIL</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                {{-- 3. KARTU GANTI PASSWORD --}}
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-lock text-warning me-2"></i>Keamanan
                        </h6>

                        <div class="alert alert-light border-0 rounded-3 small text-muted mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 text-info"></i>
                            <span>Kosongkan jika tidak ingin mengganti password.</span>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted ms-1 mb-1">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                placeholder="Minimal 6 karakter">
                        </div>

                        <div class="mb-0">
                            <label class="small fw-bold text-muted ms-1 mb-1">KONFIRMASI PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow mb-4"
                    style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); border: none; font-size: 1rem;">
                    <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                </button>
            </form>

            {{-- 4. TOMBOL LOGOUT --}}
            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-3 fw-bold border-0"
                            style="background-color: #fff5f5;">
                            <i class="fas fa-sign-out-alt me-2"></i> KELUAR APLIKASI
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
