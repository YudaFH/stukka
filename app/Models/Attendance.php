<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'photo_in',
        'photo_out',
        'latitude_in',
        'longitude_in',
        'location_name_in',
        'location_status_in',
        'latitude_out',
        'longitude_out',
        'location_name_out',
        'location_status_out',
        'status',
        'face_match_score',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'face_match_score' => 'float',
            'latitude_in' => 'float',
            'longitude_in' => 'float',
            'latitude_out' => 'float',
            'longitude_out' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
