<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ZktecoApiService; // Import service API
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator; // Untuk membuat paginasi manual
use Illuminate\View\View;

class EmployeeController extends Controller
{
    protected $zktecoApiService;

    // Suntikkan (inject) service API
    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    /**
     * Menampilkan daftar karyawan dari API.
     */
    public function index(Request $request): View
    {
        try {
            // Mengambil data dari API Service
            $apiData = $this->zktecoApiService->getEmployees($request);

            // Membuat objek paginasi secara manual dari data API
            // Asumsi struktur API: { "data": [...], "meta": { "total": ..., "per_page": ..., "current_page": ...} }
            $employees = new LengthAwarePaginator(
                $apiData['data'] ?? $apiData['results'] ?? [], // Item per halaman
                $apiData['meta']['total'] ?? $apiData['count'] ?? 0, // Total item
                $apiData['meta']['per_page'] ?? 50, // Item per halaman
                $apiData['meta']['current_page'] ?? $request->input('page', 1), // Halaman saat ini
                ['path' => $request->url(), 'query' => $request->query()] // Opsi untuk URL
            );

            return view('admin.employees.index', [
                'employees' => $employees,
                'sortBy' => $request->input('sort_by', 'id'),
                'sortDir' => $request->input('sort_dir', 'asc')
            ]);

        } catch (\Exception $e) {
            // Menggunakan kembali view error jika API gagal dihubungi
            return view('admin.locations.index-error', ['error' => 'Gagal mengambil data karyawan dari API: ' . $e->getMessage()]);
        }
    }
}
