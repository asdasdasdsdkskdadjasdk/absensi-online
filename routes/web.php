<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\SchemaController;
use App\Http\Controllers\Admin\BioPhotoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApiSettingController; // <-- INI YANG BENAR


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');    
});

// Rute untuk pengguna yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.approvals.index');
        } else {
            return redirect()->route('absensi.create');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/absensi', [AttendanceController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');

    Route::get('/test-api', [AttendanceController::class, 'testApiConnection']);

});


// === SEMUA RUTE ADMIN DIGABUNG DI SINI ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute untuk CRUD Lokasi Kantor
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/{terminal_id}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{terminal_id}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{terminal_id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    // Rute untuk CRUD Users
    Route::resource('users', UserController::class);



    // Rute untuk Persetujuan Absensi
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/waiting', [ApprovalController::class, 'historyWaiting'])->name('approvals.history_waiting'); // RUTE BARU DITAMBAHKAN
    Route::get('/approvals/rejected', [ApprovalController::class, 'historyRejected'])->name('approvals.history_rejected');
    Route::get('/approvals/approved', [ApprovalController::class, 'historyApproved'])->name('approvals.history_approved');
    Route::post('/approvals/{attendance}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{attendance}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    Route::get('/approvals/{attendance}/edit', [ApprovalController::class, 'edit'])->name('approvals.edit');
    Route::put('/approvals/{attendance}', [ApprovalController::class, 'update'])->name('approvals.update');
    Route::delete('/approvals/{attendance}', [ApprovalController::class, 'destroy'])->name('approvals.destroy');
    Route::get('/approvals/download/export/{filename}', [ApprovalController::class, 'downloadExportedFile'])->name('approvals.download_export');


    Route::get('/approvals/test-filters', [App\Http\Controllers\Admin\ApprovalController::class, 'testApiFilters'])->name('approvals.test_filters');

    // UBAH/TAMBAHKAN ROUTE INI (gunakan POST)
    Route::post('/approvals/approved/export', [ApprovalController::class, 'exportApproved'])->name('approvals.export');

    // Rute untuk Data Karyawan
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/api', [ApiSettingController::class, 'index'])->name('api.index');
    Route::post('/api/refresh', [ApiSettingController::class, 'refreshToken'])->name('api.refresh');

    // Rute untuk Melihat Skema DB
    Route::get('/schema', [SchemaController::class, 'index'])->name('schema.index');

    // Rute untuk Foto Registrasi Karyawan (BioPhoto)
Route::get('/biophotos', [BioPhotoController::class, 'index'])->name('biophotos.index');
});


require __DIR__.'/auth.php';
