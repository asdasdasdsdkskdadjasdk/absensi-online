<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'emp_code',
        'email',
        'password',
        'is_admin',
        'device_id',
        'department_id',
        'position_id',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendapatkan atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // Memastikan is_admin selalu true/false
        ];
    }

    /**
     * Mendefinisikan relasi "satu ke banyak" dengan model Attendance.
     * Seorang user bisa memiliki banyak data absensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

