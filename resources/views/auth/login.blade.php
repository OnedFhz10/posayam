@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
        <div class="w-100 px-3" style="max-width: 400px;">

            <div class="text-center mb-5">
                <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <span style="font-size: 2.5rem;">🍗</span>
                </div>
                <h3 class="fw-bold text-dark">POS Ayam Geprek</h3>
                <p class="text-muted small">Masuk untuk mulai berjualan</p>
            </div>

            <div class="card p-4">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small rounded-3">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('login.action') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">EMAIL</label>
                        <input type="email" name="email"
                            class="form-control form-control-lg bg-light border-0 rounded-4 fs-6"
                            placeholder="admin@ayam.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">PASSWORD</label>
                        <input type="password" name="password"
                            class="form-control form-control-lg bg-light border-0 rounded-4 fs-6" placeholder="••••••"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-lg">MASUK
                        APLIKASI</button>
                </form>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">Versi 1.0 Mobile</small>
            </div>
        </div>
    </div>
@endsection
