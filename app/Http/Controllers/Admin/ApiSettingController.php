<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // <-- Tambahkan ini
use Illuminate\Support\Str;
use Throwable; // <-- Tambahkan ini

class ApiSettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan API.
     */
    public function index()
    {
        
        $apiToken = DB::table('settings')->where('key', 'api_token')->value('value');
        return view('admin.api.index', ['apiToken' => $apiToken]);
    }

    /**
     * Meminta token baru dari API WDMS dan menyimpannya ke database.
     */
    public function refreshToken()
    {
        try {
            // 1. Ambil detail koneksi dari file .env
            $apiUrl = env('WDMS_API_URL');
            $username = env('WDMS_API_USERNAME');
            $password = env('WDMS_API_PASSWORD');
            $endpoint = "/jwt-api-token-auth/";

            // 2. Lakukan panggilan API menggunakan Laravel HTTP Client
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl . $endpoint, [
                    'username' => $username,
                    'password' => $password,
                ]);

            // 3. Periksa jika panggilan API gagal
            if ($response->failed()) {
                // Jika gagal karena kredensial salah (400) atau error server lain
                return redirect()->route('admin.api.index')
                    ->with('error', 'Gagal mendapatkan token dari API. Status: ' . $response->status() . '. Pesan: ' . $response->body());
            }

            // 4. Ambil token dari response JSON
            $newToken = $response->json('token');

            if (!$newToken) {
                return redirect()->route('admin.api.index')
                    ->with('error', 'Respon dari API berhasil, tetapi tidak mengandung token.');
            }

            // 5. Simpan token baru ke database
            DB::table('settings')
                ->where('key', 'api_token')
                ->update(['value' => $newToken, 'updated_at' => now()]);

            return redirect()->route('admin.api.index')
                ->with('success', 'Token API berhasil diperbarui dari server WDMS!');

        } catch (Throwable $e) {
            // Menangkap error koneksi (misalnya server tidak bisa dijangkau)
            return redirect()->route('admin.api.index')
                ->with('error', 'Gagal terhubung ke server API WDMS. Pastikan server berjalan dan bisa diakses. Error: ' . $e->getMessage());
        }
    }
}

