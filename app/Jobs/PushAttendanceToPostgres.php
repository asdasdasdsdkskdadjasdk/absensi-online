<?php

namespace App\Jobs;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PushAttendanceToPostgres implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Menyimpan ID agar job lebih ringan
    protected int $attendanceId;

    public function __construct(Attendance $attendance)
    {
        $this->attendanceId = $attendance->id;
    }

    public function handle(): void
    {
        // Ambil data absensi terbaru dari database MySQL
        $attendance = Attendance::with(['user', 'officeLocation'])->find($this->attendanceId);

        // Jika data sudah dihapus oleh proses lain, hentikan job
        if (!$attendance) {
            Log::warning("Job PushAttendanceToPostgres dibatalkan karena data Absensi ID {$this->attendanceId} sudah tidak ada.");
            return;
        }

        try {
            $user = $attendance->user;
            $officeLocation = $attendance->officeLocation;

            // Pastikan relasi user dan officeLocation ada
            if (!$user || !$officeLocation) {
                throw new \Exception("Relasi User atau OfficeLocation tidak ditemukan untuk Absensi ID {$this->attendanceId}.");
            }

            // Gunakan koneksi 'pgsql_secondary' yang sudah kita definisikan
            $pgsql = DB::connection('pgsql_secondary');

            // 1. Ambil detail karyawan dari PostgreSQL
            $employeePg = $pgsql->table('personnel_employee')
                ->where('emp_code', $user->emp_code)->first();

            // 2. Ambil detail terminal dari PostgreSQL
            $terminalPg = $pgsql->table('iclock_terminal')
                ->where('id', $officeLocation->terminal_id)->first();
            
            if (!$employeePg || !$terminalPg) {
                throw new \Exception("Data Karyawan (emp_code: {$user->emp_code}) atau Terminal (id: {$officeLocation->terminal_id}) tidak ditemukan di PostgreSQL.");
            }

            // 3. Siapkan data untuk dimasukkan ke tabel iclock_transaction
            $dataToInsert = [
                'emp_id'         => $employeePg->id,
                'emp_code'       => $user->emp_code,
                'punch_time'     => $attendance->created_at,
                'punch_state'    => 0,
                'verify_type'    => 15,
                'work_code'      => '0',
                'terminal_id'    => $terminalPg->id,
                'terminal_sn'    => $terminalPg->sn,
                'terminal_alias' => $terminalPg->alias,
                'area_alias'     => 'All Area',
                // --- PERUBAHAN DI SINI ---
                'latitude'       => $attendance->latitude,  // Mengambil nilai asli dari absensi
                'longitude'      => $attendance->longitude, // Mengambil nilai asli dari absensi
                // -------------------------
                'is_attendance'  => 1,
                'upload_time'    => now(),
                'source'         => 1,
                'purpose'        => 9,
                'crc'            => null,
                'is_mask'        => 0,
                'temperature'    => 0.0,
            ];

            // 4. Masukkan data ke PostgreSQL
            $pgsql->table('iclock_transaction')->insert($dataToInsert);
            
            // 5. Jika berhasil, HAPUS data dari database lokal (MySQL)
            $attendance->delete();

            Log::info("Absensi ID {$this->attendanceId} untuk {$user->name} berhasil dikirim ke PostgreSQL dan dihapus dari lokal.");

        } catch (Throwable $e) {
            Log::error("Gagal mengirim absensi ID {$this->attendanceId} ke PostgreSQL: " . $e->getMessage());
            // Menggagalkan job agar bisa di-retry secara otomatis oleh queue worker
            $this->fail($e);
        }
    }
}