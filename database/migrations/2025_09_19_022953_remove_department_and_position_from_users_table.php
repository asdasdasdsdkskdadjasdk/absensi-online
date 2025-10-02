<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom yang tidak lagi kita perlukan
            $table->dropColumn(['department_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kembali kolom jika migrasi di-rollback
            $table->string('department_id')->nullable();
            $table->string('position_id')->nullable();
        });
    }
};
