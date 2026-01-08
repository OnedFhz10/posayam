<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // --- DATA UMUM (Semua butuh ini) ---
        // 1. Ucapan Selamat (Pagi/Siang/Sore)
        $hour = Carbon::now()->format('H');
        if ($hour < 12) $greeting = 'Selamat Pagi';
        elseif ($hour < 15) $greeting = 'Selamat Siang';
        elseif ($hour < 18) $greeting = 'Selamat Sore';
        else $greeting = 'Selamat Malam';

        // --- DATA KHUSUS ADMIN (Global) ---
        if ($user->role == 'admin') {
            $omzet_hari_ini = Transaksi::whereDate('created_at', $today)->sum('total_belanja');
            $transaksi_hari_ini = Transaksi::whereDate('created_at', $today)->count();
            
            // Grafik 7 Hari Terakhir
            $chart_labels = [];
            $chart_values = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chart_labels[] = $date->format('d/m');
                $chart_values[] = Transaksi::whereDate('created_at', $date)->sum('total_belanja');
            }

            // Stok Menipis (Penting untuk Admin belanja)
            $stok_menipis = Produk::where('stok', '<=', 10)->get();
            $produk_laris = null; // Opsional jika mau ditambah
        } 
        
        // --- DATA KHUSUS KASIR (Personal) ---
        else {
            // Kasir hanya melihat omzet DIA SENDIRI hari ini (untuk setoran)
            $omzet_hari_ini = Transaksi::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->sum('total_belanja');
                                
            $transaksi_hari_ini = Transaksi::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->count();
            
            // Kasir tidak butuh grafik tren global, stok menipis, dll.
            $chart_labels = []; 
            $chart_values = [];
            $stok_menipis = collect([]); // Kosongkan
        }

        return view('dashboard.index', compact(
            'greeting',
            'omzet_hari_ini', 
            'transaksi_hari_ini', 
            'chart_labels', 
            'chart_values',
            'stok_menipis'
        ));
    }
}