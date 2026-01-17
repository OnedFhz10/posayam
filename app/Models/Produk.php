<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    // 1. Kita daftarkan semua kolom yang boleh diisi manual
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori',
        'gambar',     // <--- Wajib ada agar gambar bisa disimpan
        'is_active',  // Penting untuk menyembunyikan produk di kasir
    ];

    // 2. Casting data agar formatnya selalu benar saat diambil
    protected $casts = [
        'harga' => 'integer',      // Memastikan harga dianggap angka, bukan tulisan
        'stok' => 'integer',       // Memastikan stok dianggap angka
        'is_active' => 'boolean',  // Memastikan is_active dianggap true/false
    ];
}