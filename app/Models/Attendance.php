<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'office_location_id',
        'type',
        'latitude',
        'longitude',
        'photo_path',
        'status',
    ];

    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model OfficeLocation
     */
    public function officeLocation(): BelongsTo
    {
        return $this->belongsTo(OfficeLocation::class);
    }
}

