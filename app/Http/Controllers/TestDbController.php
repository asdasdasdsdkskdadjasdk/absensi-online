<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class TestDbController extends Controller
{
    public function index(): View
    {
        try {
            $connection = DB::connection('pgsql_secondary');

            // Mengambil semua kolom dari transaksi dan menggabungkannya dengan nama karyawan
            $transactions = $connection->table('iclock_transaction as t')
                ->join('personnel_employee as e', 't.emp_id', '=', 'e.id')
                ->select(
                    't.*', // Ambil semua kolom dari tabel transaksi
                    DB::raw("TRIM(e.first_name || ' ' || e.last_name) as employee_name")
                )
                ->orderBy('t.punch_time', 'desc')
                ->paginate(50); // Menggunakan pagination agar tidak berat

            // PERBAIKAN: Menambahkan variabel $targetDate yang hilang
            return view('daily_report', [
                'transactions' => $transactions,
                'targetDate' => 'Keseluruhan Data' // Anda bisa ubah teks ini
            ]);

        } catch (\Exception $e) {
            dd("KONEKSI GAGAL. Error detail: ", $e->getMessage());
        }
    }
}

