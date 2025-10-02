<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Nama pengaturan, e.g., 'api_token'
            $table->text('value')->nullable(); // Nilai pengaturan
            $table->timestamps();
        });

        // Masukkan token awal saat migrasi dijalankan
        DB::table('settings')->insert([
            'key' => 'api_token',
            'value' => Str::random(60), // Membuat token acak pertama kali
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
