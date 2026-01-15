<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail; // Tambahkan ini
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. KARTU UTAMA (Hari Ini)
        $omzet = Transaksi::whereDate('created_at', $today)->sum('total_belanja');
        $transaksi_sukses = Transaksi::whereDate('created_at', $today)->count();

        // 2. DATA GRAFIK: Omzet 7 Hari Terakhir
        $chartLabels = [];
        $chartOmzet = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d M'); // Contoh: 15 Jan
            
            // Ambil omzet per tanggal tersebut
            $total = Transaksi::whereDate('created_at', $date->format('Y-m-d'))->sum('total_belanja');
            $chartOmzet[] = $total;
        }

        // 3. DATA GRAFIK: 5 Produk Terlaris (Pie Chart)
        $topProduk = TransaksiDetail::select('produks.nama', DB::raw('sum(transaksi_details.jumlah) as total'))
            ->join('produks', 'transaksi_details.produk_id', '=', 'produks.id')
            ->groupBy('produks.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $namaProduk = $topProduk->pluck('nama');
        $jumlahProduk = $topProduk->pluck('total');

        // Pastikan nama view sesuai lokasi file Anda.
        // Jika file ada di 'resources/views/dashboard.blade.php', gunakan 'dashboard'
        // Jika file ada di 'resources/views/dashboard/index.blade.php', gunakan 'dashboard.index'
        $viewName = view()->exists('dashboard.index') ? 'dashboard.index' : 'dashboard';

        return view($viewName, compact(
            'omzet', 
            'transaksi_sukses', 
            'chartLabels', 
            'chartOmzet',
            'namaProduk',
            'jumlahProduk'
        ));
    }
}