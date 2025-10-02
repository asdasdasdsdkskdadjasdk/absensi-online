<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika pengguna sudah login DAN kolom profile_updated_at masih kosong
        if (Auth::check() && ! Auth::user()->profile_updated_at) {

            // Beri pengecualian agar tidak terjadi redirect loop
            // Izinkan akses ke halaman profil, proses update profil, dan logout
            if ( ! $request->routeIs('profile.edit') && ! $request->routeIs('profile.update') && ! $request->routeIs('logout')) {

                // Jika mengakses halaman lain, paksa redirect ke halaman profil
                return redirect()->route('profile.edit')
                    ->with('warning', 'Harap lengkapi email dan password Anda sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}