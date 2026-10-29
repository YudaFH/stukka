<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $date = $request->input('date');
        $employeeId = $request->input('user_id');
        $status = $request->input('status');

        $query = Attendance::with('user')->orderBy('date', 'desc')->orderBy('time_in', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($request->filled('month')) {
            $query->where('date', 'like', "{$month}%");
        } elseif ($request->filled('date')) {
            $query->where('date', $date);
        } else {
            // Default ke hari ini jika tidak ada filter waktu sama sekali
            $date = Carbon::today()->toDateString();
            $query->where('date', $date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $employeeId);
        }

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        $attendances = $query->paginate(20)->withQueryString();

        $employees = User::where('usertype', 'karyawan')->orderBy('name')->get();
        $settings = AttendanceSetting::getSettings();

        // Statistik hari ini
        $today = Carbon::today()->toDateString();
        $totalEmployees = $employees->count();
        $todayPresent = Attendance::where('date', $today)->count();
        $todayLate = Attendance::where('date', $today)->where('status', 'late')->count();
        $todayOnTime = Attendance::where('date', $today)->where('status', 'on_time')->count();

        return view('admin.attendances.index', compact(
            'attendances',
            'employees',
            'settings',
            'date',
            'startDate',
            'endDate',
            'month',
            'employeeId',
            'status',
            'totalEmployees',
            'todayPresent',
            'todayLate',
            'todayOnTime'
        ));
    }

    /**
     * Unduh Laporan Rekap Presensi dalam Format Excel (.xls)
     */
    public function exportExcel(Request $request)
    {
        $attendances = $this->getFilteredAttendances($request);
        $periodLabel = $this->getPeriodLabel($request);
        $filterUser = $request->filled('user_id') ? User::find($request->user_id) : null;
        $totalEmployees = User::where('usertype', 'karyawan')->count();

        $cleanPeriod = preg_replace('/[^A-Za-z0-9\-]/', '_', $periodLabel);
        $filename = 'Laporan-Presensi-Stukka-' . $cleanPeriod . '-' . date('Ymd_His') . '.xls';

        $content = view('admin.attendances.export_excel', compact(
            'attendances',
            'periodLabel',
            'filterUser',
            'totalEmployees'
        ))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Tampilan Laporan Rekap Presensi Cetak / Simpan PDF (A4 Print-Ready)
     */
    public function exportPdf(Request $request)
    {
        $attendances = $this->getFilteredAttendances($request);
        $periodLabel = $this->getPeriodLabel($request);
        $filterUser = $request->filled('user_id') ? User::find($request->user_id) : null;
        $totalEmployees = User::where('usertype', 'karyawan')->count();
        $totalHadir = $attendances->count();
        $totalOnTime = $attendances->where('status', 'on_time')->count();
        $totalLate = $attendances->where('status', 'late')->count();
        $totalOutside = $attendances->where('location_status_in', 'outside')->count();

        return view('admin.attendances.export_pdf', compact(
            'attendances',
            'periodLabel',
            'filterUser',
            'totalEmployees',
            'totalHadir',
            'totalOnTime',
            'totalLate',
            'totalOutside'
        ));
    }

    /**
     * Helper: Ambil data presensi terfilter (tanpa pagination) untuk ekspor
     */
    private function getFilteredAttendances(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $date = $request->input('date');
        $employeeId = $request->input('user_id');
        $status = $request->input('status');

        $query = Attendance::with('user')->orderBy('date', 'desc')->orderBy('time_in', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($request->filled('month')) {
            $query->where('date', 'like', "{$month}%");
        } elseif ($request->filled('date')) {
            $query->where('date', $date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $employeeId);
        }

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Helper: Buat label periode yang rapi
     */
    private function getPeriodLabel(Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            return Carbon::parse($request->start_date)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($request->end_date)->translatedFormat('d M Y');
        } elseif ($request->filled('month')) {
            return Carbon::parse($request->month . '-01')->translatedFormat('F Y');
        } elseif ($request->filled('date')) {
            return Carbon::parse($request->date)->translatedFormat('d F Y');
        }
        return 'Semua Data s/d ' . Carbon::today()->translatedFormat('d F Y');
    }

    /**
     * Halaman Khusus Pengaturan Jam & Aturan Absensi
     */
    public function settingsView()
    {
        $settings = AttendanceSetting::getSettings();
        return view('admin.attendances.settings', compact('settings'));
    }

    /**
     * Simpan Perubahan Pengaturan Jam Kerja & Aturan Absensi
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'check_in_start_time' => 'required',
            'work_start_time' => 'required',
            'late_tolerance_minutes' => 'required|integer|min:0|max:180',
            'check_in_end_time' => 'required',
            'check_out_start_time' => 'required',
            'work_end_time' => 'required',
            'check_out_end_time' => 'required',
            'work_days' => 'required|array|min:1',
            'min_face_match_score' => 'required|integer|min:40|max:95',
            'auto_submit_countdown' => 'required|integer|min:1|max:10',
            'office_name' => 'nullable|string|max:255',
            'office_latitude' => 'nullable|numeric',
            'office_longitude' => 'nullable|numeric',
            'office_radius_meters' => 'nullable|integer|min:10|max:10000',
        ]);

        $settings = AttendanceSetting::getSettings();
        $settings->update($validated);

        return back()->with('success', 'Seluruh aturan jam kerja dan parameter biometrik absensi berhasil disimpan!');
    }
}
