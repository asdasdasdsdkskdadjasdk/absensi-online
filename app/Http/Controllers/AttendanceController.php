<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Jobs\PushAttendanceToPostgres;
use App\Jobs\PushAttendanceToApi;
use App\Services\ZktecoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    protected $zktecoApiService;

    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    /**
     * Menampilkan halaman absensi dengan data dari API.
     */
   public function create(): View
    {
        $user = Auth::user();
        
        $departmentName = 'Departemen Tidak Ditemukan';
        $positionName = 'Posisi Tidak Ditemukan';
        $referencePhoto = null;
        $apiFirstName = null; // Definisikan variabel di awal


        if ($user && !$user->is_admin && !empty(trim($user->emp_code))) {
            $empCode = trim($user->emp_code);
            
            $employeeDetails = $this->zktecoApiService->getEmployeeDetails($empCode);
            if ($employeeDetails) {
                // Ambil department dan position saja
                $departmentName = $employeeDetails['department_name'] ?? $departmentName;
                $positionName = $employeeDetails['position_name'] ?? $positionName;
            }
            
            // --- LOGIKA PENGAMBILAN FOTO DIPERBARUI ---
            
            // Ambil nama pengguna dari Auth
            $userName = $user->name; 
            
            // Panggil metode yang sudah benar dengan parameter yang benar ($empCode dan $userName)
            $bioPhotoData = $this->zktecoApiService->getBioPhotoRecordByName($empCode, $userName);


        // --- TAMBAHKAN BAGIAN INI ---
        // Ambil nama depan dari data API untuk ditampilkan di console
        if ($bioPhotoData && isset($bioPhotoData['first_name'])) {
            $apiFirstName = $bioPhotoData['first_name'];
        } else {
            $apiFirstName = 'NAMA TIDAK DITEMUKAN DI API'; // Pesan jika data tidak ada
        }

            $photoPath = "1/" . $empCode . ".jpg";

            if ($photoPath) {
                $imageData = $this->zktecoApiService->getRawImageData($photoPath);
                if ($imageData) {
                    $referencePhoto = base64_encode($imageData);
                }
            }
            // ---------------------------------------------
        }
        
        $locations = OfficeLocation::all();

        return view('absensi', [
            'locations' => $locations, 
            'user' => $user,
            'departmentName' => $departmentName,
            'positionName' => $positionName,
            'referencePhoto' => $referencePhoto,
            'apiFirstName' => $apiFirstName, // Kirim nama dari API ke view
        ]);
    }
    /**
     * Menyimpan data absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:kantor,dinas',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image',
            'office_id' => 'required|exists:office_locations,id' 
        ]);

        $user = Auth::user();
        $type = $request->type;
        $officeLocation = OfficeLocation::find($request->office_id);
        
        if (!$officeLocation) {
             return response()->json(['success' => false, 'message' => 'Lokasi kantor yang dipilih tidak valid.']);
        }

        $status = 'pending';

        if ($type === 'kantor') {
            $distance = $this->haversine($request->latitude, $request->longitude, $officeLocation->latitude, $officeLocation->longitude);
            if ($distance > $officeLocation->radius_meters) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Anda berada di luar radius kantor yang diizinkan.'
                ]);
            }
            $status = 'waiting';
        }

        $path = $request->file('photo')->store('attendances', 'public');

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'office_location_id' => $officeLocation->id,
            'type' => $type,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo_path' => $path,
            'status' => $status,
        ]);
        
        $message = 'Absensi dinas berhasil direkam dan menunggu persetujuan.';
        if ($attendance->status === 'waiting') {
            //PushAttendanceToPostgres::dispatch($attendance);
            PushAttendanceToPostgres::dispatch($attendance);
            $message = 'Absensi kantor berhasil, data sedang disinkronisasi.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Fungsi Haversine.
     */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}

