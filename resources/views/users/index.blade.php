{{-- File: resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h3 class="mt-4 fw-bold text-primary"><i class="fas fa-users-cog me-2"></i>Manajemen Karyawan</h3>

        {{-- Tombol Tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted">Kelola akses login kasir dan admin cabang.</p>
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-2"></i>Tambah Karyawan
            </a>
        </div>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabel User --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Nama Lengkap</th>
                                <th class="py-3">Email Login</th>
                                <th class="py-3">Role</th>
                                <th class="py-3">Cabang</th>
                                <th class="py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role == 'admin')
                                            <span class="badge bg-primary rounded-pill px-3">Owner/Admin</span>
                                        @else
                                            <span class="badge bg-info text-dark rounded-pill px-3">Kasir</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->cabang)
                                            <i class="fas fa-store me-1 text-muted"></i> {{ $user->cabang->nama }}
                                        @else
                                            <span class="text-muted fst-italic">- Semua Cabang -</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akses karyawan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/128/7486/7486744.png" width="60"
                                            class="mb-3 opacity-50">
                                        <p class="mb-0">Belum ada karyawan lain yang didaftarkan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
