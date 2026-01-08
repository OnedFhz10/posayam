<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // 1. Ambil Data yang sama dengan filter saat ini
        $data = $this->getFilteredData($request);

        // 2. Load View khusus PDF
        $pdf = PDF::loadView('laporan.pdf', $data);

        // 3. Download file
        return $pdf->download('laporan-transaksi.pdf');
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