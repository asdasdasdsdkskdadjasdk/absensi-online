<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ZktecoApiService; // Menggunakan API Service
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator; // Untuk membuat paginasi manual
use Illuminate\View\View;

class BioPhotoController extends Controller
{
    protected $zktecoApiService;

    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    /**
     * Menampilkan daftar foto registrasi karyawan dari API.
     */
    public function index(Request $request): View
    {
        try {
            // Langkah A: Ambil data foto (termasuk path) dari API
            $apiData = $this->zktecoApiService->getBioPhotos($request);

            // Langkah B: Untuk setiap data, ambil gambar mentah menggunakan path dari 'approval_photo'
            $photosWithImages = array_map(function ($photo) {
                if (!empty($photo['approval_photo'])) {
                    $imageData = $this->zktecoApiService->getRawImageData($photo['approval_photo']);
                    if ($imageData) {
                        // Simpan data gambar Base64 ke key 'register_photo' agar view bisa menampilkannya
                        $photo['register_photo'] = base64_encode($imageData);
                    }
                }
                return $photo;
            }, $apiData['data'] ?? []);

            // Langkah C: Buat objek paginasi dengan data yang sudah diperkaya gambar
            $bioPhotos = new LengthAwarePaginator(
                $photosWithImages,
                $apiData['meta']['total'] ?? 0,
                $apiData['meta']['per_page'] ?? 15,
                $apiData['meta']['current_page'] ?? 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.biophoto.index', compact('bioPhotos'));

        } catch (\Exception $e) {
            // Menangani error jika API gagal dihubungi
            return view('admin.locations.index-error', ['error' => 'Gagal mengambil data foto dari API: ' . $e->getMessage()]);
        }
    }
}

