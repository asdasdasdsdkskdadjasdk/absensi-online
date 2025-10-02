<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LoadSettingsFromDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Hanya muat jika tabel 'settings' ada
            if (Schema::hasTable('settings')) {
                $settings = DB::table('settings')->pluck('value', 'key')->all();
                Config::set('settings', $settings);
            }
        } catch (\Exception $e) {
            Log::error("Gagal memuat pengaturan dari database: " . $e->getMessage());
        }

        return $next($request);
    }
}

