<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToAdminHome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->is('logout')) {
                // Jika pengguna ingin logout, lakukan logout dan redirect ke halaman login admin
                Auth::logout();
                return redirect()->route('login.admin')->with('status', 'Anda telah logout.');
            } elseif (Auth::user()->role === 'admin') {
                // Jika pengguna adalah admin, arahkan ke dashboard admin
                return redirect()->route('index.admin');
            }
        }

        return $next($request);
    }
}
