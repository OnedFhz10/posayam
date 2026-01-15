<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <--- Wajib ada untuk hapus/cek file

class ProdukController extends Controller
{
    public function index()
    {
        // 1. Produk Aktif
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
        // 1. Validasi Input (Termasuk Gambar)
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'kategori' => 'required',
            // Gambar opsional, tapi kalau ada harus gambar valid max 2MB
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Siapkan Data
        $data = [
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'is_active' => true,
            'gambar' => null // Default null
        ];

        // 3. Proses Upload Gambar (Jika ada)
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'public/produks'
            $path = $request->file('gambar')->store('produks', 'public');
            $data['gambar'] = $path;
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'kategori' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // 2. Cek status aktif/restore
        $is_active = $produk->is_active;
        if($request->has('restore')) {
            $is_active = true;
        }

        // 3. Siapkan Data Update
        $data = [
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'is_active' => $is_active
        ];

        // 4. Proses Ganti Gambar (Jika ada upload baru)
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dulu agar server tidak penuh
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            // Simpan gambar baru
            $path = $request->file('gambar')->store('produks', 'public');
            $data['gambar'] = $path;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Data menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        try {
            // Simpan path gambar sebelum delete record
            $gambarLama = $produk->gambar;

            // Coba hapus permanen
            $produk->delete();

            // Jika berhasil delete database, HAPUS JUGA FILE GAMBARNYA
            if ($gambarLama && Storage::disk('public')->exists($gambarLama)) {
                Storage::disk('public')->delete($gambarLama);
            }

            $pesan = 'Menu berhasil dihapus permanen!';

        } catch (\Illuminate\Database\QueryException $e) {
            // Jika gagal (karena ada riwayat transaksi), maka ARSIPKAN saja
            if ($e->getCode() == "23000") {
                $produk->update(['is_active' => false]);
                // Gambar TIDAK dihapus karena produk hanya diarsipkan
                $pesan = 'Menu diarsipkan (Non-Aktif) karena memiliki riwayat transaksi.';
            } else {
                return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
            }
        }

        return redirect()->route('produk.index')->with('success', $pesan);
    }
    
    public function restore($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update(['is_active' => true]);
        return redirect()->route('produk.index')->with('success', 'Menu berhasil diaktifkan kembali!');
    }
}