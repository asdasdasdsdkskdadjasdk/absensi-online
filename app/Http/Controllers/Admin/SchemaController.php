<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class SchemaController extends Controller
{
    /**
     * Menampilkan skema dari kedua database (default & secondary).
     */
    public function index()
    {
        // Inisialisasi struktur array yang konsisten
        $schemas = [
            'mysql' => [
                'name' => 'MySQL (db_absensi_online)',
                'tables' => [],
                'error' => null,
            ],
            'pgsql_secondary' => [
                'name' => 'PostgreSQL (ZKTeco)',
                'tables' => [],
                'error' => null,
            ]
        ];

        // 1. Coba ambil Skema Database Default (MySQL)
        try {
            // Menggunakan metode bawaan Laravel yang lebih sederhana
            $mysqlTables = Schema::connection('mysql')->getTableListing();
            
            foreach ($mysqlTables as $table) {
                $schemas['mysql']['tables'][$table] = Schema::connection('mysql')->getColumnListing($table);
            }
        } catch (\Exception $e) {
            Log::error("MySQL Schema Error: " . $e->getMessage());
            $schemas['mysql']['error'] = 'Gagal terhubung atau mengambil skema dari database MySQL: ' . $e->getMessage();
        }

        // 2. Coba ambil Skema Database Secondary (PostgreSQL)
        try {
            // Menggunakan metode bawaan Laravel yang lebih sederhana
            $pgsqlTables = Schema::connection('pgsql_secondary')->getTableListing();

            foreach ($pgsqlTables as $table) {
                $schemas['pgsql_secondary']['tables'][$table] = Schema::connection('pgsql_secondary')->getColumnListing($table);
            }
        } catch (\Exception $e) {
            Log::error("PostgreSQL Schema Error: " . $e->getMessage());
            $schemas['pgsql_secondary']['error'] = 'Gagal terhubung atau mengambil skema dari database PostgreSQL: ' . $e->getMessage();
        }
        
        return view('admin.schema.index', compact('schemas'));
    }
}

