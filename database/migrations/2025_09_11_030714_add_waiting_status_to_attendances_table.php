<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan DB statement untuk mengubah ENUM agar lebih andal
        DB::statement("ALTER TABLE attendances CHANGE COLUMN status status ENUM('pending', 'approved', 'rejected', 'waiting') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke kondisi semula jika rollback
        DB::statement("ALTER TABLE attendances CHANGE COLUMN status status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};


