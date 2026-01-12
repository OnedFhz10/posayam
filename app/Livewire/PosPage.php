<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosPage extends Component
{
    // === DATA UTAMA ===
    public $cart = [];
    public $search = '';
    public $kategoriPilihan = 'Semua';
    public $bayar = ''; 

    // === HASIL HITUNGAN ===
    public $totalBelanja = 0;
    public $totalItem = 0;
    public $kembalian = 0;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->hitungTotal();
    }

    #[Layout('layouts.app')] 
    public function render()
    {
        $query = Produk::where('is_active', true);

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        if ($this->kategoriPilihan != 'Semua') {
            $query->where('kategori', $this->kategoriPilihan);
        }

        return view('livewire.pos-page', [
            'produks' => $query->orderBy('stok', 'desc')->get()
        ]);
    }

    // --- HELPER PEMBERSIH ANGKA ---
    public function bersihkanAngka($nilai)
    {
        if (empty($nilai)) return 0;
        $bersih = preg_replace('/[^0-9]/', '', (string)$nilai);
        return (int) $bersih;
    }

    // --- LOGIKA KERANJANG ---
    public function gantiKategori($nama) 
    {
        $this->kategoriPilihan = $nama; 
        $this->search = ''; 
    }

    public function addToCart($id)
    {
        $produk = Produk::find($id);
        if (!$produk || $produk->stok <= 0) return session()->flash('error', 'Stok Habis!');

        if (isset($this->cart[$id])) {
            if ($this->cart[$id]['quantity'] + 1 > $produk->stok) return session()->flash('error', 'Stok tidak cukup!');
            $this->cart[$id]['quantity']++;
        } else {
            $this->cart[$id] = [
                'name' => $produk->nama,
                'quantity' => 1,
                'price' => (int) $produk->harga
            ];
        }
        $this->updateSession();
    }

    public function decreaseCart($id)
    {
        if (isset($this->cart[$id])) {
            if ($this->cart[$id]['quantity'] > 1) {
                $this->cart[$id]['quantity']--;
            } else {
                unset($this->cart[$id]);
            }
            $this->updateSession();
        }
    }

    // --- LOGIKA HITUNG ---
    public function hitungTotal()
    {
        $this->totalBelanja = 0;
        $this->totalItem = 0;

        foreach ($this->cart as $item) {
            $this->totalBelanja += $item['price'] * $item['quantity'];
            $this->totalItem += $item['quantity'];
        }
        
        $bayarInt = $this->bersihkanAngka($this->bayar);
        
        if ($bayarInt >= $this->totalBelanja) {
            $this->kembalian = $bayarInt - $this->totalBelanja;
        } else {
            $this->kembalian = 0;
        }
    }

    public function updatedBayar() 
    { 
        $this->hitungTotal(); 
    }

    public function updateSession() 
    { 
        session()->put('cart', $this->cart); 
        $this->hitungTotal(); 
    }

    // --- PROSES CHECKOUT ---
    public function store()
    {
        $bayarInt = $this->bersihkanAngka($this->bayar);
        
        // Hitung ulang total untuk memastikan data sinkron
        $this->hitungTotal();
        $totalInt = $this->totalBelanja;

        // Validasi
        if (empty($this->cart)) return session()->flash('error', 'Keranjang kosong!');
        
        if ($bayarInt < $totalInt) {
            return session()->flash('error', 
                "Uang kurang! Total: " . number_format($totalInt, 0,',','.') . 
                ", Diterima: " . number_format($bayarInt, 0,',','.')
            );
        }

        DB::beginTransaction();
        try {
            $trx = Transaksi::create([
                'nomor_transaksi' => 'TRX-' . date('ymdHis') . rand(100, 999),
                
                // --- PERBAIKAN DISINI: Menambahkan cabang_id ---
                // Kita ambil dari user login, atau default ke 1 jika tidak ada
                'cabang_id' => Auth::user()->cabang_id ?? 1, 
                
                'user_id' => Auth::id(),
                'total_belanja' => $totalInt,
                'bayar' => $bayarInt,
                'kembalian' => $this->kembalian,
            ]);

            foreach ($this->cart as $id => $item) {
                $produk = Produk::where('id', $id)->lockForUpdate()->first();
                if (!$produk || $produk->stok < $item['quantity']) throw new \Exception("Stok " . ($item['name'] ?? 'Item') . " habis!");

                TransaksiDetail::create([
                    'transaksi_id' => $trx->id,
                    'produk_id' => $id,
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                $produk->decrement('stok', $item['quantity']);
            }

            DB::commit();

            // Reset
            session()->forget('cart');
            $this->cart = [];
            $this->bayar = '';
            $this->hitungTotal();
            
            session()->flash('success', 'Transaksi Berhasil!');
            $this->dispatch('transaksi-berhasil'); 

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }
}