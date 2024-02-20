<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna sudah masuk
        if (!auth()->check() && !$request->is('register')) {
            // Jika belum masuk dan bukan rute registrasi, arahkan ke halaman login
            return redirect()->route('login');
        }

        // Lanjutkan permintaan jika pengguna sudah masuk atau sedang di rute registrasi
        return $next($request);
    }
}
