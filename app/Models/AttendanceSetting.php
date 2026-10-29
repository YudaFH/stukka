<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_start_time',
        'work_start_time',
        'late_tolerance_minutes',
        'check_in_end_time',
        'check_out_start_time',
        'work_end_time',
        'check_out_end_time',
        'work_days',
        'min_face_match_score',
        'auto_submit_countdown',
        'office_name',
        'office_latitude',
        'office_longitude',
        'office_radius_meters',
    ];

    protected function casts(): array
    {
        return [
            'work_days' => 'array',
            'late_tolerance_minutes' => 'integer',
            'min_face_match_score' => 'integer',
            'auto_submit_countdown' => 'integer',
            'office_latitude' => 'float',
            'office_longitude' => 'float',
            'office_radius_meters' => 'integer',
        ];
    }

    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'check_in_start_time' => '06:00:00',
            'work_start_time' => '08:00:00',
            'late_tolerance_minutes' => 15,
            'check_in_end_time' => '11:00:00',
            'check_out_start_time' => '16:00:00',
            'work_end_time' => '17:00:00',
            'check_out_end_time' => '23:59:00',
            'work_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'min_face_match_score' => 60,
            'auto_submit_countdown' => 2,
            'office_name' => 'Kantor Stukka Digital',
            'office_latitude' => -7.25747190,
            'office_longitude' => 112.75208830,
            'office_radius_meters' => 150,
        ]);
    }

    /**
     * Hitung jarak dari koordinat lokasi ke kantor (dalam meter menggunakan Haversine)
     */
    public function calculateDistanceMeters(?float $lat, ?float $lng): float
    {
        if ($lat === null || $lng === null) {
            return 9999999;
        }

        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat - $this->office_latitude);
        $dLng = deg2rad($lng - $this->office_longitude);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($this->office_latitude)) * cos(deg2rad($lat)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 1);
    }

    /**
     * Cek apakah koordinat berada di dalam radius toleransi kantor
     */
    public function isWithinOfficeRadius(?float $lat, ?float $lng): bool
    {
        return $this->calculateDistanceMeters($lat, $lng) <= ($this->office_radius_meters ?? 150);
    }

    /**
     * Cek apakah hari ini merupakan hari kerja aktif
     */
    public function isWorkingDay(?string $dayName = null): bool
    {
        $day = strtolower($dayName ?? now()->format('l'));
        $days = is_array($this->work_days) ? $this->work_days : ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        return in_array($day, $days, true);
    }
}
