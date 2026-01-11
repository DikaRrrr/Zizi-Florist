<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('frontend.v_auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Cek agar email tidak kembar
            'password' => 'required|min:6',
            'alamat' => 'required|string',
            'hp' => 'required|numeric',
        ]);

        // 2. Simpan ke Database
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // WAJIB DI-HASH!
            'alamat' => $request->alamat,
            'hp' => $request->hp,
            'role' => 'customer', // Sesuaikan jika ada kolom role
        ]);

        // 3. Redirect ke Login dengan Pesan Sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // --- LOGIN ---
    public function showLoginForm()
    {
        return view('frontend.v_auth.login');
    }

    public function login(Request $request)
    { // 1. Validasi
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- CEK ROLE DISINI ---
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Masuk Dapur (Admin)
            }

            return redirect()->intended('/')->with('success', 'Login Berhasil.'); // Masuk Ruang Tamu (User Biasa)
        }

        // 3. Kalau Gagal
        return back()->withErrors([
            'email' => 'Email salah.',   // Bikin email merah
            'password' => 'Password salah',      // Bikin password merah
        ])->onlyInput('email');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
