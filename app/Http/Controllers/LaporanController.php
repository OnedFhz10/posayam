<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; 

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getFilteredData($request);
        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $data = $this->getFilteredData($request);
        
        $user = Auth::user();
        $cabang = $user->cabang ? $user->cabang->nama : 'Semua Cabang';
        $data['nama_cabang'] = $cabang;
        $data['dicetak_oleh'] = $user->name;

        $pdf = PDF::loadView('laporan.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Penjualan_' . date('Y-m-d_Hi') . '.pdf');
    }

    // --- FITUR BARU: PINDAHAN DARI TRANSAKSICONTROLLER ---
    public function cetakStrukThermal($id)
{
    $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
    return view('transaksi.struk_thermal', compact('transaksi'));
}

    public function destroy($id)
    {
        // 1. Cari Transaksi
        $transaksi = Transaksi::with('details')->findOrFail($id);

        // 2. Kembalikan Stok (Normal)
        foreach ($transaksi->details as $detail) {
            $produk = Produk::find($detail->produk_id);
            if ($produk) {
                $produk->increment('stok', $detail->jumlah);
            }
            $detail->delete();
        }

        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
    }

    private function getFilteredData($request)
    {
        $user = Auth::user();
        $query = Transaksi::with(['user', 'details.produk']); 

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
        }

        $transaksis = $query->latest()->get();

        // --- LOGIKA NORMAL ---
        $laporanItem = []; 

        foreach ($transaksis as $trx) {
            foreach ($trx->details as $detail) {
                if (!$detail->produk) {
                    $namaProduk = 'Item Terhapus (ID:'.$detail->produk_id.')';
                    $kategori = 'Unknown';
                } else {
                    $namaProduk = $detail->produk->nama;
                    $kategori = $detail->produk->kategori;
                }

                if (!isset($laporanItem[$namaProduk])) {
                    $laporanItem[$namaProduk] = [
                        'nama' => $namaProduk,
                        'kategori' => $kategori,
                        'total_qty' => 0,
                        'total_pendapatan' => 0
                    ];
                }
                
                $laporanItem[$namaProduk]['total_qty'] += $detail->jumlah;
                $laporanItem[$namaProduk]['total_pendapatan'] += $detail->subtotal;
            }
        }

        $produk_terjual = collect($laporanItem)->map(function($item) {
            return (object) $item;
        })->sortByDesc('total_qty')->values();
        
        $total_omzet = $transaksis->sum('total_belanja');
        $total_transaksi = $transaksis->count();

        return compact('transaksis', 'total_omzet', 'total_transaksi', 'startDate', 'endDate', 'produk_terjual');
    }
}