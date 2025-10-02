<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'terminal_id', // <-- Menambahkan ini ke daftar izin
        'latitude',
        'longitude',
        'radius_meters',
    ];

    /**
     * Mendefinisikan relasi "one-to-many" ke model Attendance.
     * Satu lokasi kantor bisa memiliki banyak catatan absensi.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

