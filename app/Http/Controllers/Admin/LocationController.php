<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use App\Services\ZktecoApiService; // Import service API
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    protected $zktecoApiService;

    // Suntikkan (inject) service API melalui constructor
    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    /**
     * Menampilkan daftar gabungan terminal dari API dan data lokasi dari MySQL.
     */
    public function index(): View
    {
        try {
            // Mengambil data terminal dari API
            $terminals = $this->zktecoApiService->getTerminals();
            
            // Mengambil data lokasi yang sudah disimpan secara lokal
            $localLocations = OfficeLocation::all()->keyBy('terminal_id');

            return view('admin.locations.index', [
                'terminals' => $terminals,
                'localLocations' => $localLocations,
            ]);

        } catch (\Exception $e) {
            // Menangani error jika API tidak bisa dihubungi
            return view('admin.locations.index-error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form untuk menambah/mengedit data lokasi untuk sebuah terminal.
     */
    public function edit($terminal_id): View
    {
        // Mengambil detail terminal dari API
        $terminal = $this->zktecoApiService->getTerminalDetails($terminal_id);

        if (!$terminal) {
            // Redirect jika terminal tidak ditemukan via API
            return redirect()->route('admin.locations.index')->withErrors('Terminal tidak ditemukan.');
        }

        // Mencari data lokasi lokal yang sudah ada, atau membuat instance baru jika belum ada
        $location = OfficeLocation::firstOrNew(['terminal_id' => $terminal_id]);

        return view('admin.locations.edit', compact('terminal', 'location'));
    }

    /**
     * --- BAGIAN YANG DIPERBARUI ---
     * Menyimpan atau memperbarui data lokasi. Nama lokasi diambil otomatis dari API.
     */
    public function update(Request $request, $terminal_id)
    {
        // 1. Validasi hanya data yang diinput oleh pengguna
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meters' => 'required|integer|min:1',]
            , [
        'latitude.between'  => 'Nilai latitude harus antara -90 dan 90.',
        'longitude.between' => 'Nilai longitude harus antara -180 dan 180.',
        '*.required'        => 'Kolom ini wajib diisi.',
        '*.numeric'         => 'Kolom ini harus berupa angka.',]
        );

        // 2. Ambil detail terminal dari API untuk mendapatkan namanya
        $terminal = $this->zktecoApiService->getTerminalDetails($terminal_id);

        if (!$terminal) {
            return redirect()->back()->withErrors('Gagal mengambil nama terminal dari API untuk disimpan.');
        }

        // 3. Tentukan nama lokasi dari nama terminal di API
        $locationName = $terminal['alias'] ?? $terminal['name'] ?? 'Terminal ' . $terminal_id;

        // 4. Gabungkan data yang divalidasi dengan nama yang didapat dari API
        $dataToSave = array_merge($validatedData, ['name' => $locationName]);

        // 5. Simpan ke database
        OfficeLocation::updateOrCreate(
            ['terminal_id' => $terminal_id], // Kondisi pencarian
            $dataToSave // Data untuk diisi atau diperbarui
        );

        return redirect()->route('admin.locations.index')->with('success', 'Data lokasi berhasil disimpan.');
    }

    /**
     * Menghapus data lokasi dari database lokal (MySQL).
     */
    public function destroy($terminal_id)
    {
        OfficeLocation::where('terminal_id', $terminal_id)->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Data lokasi berhasil dihapus.');
    }
}

