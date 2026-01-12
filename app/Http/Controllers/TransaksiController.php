<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // 1. Tampilkan Halaman Kasir
    public function index()
    {
        // LOGIKA SISTEMATIS:
        // 1. Hanya ambil produk yang statusnya AKTIF (dijual).
        // 2. Urutkan berdasarkan Kategori supaya rapi, lalu Nama.
        $produks = Produk::where('is_active', true)
                         ->orderBy('kategori', 'asc')
                         ->orderBy('nama', 'asc')
                         ->get();
        
        $cart = session()->get('cart', []);
        
        // Hitung total belanja server-side (Validasi)
        $total_belanja = 0;
        $total_item = 0;
        foreach($cart as $details) {
            $total_belanja += $details['price'] * $details['quantity'];
            $total_item += $details['quantity'];
        }

        return view('transaksi.index', compact('produks', 'cart', 'total_belanja', 'total_item'));
    }

    // 2. Tambah Item (+1) dengan Validasi Stok
    public function addToCart($id) {
        $produk = Produk::findOrFail($id);
        if($produk->stok <= 0) return redirect()->back()->with('error', 'Stok Habis!');
        
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            if($cart[$id]['quantity'] + 1 > $produk->stok) return redirect()->back()->with('error', 'Stok Kurang!');
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $produk->nama,
                "quantity" => 1,
                "price" => $produk->harga,
                "kategori" => $produk->kategori
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back(); // Tanpa flash message biar cepat
    }

    // 3. Kurangi Item (-1)
    public function decreaseCart($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            if($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                session()->put('cart', $cart);
            } else {
                unset($cart[$id]); // Jika sisa 1, hapus item
                session()->put('cart', $cart);
            }
        }
        return redirect()->back();
    }

    // 4. Hapus Item Sepenuhnya
    public function deleteCart($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function cetakStruk($id) 
    {
        $transaksi = \App\Models\Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        return view('transaksi.struk', compact('transaksi'));
    }

    // 5. Simpan Transaksi (Checkout) dengan Pengurangan Stok
    public function store(Request $request)
    {
        $request->validate([
            'bayar' => 'required|numeric'
        ]);

        $cart = session()->get('cart');
        
        // A. Validasi Keranjang Kosong
        if(!$cart || count($cart) <= 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        // B. Hitung Ulang Total & Validasi Stok Terakhir (Double Check)
        $total_belanja = 0;
        foreach($cart as $id => $item) {
            $produk_db = Produk::find($id);
            
            // Cek stok lagi sebelum kunci transaksi
            if(!$produk_db || $produk_db->stok < $item['quantity']) {
                return redirect()->back()->with('error', 'Stok barang "' . $item['name'] . '" tidak cukup saat proses checkout.');
            }
            
            $total_belanja += $item['price'] * $item['quantity'];
        }

        // C. Validasi Uang Pembayaran
        if($request->bayar < $total_belanja) {
            return redirect()->back()->with('error', 'Uang pembayaran kurang!');
        }

        // D. PROSES DATABASE (Transaction)
        try {
            DB::transaction(function() use ($request, $cart, $total_belanja) {
                
                // 1. Buat Header Transaksi
                $transaksi = Transaksi::create([
                    'nomor_transaksi' => 'TRX-' . date('YmdHis') . rand(100,999), // Tambah random biar unik
                    'cabang_id' => Auth::user()->cabang_id ?? 1,
                    'user_id' => Auth::id(),
                    'total_belanja' => $total_belanja,
                    'bayar' => $request->bayar,
                    'kembalian' => $request->bayar - $total_belanja,
                ]);

                // 2. Buat Detail & KURANGI STOK
                foreach($cart as $id => $details) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $id,
                        'jumlah' => $details['quantity'],
                        'harga_satuan' => $details['price'],
                        'subtotal' => $details['price'] * $details['quantity'],
                    ]);

                    // PENTING: Kurangi Stok di Database
                    $produk = Produk::find($id);
                    $produk->decrement('stok', $details['quantity']);
                }
            });

            // E. Bersihkan Keranjang & Redirect
            session()->forget('cart');
            return redirect()->route('transaksi.index')->with('success', 'Transaksi Berhasil! Kembalian: Rp ' . number_format($request->bayar - $total_belanja, 0, ',', '.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}