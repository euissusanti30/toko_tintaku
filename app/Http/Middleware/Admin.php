<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
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
        // Check admin guard for admin user
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->role != 1) {
            return redirect('/home')->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
