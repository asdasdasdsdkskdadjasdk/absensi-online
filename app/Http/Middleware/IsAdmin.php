<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Jika bukan admin, tendang ke halaman dashboard biasa atau tampilkan error
        abort(403, 'ANDA TIDAK MEMILIKI AKSES ADMIN.');
    }
}