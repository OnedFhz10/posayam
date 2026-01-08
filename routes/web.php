<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman utama langsung ke login aja
Route::get('/', function () {
    return redirect('/login');
});

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

// Route untuk User yang Sudah Login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('produk', \App\Http\Controllers\ProdukController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Produk (Otomatis membuat index, create, store, edit, update, destroy)
    Route::resource('produk', App\Http\Controllers\ProdukController::class);

    // --- ROUTE POS / KASIR ---
    Route::get('/transaksi', [App\Http\Controllers\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/add/{id}', [App\Http\Controllers\TransaksiController::class, 'addToCart'])->name('transaksi.add');
    Route::get('/transaksi/decrease/{id}', [App\Http\Controllers\TransaksiController::class, 'decreaseCart'])->name('transaksi.decrease');
    Route::get('/transaksi/delete/{id}', [App\Http\Controllers\TransaksiController::class, 'deleteCart'])->name('transaksi.delete');
    Route::post('/transaksi/bayar', [App\Http\Controllers\TransaksiController::class, 'store'])->name('transaksi.store');

    // --- LAPORAN ---
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');

    Route::get('/laporan/export-pdf', [App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.export_pdf');

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});