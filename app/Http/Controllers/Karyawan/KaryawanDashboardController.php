<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $settings = AttendanceSetting::getSettings();

        // Statistik bulan berjalan
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $monthlyAttendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalPresent = $monthlyAttendances->count();
        $totalLate = $monthlyAttendances->where('status', 'late')->count();
        $totalOnTime = $monthlyAttendances->where('status', 'on_time')->count();

        // 7 Riwayat absensi terakhir
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->latest('date')
            ->take(7)
            ->get();

        return view('karyawan.dashboard', compact(
            'user',
            'todayAttendance',
            'settings',
            'totalPresent',
            'totalLate',
            'totalOnTime',
            'recentAttendances'
        ));
    }
}
