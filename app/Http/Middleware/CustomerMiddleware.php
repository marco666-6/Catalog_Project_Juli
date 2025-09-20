<?php

// app/Http/Middleware/CustomerMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isCustomer()) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Akses ditolak. Anda harus login sebagai customer.');
    }
}