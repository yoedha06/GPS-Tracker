<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function dologin(Request $request)
    {
        // Validasi data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Jika berhasil, redirect ke lokasi yang sesuai
            return redirect()->intended('customer');
        }

        // Jika autentikasi gagal, kembalikan ke halaman login dengan pesan kesalahan
        return redirect()->back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }
}
