<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman Scan Wajah untuk Absensi Masuk / Pulang
     */
    public function scan()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $settings = AttendanceSetting::getSettings();
        $currentTime = Carbon::now()->format('H:i:s');
        $isWorkingDay = $settings->isWorkingDay();

        $hasCheckedIn = !empty($todayAttendance?->time_in);
        $hasCheckedOut = !empty($todayAttendance?->time_out);

        // Menentukan status ketersediaan presensi secara presisi
        // Selalu buka kamera agar karyawan yang telat, lembur larut malam, atau bertugas di hari libur tetap bisa absen
        $attendanceState = 'ready'; // 'completed' atau 'ready'
        $activeMode = 'in'; // 'in' atau 'out'
        $stateMessage = '';

        if ($hasCheckedIn && $hasCheckedOut) {
            $attendanceState = 'completed';
            $stateMessage = 'Presensi Anda hari ini sudah lengkap! Anda sudah absen Masuk (' . substr($todayAttendance->time_in, 0, 5) . ' WIB) dan Pulang (' . substr($todayAttendance->time_out, 0, 5) . ' WIB). Sampai jumpa besok!';
        } elseif (!$hasCheckedIn) {
            // Belum absen masuk -> Selalu bisa absen masuk kapan pun
            $attendanceState = 'ready';
            $activeMode = 'in';
            if (!$isWorkingDay) {
                $stateMessage = 'Presensi di luar hari kerja operasional (Lembur / Tugas Khusus). Presensi masuk tetap dibuka.';
            }
        } else {
            // Sudah absen masuk, belum absen pulang -> Selalu bisa absen pulang kapan pun selesai lembur/tugas
            $attendanceState = 'ready';
            $activeMode = 'out';
            if (!$isWorkingDay) {
                $stateMessage = 'Presensi pulang (Lembur Hari Libur).';
            }
        }

        return view('karyawan.attendance.scan', compact(
            'user', 
            'todayAttendance', 
            'settings',
            'isWorkingDay',
            'hasCheckedIn',
            'hasCheckedOut',
            'attendanceState',
            'activeMode',
            'stateMessage'
        ));
    }

    /**
     * Rekam data absensi (Masuk atau Pulang) via Ajax/Fetch
     */
    public function record(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'image' => 'required|string', // base64
            'face_match_score' => 'nullable|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_name' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ], [
            'latitude.required' => 'Akses lokasi (GPS) wajib diaktifkan pada browser/perangkat Anda.',
            'longitude.required' => 'Akses lokasi (GPS) wajib diaktifkan pada browser/perangkat Anda.',
        ]);

        $user = Auth::user();

        if (!$user->hasRegisteredFace()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mendaftarkan wajah master! Silakan registrasi wajah terlebih dahulu.'
            ], 422);
        }

        $settings = AttendanceSetting::getSettings();
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');
        $isWorkingDay = $settings->isWorkingDay();

        // 1. Cek Skor Kemiripan Wajah Minimal
        if ($request->filled('face_match_score') && $request->face_match_score < $settings->min_face_match_score) {
            return response()->json([
                'success' => false,
                'message' => 'Akurasi wajah (' . round($request->face_match_score) . '%) di bawah standar minimal (' . $settings->min_face_match_score . '%). Arahkan wajah lebih jelas ke kamera.'
            ], 422);
        }

        // 2. Evaluasi Geolokasi (Di Kantor vs Di Luar Kantor)
        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $distance = $settings->calculateDistanceMeters($lat, $lng);
        $isWithinOffice = $settings->isWithinOfficeRadius($lat, $lng);
        $locationStatus = $isWithinOffice ? 'office' : 'outside';

        $locationName = $request->location_name;
        if (empty($locationName)) {
            $locationName = $isWithinOffice 
                ? ($settings->office_name ?? 'Kantor Stukka Digital') 
                : "Koordinat: {$lat}, {$lng} (±" . round($distance) . "m dari kantor)";
        }

        // 3. Decode dan simpan gambar snapshot base64
        $imageData = $request->input('image');
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json(['success' => false, 'message' => 'Format snapshot foto tidak valid!'], 422);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Format snapshot tidak dikenali!'], 422);
        }

        $folder = 'attendances/' . date('Y-m');
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        $filename = $request->type . '_' . $user->id . '_' . time() . '.' . ($type ?? 'jpg');
        $filePath = $folder . '/' . $filename;
        Storage::disk('public')->put($filePath, $imageData);

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        $locationBadge = $isWithinOffice ? '🏢 Di Kantor' : '📍 Di Luar Kantor';

        if ($request->type === 'in') {
            if ($attendance->time_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi masuk hari ini pada pukul ' . substr($attendance->time_in, 0, 5) . ' WIB.'
                ], 422);
            }

            // Hitung status: on_time, late, atau overtime (hari libur)
            if (!$isWorkingDay) {
                $status = 'overtime';
                $statusLabel = 'LEMBUR / HARI LIBUR';
            } else {
                $workStart = Carbon::createFromTimeString($settings->work_start_time);
                $lateLimit = $workStart->copy()->addMinutes($settings->late_tolerance_minutes);

                if ($now->greaterThan($lateLimit)) {
                    $status = 'late';
                    $statusLabel = 'TERLAMBAT';
                } else {
                    $status = 'on_time';
                    $statusLabel = 'TEPAT WAKTU';
                }
            }

            $attendance->time_in = $currentTime;
            $attendance->photo_in = $filePath;
            $attendance->latitude_in = $lat;
            $attendance->longitude_in = $lng;
            $attendance->location_name_in = $locationName;
            $attendance->location_status_in = $locationStatus;
            $attendance->status = $status;
            $attendance->face_match_score = $request->face_match_score;

            if ($request->filled('notes')) {
                $attendance->notes = $request->notes;
            } elseif (!$isWithinOffice) {
                $attendance->notes = 'Absen di Luar Kantor (Jarak: ' . round($distance) . 'm)';
            } elseif (!$isWorkingDay) {
                $attendance->notes = 'Presensi di luar hari kerja operasional (Lembur / Tugas Khusus)';
            }
            $attendance->save();

            $msg = "Presensi Masuk Berhasil! Status: {$statusLabel} • {$locationBadge} (Pukul " . substr($currentTime, 0, 5) . " WIB).";

            session()->flash('success', $msg);

            return response()->json([
                'success' => true,
                'message' => $msg,
                'status' => $status,
                'location_status' => $locationStatus,
                'location_name' => $locationName,
                'distance_meters' => round($distance),
                'user_name' => $user->name,
                'time_formatted' => substr($currentTime, 0, 5) . ' WIB',
                'type' => 'in',
                'attendance' => $attendance,
            ]);

        } else { // type == 'out'
            if (!$attendance->time_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum melakukan absensi masuk hari ini!'
                ], 422);
            }

            if ($attendance->time_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi pulang hari ini pada pukul ' . substr($attendance->time_out, 0, 5) . ' WIB.'
                ], 422);
            }

            $attendance->time_out = $currentTime;
            $attendance->photo_out = $filePath;
            $attendance->latitude_out = $lat;
            $attendance->longitude_out = $lng;
            $attendance->location_name_out = $locationName;
            $attendance->location_status_out = $locationStatus;

            if (!$isWithinOffice && empty($attendance->notes)) {
                $attendance->notes = 'Absen Pulang di Luar Kantor (Jarak: ' . round($distance) . 'm)';
            }

            $attendance->save();

            $msg = "Presensi Pulang Berhasil! {$locationBadge} • Tercatat pukul " . substr($currentTime, 0, 5) . " WIB. Terima kasih atas kerja keras Anda.";
            session()->flash('success', $msg);

            return response()->json([
                'success' => true,
                'message' => $msg,
                'status' => $attendance->status ?? 'present',
                'location_status' => $locationStatus,
                'location_name' => $locationName,
                'distance_meters' => round($distance),
                'user_name' => $user->name,
                'time_formatted' => substr($currentTime, 0, 5) . ' WIB',
                'type' => 'out',
                'attendance' => $attendance,
            ]);
        }
    }

    /**
     * Tampilkan halaman Perekaman / Registrasi Wajah Master
     */
    public function registerFaceView()
    {
        $user = Auth::user();

        // Perekaman wajah hanya dilakukan satu kali, kecuali jika di-reset oleh Admin
        if ($user->hasRegisteredFace()) {
            return redirect()->route('karyawan.dashboard')
                ->with('error', 'Wajah biometrik Anda sudah terdaftar. Hubungi Admin jika Anda memerlukan perekaman ulang.');
        }

        return view('karyawan.face.register', compact('user'));
    }

    /**
     * Simpan data wajah master & embedding descriptor
     */
    public function storeFace(Request $request)
    {
        $request->validate([
            'face_descriptor' => 'required',
            'image' => 'required|string',
        ]);

        $user = Auth::user();

        // Cegah overwrite jika wajah sudah terdaftar
        if ($user->hasRegisteredFace()) {
            return response()->json([
                'success' => false,
                'message' => 'Wajah biometrik Anda sudah terdaftar. Hubungi Admin jika Anda memerlukan perekaman ulang.'
            ], 403);
        }

        $descriptor = is_string($request->face_descriptor) 
            ? json_decode($request->face_descriptor, true) 
            : $request->face_descriptor;

        if (!is_array($descriptor) || count($descriptor) !== 128) {
            return response()->json([
                'success' => false,
                'message' => 'Data biometrik tidak valid. Pastikan wajah tegak dan pencahayaan terang.'
            ], 422);
        }

        $imageData = $request->input('image');
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]);
            $imageData = base64_decode($imageData);
        }

        $folder = 'faces';
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        $filename = 'master_' . $user->id . '_' . time() . '.' . ($type ?? 'jpg');
        $filePath = $folder . '/' . $filename;
        Storage::disk('public')->put($filePath, $imageData);

        $user->face_descriptor = json_encode($descriptor);
        $user->face_photo = $filePath;
        $user->face_registered_at = Carbon::now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Master Biometrik Wajah berhasil disimpan! Anda sekarang siap melakukan presensi.',
            'redirect' => route('karyawan.attendance.scan')
        ]);
    }

    /**
     * Riwayat Absensi Karyawan
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('karyawan.attendance.history', compact('user', 'attendances', 'month', 'year'));
    }
}
