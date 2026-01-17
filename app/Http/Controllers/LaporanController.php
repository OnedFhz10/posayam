<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF; 

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data (Logika Filter terpusat)
        $data = $this->getFilteredData($request);

        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        // 1. VALIDASI: Pastikan user memilih tanggal agar laporan tidak terlalu besar
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'Pilih Tanggal Awal dulu.',
            'end_date.required'   => 'Pilih Tanggal Akhir dulu.',
        ]);

        // 2. AMBIL DATA (Gunakan fungsi filter yang sama dengan index)
        $data = $this->getFilteredData($request);
        
        // Tambahkan info toko/cabang untuk judul laporan
        $user = Auth::user();
        $cabang = $user->cabang ? $user->cabang->nama : 'Semua Cabang';
        $data['nama_cabang'] = $cabang;
        $data['dicetak_oleh'] = $user->name;

        // 3. GENERATE PDF
        $pdf = PDF::loadView('laporan.pdf', $data);
        
        // Set ukuran kertas struk (opsional) atau A4
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Harian_' . date('Y-m-d_H-i') . '.pdf');
    }

    public function destroy($id)
    {
        // 1. Cari Transaksi berdasarkan ID
        $transaksi = Transaksi::with('details')->findOrFail($id);

        // 2. Kembalikan Stok Produk (Looping setiap item yang dibeli)
        foreach ($transaksi->details as $detail) {
            $produk = Produk::find($detail->produk_id);
            if ($produk) {
                $produk->increment('stok', $detail->jumlah);
            }
            // Hapus detailnya
            $detail->delete();
        }

        // 3. Hapus Transaksi Utama
        $transaksi->delete();

        return redirect()->back()->with('success', 'Laporan transaksi berhasil dihapus dan stok telah dikembalikan.');
    }

    // --- FUNGSI PRIVAT UNTUK FILTER & LOGIKA PECAH PAKET ---
    private function getFilteredData($request)
    {
        $user = Auth::user();
        
        // 1. Query Dasar Transaksi
        $query = Transaksi::with(['user', 'details.produk']); 

        // LOGIKA FILTER ROLE (Disesuaikan):
        // Jika ADMIN: Bisa melihat semua data (tidak ada filter where).
        // Jika KASIR: Hanya melihat data di CABANG tempat dia bekerja.
        if ($user->role !== 'admin') { 
            $query->where('cabang_id', $user->cabang_id);
        }

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $query->whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        if ($request->filled('nomor_transaksi')) {
            $query->where('nomor_transaksi', 'like', '%' . $request->nomor_transaksi . '%');
        } elseif ($request->filled('kode_transaksi')) {
            $query->where('kode_transaksi', 'like', '%' . $request->kode_transaksi . '%');
        }

        $transaksis = $query->latest()->get();

        // -----------------------------------------------------------
        // BAGIAN MODIFIKASI: LOGIKA PECAH PAKET (BREAKDOWN)
        // -----------------------------------------------------------
        
        // A. Definisikan Resep Paket Disini (Sesuaikan dengan Nama Menu di Database Anda)
        // Format: 'Nama Menu Paket' => ['Komponen' => Jumlah, 'Komponen Lain' => Jumlah]
        $resepPaket = [
            'Paket Ayam Bakar' => [
                'Ayam Bakar' => 1, 
                'Nasi' => 1
            ],
            'Paket Ayam Chiken' => [ 
                'Ayam Chicken' => 1, 
                'Nasi' => 1
            ],
            'Paket Jumbo' => [
                'Ayam Chicken' => 2, 
                'Nasi' => 1,
                'Es Teh Manis' => 1
            ],
            // Tambahkan paket lain di sini sesuai nama di Database Anda...
        ];

        // B. Array penampung hasil akhir laporan item
        $laporanItem = []; 

        // C. Loop semua transaksi untuk dihitung manual (agar bisa dipecah)
        foreach ($transaksis as $trx) {
            foreach ($trx->details as $detail) {
                // Pastikan produk masih ada (tidak null)
                if (!$detail->produk) continue;

                $namaProduk = $detail->produk->nama;
                $qtyBeli = $detail->jumlah;
                $subtotal = $detail->subtotal;
                $kategoriAsli = $detail->produk->kategori;

                // Cek apakah produk ini adalah Paket yang terdaftar di resep?
                if (array_key_exists($namaProduk, $resepPaket)) {
                    
                    // --- JIKA PAKET: Pecah isinya ---
                    $komponen = $resepPaket[$namaProduk];
                    
                    // Hitung estimasi pendapatan per komponen (Subtotal dibagi jumlah jenis item)
                    $jumlahJenisItem = count($komponen);
                    $pendapatanPerKomponen = $subtotal / ($jumlahJenisItem > 0 ? $jumlahJenisItem : 1);

                    foreach ($komponen as $namaKomponen => $qtyPerPaket) {
                        // Total qty komponen = Qty Beli Paket * Qty dalam Paket
                        $totalQtyKomponen = $qtyBeli * $qtyPerPaket;
                        
                        // Tentukan kategori komponen secara manual/logika sederhana
                        $kategoriKomponen = 'Item Pecahan'; // Default
                        if(str_contains($namaKomponen, 'Ayam')) $kategoriKomponen = 'Ayam';
                        elseif(str_contains($namaKomponen, 'Nasi')) $kategoriKomponen = 'Topping';
                        elseif(str_contains($namaKomponen, 'Teh') || str_contains($namaKomponen, 'Minum') || str_contains($namaKomponen, 'Es')) $kategoriKomponen = 'Minuman';

                        // Masukkan ke array laporan
                        if (!isset($laporanItem[$namaKomponen])) {
                            $laporanItem[$namaKomponen] = [
                                'nama' => $namaKomponen,
                                'kategori' => $kategoriKomponen,
                                'total_qty' => 0,
                                'total_pendapatan' => 0
                            ];
                        }
                        $laporanItem[$namaKomponen]['total_qty'] += $totalQtyKomponen;
                        $laporanItem[$namaKomponen]['total_pendapatan'] += $pendapatanPerKomponen;
                    }

                } else {
                    
                    // --- JIKA BUKAN PAKET (Produk Biasa): Masukkan langsung ---
                    if (!isset($laporanItem[$namaProduk])) {
                        $laporanItem[$namaProduk] = [
                            'nama' => $namaProduk,
                            'kategori' => $kategoriAsli,
                            'total_qty' => 0,
                            'total_pendapatan' => 0
                        ];
                    }
                    $laporanItem[$namaProduk]['total_qty'] += $qtyBeli;
                    $laporanItem[$namaProduk]['total_pendapatan'] += $subtotal;
                }
            }
        }

        // D. Ubah Array kembali menjadi Collection Object agar view bisa membacanya ($item->nama)
        // dan Urutkan dari yang terbanyak (Best Seller)
        $produk_terjual = collect($laporanItem)->map(function($item) {
            return (object) $item;
        })
        ->sortByDesc('total_qty')
        ->values();

        // -----------------------------------------------------------
        
        $total_omzet = $transaksis->sum('total_belanja');
        $total_transaksi = $transaksis->count();

        return compact(
            'transaksis', 
            'total_omzet', 
            'total_transaksi', 
            'startDate', 
            'endDate',
            'produk_terjual' 
        );
    }
}