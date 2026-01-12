<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use PDF; // Import PDF

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data (Logika Filter)
        $data = $this->getFilteredData($request);

        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        // 1. VALIDASI WAJIB: Harus ada tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'Silakan pilih Tanggal Awal dulu untuk mencetak PDF.',
            'end_date.required'   => 'Silakan pilih Tanggal Akhir dulu untuk mencetak PDF.',
            'end_date.after_or_equal' => 'Tanggal Akhir tidak boleh kurang dari Tanggal Awal.'
        ]);

        // 2. Ambil Data
        $data = $this->getFilteredData($request);

        // 3. Load View & Download
        $pdf = PDF::loadView('laporan.pdf', $data);
        return $pdf->download('laporan-transaksi-' . date('YmdHis') . '.pdf');
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

    // --- FUNGSI PRIVAT UNTUK FILTER (Agar tidak tulis ulang kode) ---
    private function getFilteredData($request)
    {
        $user = Auth::user();
        $query = Transaksi::with(['user', 'details.produk']); // Load detail & produk

        // Filter Role
        if ($user->role !== 'admin') { 
            $query->where('user_id', $user->id);
        }

        // Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter Kode
        if ($request->filled('kode_transaksi')) {
            $query->where('kode_transaksi', 'like', '%' . $request->kode_transaksi . '%');
        }

        $transaksis = $query->latest()->get();

        // --- LOGIKA HITUNG DETAIL ITEM TERJUAL ---
        // Ambil semua ID transaksi hasil filter
        $transaksiIds = $transaksis->pluck('id');

        // Query ke tabel detail untuk menjumlahkan per produk
        $terjualPerItem = TransaksiDetail::whereIn('transaksi_id', $transaksiIds)
            ->with('produk')
            ->selectRaw('produk_id, sum(jumlah) as total_qty') // Asumsi kolomnya 'jumlah'
            ->groupBy('produk_id')
            ->get();
        // ------------------------------------------

        $total_omzet = $transaksis->sum('total_belanja');
        $total_transaksi = $transaksis->count();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return compact(
            'transaksis', 
            'total_omzet', 
            'total_transaksi', 
            'startDate', 
            'endDate',
            'terjualPerItem' // Kirim data item terjual ke view
        );
    }
}