<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDevice
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $deviceIdFromRequest = $request->header('X-Device-ID'); // Nanti kita kirim dari frontend

        // Jika user sudah punya device_id terdaftar
        if ($user && $user->device_id) {
            if ($user->device_id !== $deviceIdFromRequest) {
                // Jika device tidak cocok, logout dan beri pesan error
                Auth::logout();
                return redirect('/login')->withErrors(['email' => 'Akun ini terdaftar di perangkat lain.']);
            }
        }
        // Jika user belum punya device_id (login pertama kali)
        elseif ($user && !$user->device_id && $deviceIdFromRequest) {
            // Daftarkan device_id ini
            $user->device_id = $deviceIdFromRequest;
            $user->save();
        }

        return $next($request);
    }
}