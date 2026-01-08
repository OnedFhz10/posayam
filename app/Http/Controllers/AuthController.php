<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function index()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // LOGIKA SISTEMATIS: Arahkan sesuai peran
            $role = Auth::user()->role;
            
            if ($role == 'kasir') {
                // Kasir langsung ke Mesin Kasir
                return redirect()->route('transaksi.index');
            } else {
                // Admin ke Dashboard/Laporan
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}