<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Livewire\PosPage; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

// Route User Login (Semua yang login bisa akses ini)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // PROFIL (Boleh Admin & Kasir)
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // --- KHUSUS KASIR (POS SYSTEM) ---
    // Admin DILARANG masuk sini agar fokus manajemen
    Route::middleware('role:kasir')->group(function () {
        Route::get('/transaksi', PosPage::class)->name('transaksi.index');
    });

    // --- LAPORAN (SEMUA BISA LIHAT) ---
    // Admin lihat semua, Kasir lihat punya sendiri (difilter di Controller)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export_pdf');
        
        // PENTING: Hanya Admin yang boleh HAPUS data!
        Route::delete('/{id}', [LaporanController::class, 'destroy'])
            ->middleware('role:admin') 
            ->name('destroy');
    });

    // --- KHUSUS ADMIN (Owner) ---
    Route::middleware('role:admin')->group(function () {
        // Kelola Produk
        Route::resource('produk', ProdukController::class);
        // Manajemen User
        Route::resource('users', UserController::class);
    });
});