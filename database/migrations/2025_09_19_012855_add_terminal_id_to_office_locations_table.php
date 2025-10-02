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
        Schema::table('office_locations', function (Blueprint $table) {
            // Menambahkan kolom 'terminal_id' setelah kolom 'id'
            // Kolom ini akan menyimpan ID dari terminal yang ada di API.
            $table->unsignedBigInteger('terminal_id')->after('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office_locations', function (Blueprint $table) {
            $table->dropColumn('terminal_id');
        });
    }
};
