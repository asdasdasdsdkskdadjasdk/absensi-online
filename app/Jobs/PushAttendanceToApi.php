<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Services\ZktecoApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushAttendanceToApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Attendance $attendance;

    /**
     * Create a new job instance.
     */
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(ZktecoApiService $zktecoApiService): void
    {
        try {
            $this->attendance->load(['user', 'officeLocation']);
            $user = $this->attendance->user;
            $officeLocation = $this->attendance->officeLocation;

            // 1. Ambil detail terminal dari API untuk mendapatkan Serial Number (SN)
            $terminalDetails = $zktecoApiService->getTerminalDetails($officeLocation->terminal_id);

            if (!$terminalDetails || !isset($terminalDetails['sn'])) {
                throw new \Exception("Terminal dengan ID {$officeLocation->terminal_id} tidak ditemukan di API atau tidak memiliki SN.");
            }

            // 2. Siapkan data transaksi sesuai format yang dibutuhkan API
            $dataToPush = [
                'emp_code'          => (string) $user->emp_code,
                'punch_time'        => $this->attendance->created_at->toIso8601String(),
                'punch_state'       => "0", // 0: Check-in, 1: Check-out, dll.
                'punch_state_display' => "Check In", // <-- TAMBAHAN
                'verify_type'       => "15", // 15: Wajah
                'verify_type_display' => "Face", // <-- TAMBAHAN
                'terminal_sn'       => (string) $terminalDetails['sn'],
                'terminal_alias'    => (string) $terminalDetails['alias'], // <-- TAMBAHAN
                'latitude'          => (string) $this->attendance->latitude,
                'longitude'         => (string) $this->attendance->longitude,
            ];

            // 3. Panggil service untuk mengirim data ke API
            $isSuccess = $zktecoApiService->pushTransaction($dataToPush);

            if ($isSuccess) {
                // 4. Jika pengiriman berhasil, HAPUS data dari database lokal (MySQL)
                $this->attendance->delete();
                Log::info("Absensi ID {$this->attendance->id} untuk {$user->name} berhasil dikirim ke API dan dihapus dari lokal.");
            } else {
                // Jika API mengembalikan status gagal, lemparkan exception agar job di-retry
                throw new \Exception("API ZKTeco menolak transaksi untuk absensi ID {$this->attendance->id}.");
            }

        } catch (\Exception $e) {
            Log::error("Gagal memproses job PushAttendanceToApi untuk absensi ID {$this->attendance->id}: " . $e->getMessage());
            // Menggagalkan job agar bisa di-retry secara otomatis oleh Laravel Queue
            $this->fail($e);
        }
    }
}