@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Header Profil dengan Gradasi */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding-bottom: 60px;
            /* Ruang untuk kartu yang menumpuk */
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            color: white;
            text-align: center;
            padding-top: 40px;
            position: relative;
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
            margin: 0 auto 10px auto;
            color: white;
            backdrop-filter: blur(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Kartu Pengaturan (Overlap ke atas header) */
        .settings-card {
            margin-top: -45px;
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
            border-color: #764ba2;
            box-shadow: 0 0 0 4px rgba(118, 75, 162, 0.1);
        }
    </style>

    <div class="container-fluid px-0 mb-5 pb-5">

        {{-- 1. HEADER PROFIL --}}
        <div class="profile-header">
            <div class="avatar-circle">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h4 class="font-weight-bold mb-0">{{ $user->name }}</h4>
            <p class="mb-0 opacity-75 small">
                <span
                    class="badge bg-white text-primary px-3 py-1 rounded-pill mt-1">{{ ucfirst($user->role ?? 'User') }}</span>
            </p>
            <p class="small mt-1 opacity-75">{{ $user->email }}</p>
        </div>

        <div class="container px-3">

            {{-- Alert Sukses Update --}}
            @if (session('success'))
                <div
                    class="alert alert-success border-0 shadow-sm rounded-pill text-center small mt-3 mb-2 animate__animated animate__fadeInDown">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 small mt-3 mb-2">
                    <ul class="mb-0 pl-3">
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
                        <h6 class="font-weight-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-user-edit text-primary mr-2"></i>Edit Informasi
                        </h6>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted ml-1">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted ml-1">ALAMAT EMAIL</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                {{-- 3. KARTU GANTI PASSWORD --}}
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-lock text-warning mr-2"></i>Keamanan
                        </h6>

                        <div class="alert alert-light border-0 rounded small text-muted mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle mr-2 text-info"></i>
                            <span>Kosongkan jika tidak ingin mengganti password.</span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted ml-1">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                placeholder="Minimal 6 karakter">
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted ml-1">KONFIRMASI PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <button type="submit" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold shadow mb-4"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-size: 1rem;">
                    <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                </button>
            </form>

            {{-- 4. TOMBOL LOGOUT --}}
            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-danger btn-block rounded-pill py-3 font-weight-bold border-0"
                            style="background-color: #fff5f5;">
                            <i class="fas fa-sign-out-alt mr-2"></i> KELUAR APLIKASI
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
