<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // Periksa apakah pengguna sudah login dan memiliki peran admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika ya, lanjutkan ke rute yang diminta
            return $next($request);
        }
        
        // Jika tidak, redirect ke halaman login admin
        return redirect()->route('login.admin');

        
    }
}
