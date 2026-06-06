<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Menampilkan view form login (view belum dibuat)
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|string', // atau 'username' tergantung skema
            'password' => 'required|string',
        ]);

        // 2. Auth::attempt untuk cek kredensial
        if (Auth::attempt($credentials)) {
            // Mencegah session fixation
            $request->session()->regenerate();
            
            // 3. Cek role dan arahkan
            $role = Auth::user()->role;
            if ($role === 'admin') {
                return redirect()->route('dashboard.admin');
            } elseif ($role === 'pimpinan') {
                return redirect()->route('dashboard.pimpinan');
            }
            
            return redirect()->intended('/');
        }

        // Jika validasi gagal, kembali dengan pesan error
        return back()->withErrors([
            'email' => 'Kredensial tidak valid',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Menghapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect()->route('login');
    }
}
