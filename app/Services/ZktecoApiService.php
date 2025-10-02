<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ZktecoApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.zkteco.url');
        $tokenValue = config('settings.api_token');
        $this->token = 'JWT ' . $tokenValue;
    }

    private function makeRequest()
    {
        if (!$this->baseUrl || !$this->token) {
            Log::error('Konfigurasi ZKTeco API (URL/Token) tidak ditemukan.');
            return null;
        }
        return Http::withHeaders(['Authorization' => $this->token])->acceptJson();
    }

    /**
     * Mengambil data transaksi absensi yang sudah disetujui dari API dengan filter dinamis.
     */
    public function getApprovedTransactions(Request $request)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) {
            return ['data' => [], 'count' => 0];
        }

        $endpoint = '/iclock/api/transactions/'; 

        $queryParams = [
            'page_size' => $request->input('page_size', 15),
            'page'      => $request->input('page', 1),
            'ordering'  => '-punch_time',
        ];

        // --- PERBAIKAN UTAMA: MENERAPKAN SEMUA FILTER DENGAN NAMA PARAMETER YANG BENAR ---
        if ($request->filled('search')) {
            $queryParams['search'] = $request->input('search');
        }
        if ($request->filled('start_date')) {
            // PENTING: Nama parameter ini ('punch_time__date__gte') harus sesuai dengan API Anda.
            // Ini adalah format umum untuk API berbasis Django/Python untuk filter "tanggal lebih besar atau sama dengan".
            $queryParams['punch_time__date__gte'] = $request->input('start_date');
        }
        if ($request->filled('end_date')) {
             // Ini adalah format umum untuk filter "tanggal lebih kecil atau sama dengan".
            $queryParams['punch_time__date__lte'] = $request->input('end_date');
        }
        if ($request->filled('department')) {
            // Nama parameter di API adalah 'department'
            $queryParams['department'] = $request->input('department');
        }
        if ($request->filled('terminal_alias')) {
            // Filter ini sudah terbukti berfungsi
            $queryParams['terminal_alias'] = $request->input('terminal_alias');
        }

        // Kirim request ke API dengan query parameter yang sudah lengkap
        $response = $httpClient->get("{$this->baseUrl}{$endpoint}", array_filter($queryParams));

        if ($response->successful()) {
            return $response->json();
        }
        
        // Jika gagal, kembalikan array kosong agar tidak error
        return ['data' => [], 'count' => 0]; 
    }

    /**
     * Mengambil semua terminal dari API untuk filter.
     * @return Collection
     */
    public function getAllTerminals(): Collection
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return collect();

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/terminals/", ['page_size' => 1000]);

        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results') ?? [];
            return collect($results)->pluck('alias')->unique()->filter()->sort()->values();
        }
        return collect();
    }

    /**
     * Mengambil semua departemen dari API untuk filter.
     * @return Collection
     */
    public function getAllDepartments(): Collection
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return collect();

        $response = $httpClient->get("{$this->baseUrl}/personnel/api/departments/", ['page_size' => 1000]);

        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results') ?? [];
            return collect($results)->pluck('dept_name')->unique()->filter()->sort()->values();
        }
        return collect();
    }

    /**
     * Mengambil dan "meratakan" detail karyawan dari API.
     */
    public function getEmployeeDetails(string $empCode)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return null;
        
        $response = $httpClient->get("{$this->baseUrl}/personnel/api/employees/", ['emp_code' => $empCode]);

        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results');
            if (!empty($results) && isset($results[0])) {
                return $this->flattenEmployeeData($results[0]);
            }
        }
        return null;
    }

    /**
     * Mengambil daftar karyawan dari API dan "meratakannya".
     */
    public function getEmployees(Request $request): array
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return ['data' => [], 'meta' => []];

        $queryParams = [
            'page' => $request->input('page', 1),
            'page_size' => 50,
            'search' => $request->input('search', ''),
            'ordering' => $this->getSortParameter($request->input('sort_by'), $request->input('sort_dir')),
        ];
        
        $response = $httpClient->get("{$this->baseUrl}/personnel/api/employees/", array_filter($queryParams));

        if (!$response->successful()) {
            return ['data' => [], 'meta' => []];
        }

        $paginatedData = $response->json();
        //dd($paginatedData); 

        $employees = $paginatedData['data'] ?? $paginatedData['results'] ?? [];

        $enrichedEmployees = array_map([$this, 'flattenEmployeeData'], $employees);

        return [
            'data' => $enrichedEmployees,
            'meta' => [
                'total' => $paginatedData['count'] ?? 0,
                'per_page' => 50,
                'current_page' => $queryParams['page'],
            ]
        ];
    }

    private function flattenEmployeeData(array $employee): array
    {
        $employee['department_id'] = $employee['department']['id'] ?? null;
        $employee['department_name'] = $employee['department']['dept_name'] ?? 'N/A';
        $employee['position_id'] = $employee['position']['id'] ?? null;
        $employee['position_name'] = $employee['position']['position_name'] ?? 'N/A';
        return $employee;
    }
    
    public function getBioPhoto(string $empCode)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return null;

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/bio_photo/", ['employee__emp_code' => $empCode]);
        
        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results');
             if (!empty($results) && isset($results[0])) {
                return $results[0]['register_photo'] ?? null;
            }
        }
        return null;
    }

    public function pushTransaction(array $transactionData): bool
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) {
            return false;
        }

        $endpoint = "{$this->baseUrl}/iclock/api/transactions/";
        Log::info('Mengirim data absensi ke API ZKTeco:', $transactionData);
        $response = $httpClient->post($endpoint, $transactionData);

        if ($response->successful() && $response->status() === 201) {
            Log::info('Berhasil mengirim data absensi. Response:', $response->json());
            return true;
        }
        
        $errorBody = $response->json() ?? [];
        $statusCode = $response->status();
        $detailedMessage = 'Tidak ada detail error spesifik dari API.';

        // ... (Kode logging error Anda yang lengkap) ...

        Log::error('Gagal mengirim data absensi ke ZKTeco API.', [
            'status' => $statusCode,
            'pesan' => $detailedMessage,
            'response_body' => $errorBody
        ]);

        return false;
    }

    public function getBioPhotos(Request $request): array
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return ['data' => [], 'meta' => []];

        $queryParams = [
            'page' => $request->input('page', 1),
            'page_size' => 15,
        ];

        $search = $request->input('search', '');
        if (!empty($search)) {
            $queryParams['search'] = $search;
        }
        
        $response = $httpClient->get("{$this->baseUrl}/iclock/api/bio_photo/", array_filter($queryParams));

        if (!$response->successful()) {
            return ['data' => [], 'meta' => []];
        }

        $paginatedData = $response->json();
        $photos = $paginatedData['data'] ?? $paginatedData['results'] ?? [];

        return [
            'data' => $photos,
            'meta' => [
                'total' => $paginatedData['count'] ?? 0,
                'per_page' => 15,
                'current_page' => $queryParams['page'],
            ]
        ];
    }


     public function getBioPhotoRecord(string $empCode)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return null;

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/bio_photo/", ['employee__emp_code' => $empCode]);
        
        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results');
             if (!empty($results) && isset($results[0])) {
                return $results[0];
            }
        }
        return null;
    }

    public function getBioPhotoRecordByName(string $empCode, string $firstName)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return null;

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/bio_photo/", ['employee__emp_code' => $empCode]);
        
        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results');
            
            if (!empty($results)) {
                foreach ($results as $record) {
                    if (isset($record['first_name']) && strtolower($record['first_name']) === strtolower($firstName)) {
                        return $record;
                    }
                }
            }
        }
        
        return null;
    }

    public function getRawImageData(string $filePath): ?string
    {
        $httpClient = Http::withHeaders(['Authorization' => $this->token]);
        $fullImageUrl = $this->baseUrl . '/auth_files/biophoto/' . $filePath; 
        $response = $httpClient->get($fullImageUrl);
        if ($response->successful()) {
            return $response->body();
        }
        return null;
    }
    
    public function getTerminals()
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return collect();

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/terminals/");

        if ($response->successful()) {
            $results = $response->json('data') ?? $response->json('results');
            return collect($results);
        }
        return collect();
    }

    public function getTerminalDetails(int $terminalId)
    {
        $httpClient = $this->makeRequest();
        if (!$httpClient) return null;

        $response = $httpClient->get("{$this->baseUrl}/iclock/api/terminals/{$terminalId}/");
        
        if ($response->successful()) {
            return $response->json('data') ?? $response->json();
        }
        return null;
    }
    
    private function getSortParameter($sortBy, $sortDir)
    {
        if (!$sortBy || !$sortDir) {
            return null;
        }

        $field = $sortBy;
        if ($sortBy === 'department') $field = 'department__name';
        if ($sortBy === 'position') $field = 'position__name';

        if ($sortDir === 'desc') {
            return '-' . $field;
        }
        return $field;
    }
}

