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
                Auth::logout();
                return redirect()->route('login.admin');
            } elseif (Auth::user()->role === 'admin') {

                return redirect()->route('index.admin');

            }
        }

        return $next($request);
        
    }
}
