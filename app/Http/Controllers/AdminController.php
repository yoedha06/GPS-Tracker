<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        //tampilan login admin
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Cek apakah pengguna memiliki peran 'admin'
            if (Auth::user()->role === 'admin') {
                // Jika pengguna adalah admin, arahkan ke dashboard admin
                return redirect()->route('index.admin');
            } else {
                // Jika bukan admin, kembalikan ke halaman login admin dengan pesan
                return redirect()->route('login.admin')->with('error', 'Data yang dimasukkan salah');
            }
        }

        // Jika autentikasi gagal, kembalikan ke halaman login dengan pesan error
        return redirect()->back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }



    public function logoutadmin(Request $request)
    {
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.admin');
    }

}
