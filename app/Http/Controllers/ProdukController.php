<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        // PISAHKAN DATA AGAR TAMPILAN RAPI
        // 1. Produk Aktif (Urutkan dari stok terdikit biar ketahuan mana yang mau habis)
        $produks_aktif = Produk::where('is_active', true)
                                ->orderBy('stok', 'asc')
                                ->get();

        // 2. Produk Non-Aktif (Arsip)
        $produks_nonaktif = Produk::where('is_active', false)
                                  ->orderBy('updated_at', 'desc')
                                  ->get();

        return view('produk.index', compact('produks_aktif', 'produks_nonaktif'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'kategori' => 'required',
        ]);

        Produk::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'is_active' => true
        ]);

        return redirect()->route('produk.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        // ... validasi yg lama ...
        
        $produk = Produk::findOrFail($id);
        
        // Cek apakah tombol "restore" ditekan?
        $is_active = $produk->is_active;
        if($request->has('restore')) {
            $is_active = true;
        }

        $produk->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'is_active' => $is_active // Update status
        ]);

        return redirect()->route('produk.index')->with('success', 'Data menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        try {
            // Coba hapus permanen
            $produk->delete();
            $pesan = 'Menu berhasil dihapus permanen!';
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika gagal (ada foreign key), arsipkan
            if ($e->getCode() == "23000") {
                $produk->update(['is_active' => false]);
                $pesan = 'Menu diarsipkan (Non-Aktif) karena memiliki riwayat transaksi.';
            } else {
                return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
            }
        }

        return redirect()->route('produk.index')->with('success', $pesan);
    }
    
    // Fitur Restore (Opsional: Mengaktifkan kembali menu arsip)
    public function restore($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update(['is_active' => true]);
        return redirect()->route('produk.index')->with('success', 'Menu berhasil diaktifkan kembali!');
    }
}