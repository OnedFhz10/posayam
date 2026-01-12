<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // 1. Hitung Omzet (Total Belanja) Hari Ini
        $omzetHariIni = Transaksi::whereDate('created_at', $today)->sum('total_belanja');

        // 2. Hitung Jumlah Transaksi Sukses Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();

        return view('dashboard.index', compact('omzetHariIni', 'transaksiHariIni'));
    }
}