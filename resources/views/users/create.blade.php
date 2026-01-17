{{-- File: resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container px-4 mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <a href="{{ route('users.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Karyawan
                </a>

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-primary text-white p-4 rounded-top-4">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Daftarkan Karyawan Baru</h5>
                        <small class="opacity-75">Buat akun login untuk kasir cabang.</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light fs-6"
                                    placeholder="Contoh: Budi Santoso" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">EMAIL LOGIN</label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light fs-6"
                                    placeholder="kasir.cabang1@posayam.com" required>
                                <div class="form-text">Email ini akan digunakan untuk login.</div>
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">PASSWORD AWAL</label>
                                <input type="text" name="password" class="form-control form-control-lg bg-light fs-6"
                                    placeholder="Min. 6 karakter" required>
                            </div>

                            <div class="row">
                                {{-- Role --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">JABATAN / ROLE</label>
                                    <select name="role" class="form-select form-select-lg fs-6">
                                        <option value="kasir">Kasir (Hanya POS)</option>
                                        <option value="admin">Admin / Manager</option>
                                    </select>
                                </div>

                                {{-- Cabang --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">PENEMPATAN CABANG</label>
                                    <select name="cabang_id" class="form-select form-select-lg fs-6" required>
                                        <option value="">-- Pilih Cabang --</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}">{{ $cabang->nama }} ({{ $cabang->lokasi }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="fas fa-save me-2"></i> SIMPAN DATA
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
