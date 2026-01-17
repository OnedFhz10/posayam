<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // MENAMPILKAN DAFTAR KARYAWAN
    public function index()
    {
        // Ambil semua user selain user yang sedang login (supaya tidak hapus diri sendiri nanti)
        // Kita load juga relasi 'cabang' agar bisa menampilkan nama cabangnya
        $users = User::with('cabang')->where('id', '!=', Auth::id())->latest()->get();
        return view('users.index', compact('users'));
    }

    // FORM TAMBAH KARYAWAN
    public function create()
    {
        // Kita butuh data cabang untuk dropdown pilihan
        $cabangs = Cabang::all();
        return view('users.create', compact('cabangs'));
    }

    // PROSES SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,kasir',
            'cabang_id' => 'required|exists:cabangs,id', // Wajib pilih cabang yang valid
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-encrypt
            'role' => $request->role,
            'cabang_id' => $request->cabang_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Karyawan baru berhasil didaftarkan!');
    }

    // HAPUS KARYAWAN
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}