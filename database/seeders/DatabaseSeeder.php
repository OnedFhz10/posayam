<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Produk;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Cabang
        $pusat = Cabang::create([
            'nama' => 'Ayam Geprek Pusat',
            'alamat' => 'Jl. Merdeka No. 1',
            'is_active' => true
        ]);
        
        $cabang1 = Cabang::create([
            'nama' => 'Ayam Geprek Cabang Dago',
            'alamat' => 'Jl. Dago No. 45',
            'is_active' => true
        ]);

        // 2. Buat Users (Role Baru)
        
        // ADMIN (Dulu Owner, sekarang Admin yg terikat ke Pusat)
        User::create([
            'name' => 'Juragan (Admin)',
            'email' => 'admin@ayam.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cabang_id' => $pusat->id, // Admin bertugas di Pusat
        ]);

        // KASIR (Hanya di cabang)
        User::create([
            'name' => 'Staff Kasir Dago',
            'email' => 'kasir@ayam.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'cabang_id' => $cabang1->id,
        ]);

       // 3. Buat Produk dengan Kategori Baru
        // Kategori: Ayam
        Produk::create(['nama' => 'Ayam Geprek Original', 'kategori' => 'Ayam', 'harga' => 12000, 'stok' => 50, 'is_active' => true]);
        Produk::create(['nama' => 'Ayam Bakar Madu', 'kategori' => 'Ayam', 'harga' => 15000, 'stok' => 40, 'is_active' => true]);
        
        // Kategori: Paket (Ayam + Nasi)
        Produk::create(['nama' => 'Paket Geprek Hemat', 'kategori' => 'Paket', 'harga' => 18000, 'stok' => 50, 'is_active' => true]);
        Produk::create(['nama' => 'Paket Jumbo (2 Ayam)', 'kategori' => 'Paket', 'harga' => 25000, 'stok' => 30, 'is_active' => true]);

        // Kategori: Topping
        Produk::create(['nama' => 'Keju Mozzarella', 'kategori' => 'Topping', 'harga' => 5000, 'stok' => 100, 'is_active' => true]);
        Produk::create(['nama' => 'Sambal Matah', 'kategori' => 'Topping', 'harga' => 3000, 'stok' => 100, 'is_active' => true]);
        Produk::create(['nama' => 'Extra Nasi', 'kategori' => 'Topping', 'harga' => 5000, 'stok' => 200, 'is_active' => true]);

        // Kategori: Minuman
        Produk::create(['nama' => 'Es Teh Manis', 'kategori' => 'Minuman', 'harga' => 4000, 'stok' => 100, 'is_active' => true]);
        Produk::create(['nama' => 'Es Jeruk', 'kategori' => 'Minuman', 'harga' => 6000, 'stok' => 100, 'is_active' => true]);
    }
}