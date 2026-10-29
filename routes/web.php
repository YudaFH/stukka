<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminTestimonialController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Stukka Events)
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. HALAMAN PUBLIK (Bisa diakses siapa saja / Tamu)
// =========================================================================

// Halaman Utama (Home)
Route::get('/', function () {
    $featuredEvents = \App\Models\Event::where('is_featured', true)->latest()->take(3)->get();

    if ($featuredEvents->isEmpty()) {
        $featuredEvents = \App\Models\Event::latest()->take(3)->get();
    }

    $clients = \App\Models\Client::all();
    $testimonials = \App\Models\Testimonial::latest()->get();

    return view('pages.home', compact('featuredEvents', 'clients', 'testimonials'));
})->name('home');


// Halaman Portofolio
Route::get('/portfolio', [EventController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{id}', [EventController::class, 'show'])->name('portfolio.show');

// Halaman Services (Cek Kalender Ketersediaan)
// NOTE: Mengarah ke method 'services' untuk load data tanggal yang full
Route::get('/services', [BookingController::class, 'services'])->name('services');


// =========================================================================
// 2. DASHBOARD & PROFILE USER (Harus Login)
// =========================================================================

Route::middleware('auth')->group(function () {
    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // FLOW BOOKING (User harus login untuk isi form)
    Route::get('/booking/form', [BookingController::class, 'create'])->name('booking.create'); // Form Input
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store'); // Simpan Data

    // Universal Dashboard Router (Hanya untuk Admin dan Karyawan)
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }
        if ($user->isKaryawan()) {
            return redirect()->route('karyawan.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Redirect rute user dashboard lama ke rute dashboard utama
    Route::get('/my-dashboard', function () {
        return redirect()->route('dashboard');
    })->name('user.dashboard');
});


// =========================================================================
// 3. PORTAL KARYAWAN (Face Recognition & Absensi)
// =========================================================================
Route::middleware(['auth', 'karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Karyawan\KaryawanDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance/scan', [\App\Http\Controllers\Karyawan\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/attendance/record', [\App\Http\Controllers\Karyawan\AttendanceController::class, 'record'])->name('attendance.record');
    Route::get('/attendance/history', [\App\Http\Controllers\Karyawan\AttendanceController::class, 'history'])->name('attendance.history');

    // Perekaman Master Biometrik Wajah
    Route::get('/face/register', [\App\Http\Controllers\Karyawan\AttendanceController::class, 'registerFaceView'])->name('face.register');
    Route::post('/face/store', [\App\Http\Controllers\Karyawan\AttendanceController::class, 'storeFace'])->name('face.store');

    // Jadwal Event Karyawan
    Route::get('/events', [\App\Http\Controllers\Karyawan\KaryawanEventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}', [\App\Http\Controllers\Karyawan\KaryawanEventController::class, 'show'])->name('events.show');
});


// =========================================================================
// 4. ADMIN CMS (Full Akses Admin)
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // A. DASHBOARD UTAMA ADMIN (Command Center)
    Route::get('/', function () {
        // 1. Data Portofolio & Konten Publik
        $totalProjects = \App\Models\Event::count();
        $totalClients = \App\Models\Client::count();
        $totalTestimonials = \App\Models\Testimonial::count();

        // 2. Operasional Event & Kru
        $activeEventsCount = \App\Models\ScheduledEvent::whereIn('status', ['scheduled', 'ongoing'])->count();
        $upcomingEvents = \App\Models\ScheduledEvent::with('crew')
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        // 3. Keuangan Event
        $allScheduledEvents = \App\Models\ScheduledEvent::with(['payments', 'expenses'])->get();
        $totalContracts = $allScheduledEvents->sum('contract_amount');
        $totalReceived = $allScheduledEvents->sum('total_paid');
        $totalReceivable = $allScheduledEvents->sum('remaining_receivable');
        $totalExpenses = $allScheduledEvents->sum('total_expense');
        $totalUnpaidExpenses = $allScheduledEvents->sum('remaining_expense');
        $totalNetProfit = $allScheduledEvents->sum('net_profit');
        $globalMargin = $totalContracts > 0 ? round(($totalNetProfit / $totalContracts) * 100, 1) : 0;

        // 4. Presensi Karyawan Hari Ini
        $today = \Carbon\Carbon::today()->toDateString();
        $totalEmployees = \App\Models\User::where('usertype', 'karyawan')->count();
        $todayAttendances = \App\Models\Attendance::with('user')
            ->where('date', $today)
            ->get();
        $presentCount = $todayAttendances->count();
        $onTimeCount = $todayAttendances->where('status', 'on_time')->count();
        $lateCount = $todayAttendances->where('status', 'late')->count();
        $absentCount = max(0, $totalEmployees - $presentCount);
        $recentAttendances = $todayAttendances->sortByDesc('time_in')->take(4);

        return view('admin.dashboard', compact(
            'totalProjects',
            'totalClients',
            'totalTestimonials',
            'activeEventsCount',
            'upcomingEvents',
            'totalContracts',
            'totalReceived',
            'totalReceivable',
            'totalExpenses',
            'totalUnpaidExpenses',
            'totalNetProfit',
            'globalMargin',
            'totalEmployees',
            'presentCount',
            'onTimeCount',
            'lateCount',
            'absentCount',
            'recentAttendances'
        ));
    })->name('index');

    // B. KELOLA PORTOFOLIO (Resource)
    Route::resource('events', AdminEventController::class);

    // C. KELOLA CLIENT TRUST (Logo)
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
    Route::delete('/clients/{id}', [AdminClientController::class, 'destroy'])->name('clients.destroy');

    // D. KELOLA TESTIMONI
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::delete('/testimonials/{id}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // E. KELOLA BOOKING (Dialihkan ke Jadwal Event & Kru)
    Route::get('/bookings', function () {
        return redirect()->route('admin.scheduled-events.index');
    })->name('bookings.index');

    // F. KELOLA KARYAWAN
    Route::resource('employees', \App\Http\Controllers\Admin\AdminEmployeeController::class);
    Route::post('/employees/{id}/reset-face', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'resetFace'])->name('employees.reset-face');

    // G. REKAP ABSENSI & PENGATURAN
    Route::get('/attendances/export/excel', [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'exportExcel'])->name('attendances.export.excel');
    Route::get('/attendances/export/pdf', [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'exportPdf'])->name('attendances.export.pdf');
    Route::get('/attendances', [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/settings', [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'settingsView'])->name('attendances.settings.view');
    Route::post('/attendances/settings', [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'updateSettings'])->name('attendances.settings');

    // H. JADWAL EVENT OPERASIONAL & KRU
    Route::get('/scheduled-events/export/excel', [\App\Http\Controllers\Admin\AdminScheduledEventController::class, 'exportExcel'])->name('scheduled-events.export.excel');
    Route::get('/scheduled-events/export/pdf', [\App\Http\Controllers\Admin\AdminScheduledEventController::class, 'exportPdf'])->name('scheduled-events.export.pdf');
    Route::get('/scheduled-events/{id}/print', [\App\Http\Controllers\Admin\AdminScheduledEventController::class, 'printSingleEvent'])->name('scheduled-events.print');
    Route::get('/scheduled-events/convert/{bookingId}', [\App\Http\Controllers\Admin\AdminScheduledEventController::class, 'convertFromBooking'])->name('scheduled-events.convert');
    Route::resource('scheduled-events', \App\Http\Controllers\Admin\AdminScheduledEventController::class);

    // I. PERHITUNGAN KEUANGAN PER EVENT (Biaya Produksi Vendor & Pembayaran Klien)
    Route::get('/finance', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/events/{eventId}/payment', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'storePayment'])->name('finance.payment.store');
    Route::delete('/finance/payments/{id}', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'destroyPayment'])->name('finance.payment.destroy');
    Route::post('/finance/events/{eventId}/expense', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'storeExpense'])->name('finance.expense.store');
    Route::post('/finance/expenses/{id}/toggle-status', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'toggleExpenseStatus'])->name('finance.expense.toggle-status');
    Route::delete('/finance/expenses/{id}', [\App\Http\Controllers\Admin\AdminEventFinanceController::class, 'destroyExpense'])->name('finance.expense.destroy');
});


// =========================================================================
// 5. AUTHENTICATION
// =========================================================================
require __DIR__ . '/auth.php';
