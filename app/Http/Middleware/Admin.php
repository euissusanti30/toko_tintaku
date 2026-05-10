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
        // Check if user is authenticated and has admin role (role = 1)
        if (!Auth::check() || Auth::user()->role != 1) {
            // If not admin, redirect to home page
            return redirect('/home');
        }

        return $next($request);
    }
}
