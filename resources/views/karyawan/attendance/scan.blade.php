@extends('layouts.karyawan')

@section('title', 'Presensi Biometrik Wajah & Lokasi')

@push('styles')
<style>
    /* Animasi Laser Scanner Vertikal */
    @keyframes laserScan {
        0% { top: 8%; opacity: 0.3; }
        50% { top: 88%; opacity: 1; }
        100% { top: 8%; opacity: 0.3; }
    }
    .laser-line {
        position: absolute;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #00F0FF;
        box-shadow: 0 0 15px #00F0FF, 0 0 30px #00F0FF;
        border-radius: 50%;
        animation: laserScan 2.4s ease-in-out infinite;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Presensi Pengenalan Wajah & Lokasi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pastikan GPS aktif. Sistem akan memverifikasi biometrik wajah serta titik lokasi kerja Anda.
            </p>
        </div>
        <a href="{{ route('karyawan.dashboard') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all whitespace-nowrap shrink-0 self-start sm:self-auto shadow-xs">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
            <span>Dashboard</span>
        </a>
    </div>

    {{-- Alert jika belum merekam wajah master --}}
    @if(!$user->hasRegisteredFace())
        <div class="bg-white border-2 border-slate-300 rounded-3xl p-8 sm:p-12 text-center space-y-4 shadow-sm max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-[#F7941D] text-white flex items-center justify-center mx-auto shadow-md">
                <i data-lucide="camera" class="w-8 h-8"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-gray-900">Wajah Belum Terdaftar</h2>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto mt-1">
                    Anda perlu melakukan perekaman foto wajah master terlebih dahulu agar kamera dapat mengenali identitas Anda saat presensi.
                </p>
            </div>
            <a href="{{ route('karyawan.face.register') }}" 
               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-sm shadow-md transition-all whitespace-nowrap">
                <i data-lucide="camera" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Rekam Wajah Sekarang</span>
                <i data-lucide="arrow-right" class="w-4 h-4 text-[#F7941D]"></i>
            </a>
        </div>
    @else

        {{-- ========================================================================= --}}
        {{-- 1. TAB MENU PILIHAN: ABSEN AWAL (MASUK) & ABSEN AKHIR (PULANG)            --}}
        {{--    TETAP MUNCUL AGAR KARYAWAN BISA MELIHAT DAN MEMILIH KEDUA MENU          --}}
        {{-- ========================================================================= --}}
        <div class="bg-slate-100 p-1.5 rounded-2xl border-2 border-slate-200 shadow-inner grid grid-cols-2 gap-2">
            {{-- Tombol Tab 1: Absen Masuk (Awal) --}}
            <button type="button" id="tab-in-btn" onclick="switchMode('in')"
                    class="py-3 px-3 sm:px-6 rounded-xl font-black text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer">
                <div class="flex items-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4 shrink-0"></i>
                    <span>Absen Awal (Masuk)</span>
                </div>
                @if($hasCheckedIn)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                        ✓ {{ substr($todayAttendance->time_in, 0, 5) }} WIB
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700 shrink-0">
                        Belum
                    </span>
                @endif
            </button>

            {{-- Tombol Tab 2: Absen Pulang (Akhir) --}}
            <button type="button" id="tab-out-btn" onclick="switchMode('out')"
                    class="py-3 px-3 sm:px-6 rounded-xl font-black text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer">
                <div class="flex items-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                    <span>Absen Akhir (Pulang)</span>
                </div>
                @if($hasCheckedOut)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                        ✓ {{ substr($todayAttendance->time_out, 0, 5) }} WIB
                    </span>
                @elseif($hasCheckedIn)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300 shrink-0">
                        Siap Pulang
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700 shrink-0">
                        Menunggu Masuk
                    </span>
                @endif
            </button>
        </div>

        {{-- ========================================================================= --}}
        {{-- 2. KARTU DETEKSI LOKASI GPS (WAJIB NYALAIN LOKASI)                        --}}
        {{-- ========================================================================= --}}
        <div id="location-card" class="bg-white rounded-2xl p-4 sm:p-5 border-2 border-slate-300 shadow-sm transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div id="loc-icon-container" class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Verifikasi Lokasi Kerja</span>
                            <span id="loc-badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                                ⏳ Mendeteksi GPS...
                            </span>
                        </div>
                        <p id="loc-status-text" class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5">
                            Menghubungkan ke sensor GPS perangkat Anda...
                        </p>
                        <p id="loc-detail-text" class="text-[11px] text-slate-500 mt-0.5 font-mono">
                            Koordinat belum terbaca
                        </p>
                    </div>
                </div>

                <button type="button" onclick="detectGPSLocation(true)" id="btn-refresh-loc"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 text-slate-700 text-xs font-bold transition-all flex items-center justify-center gap-1.5 self-start sm:self-center shrink-0 cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-slate-600"></i>
                    <span>Perbarui Lokasi</span>
                </button>
            </div>

            {{-- Pesan Peringatan Jika GPS Belum Diizinkan / Error --}}
            <div id="loc-error-banner" class="hidden mt-3 p-3 rounded-xl bg-rose-50 border-2 border-rose-300 text-rose-900 text-xs flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span id="loc-error-msg">Akses lokasi (GPS) wajib diaktifkan pada browser/perangkat Anda untuk melanjutkan presensi.</span>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- 3. AREA SCANNER / STATUS PRESENSI                                         --}}
        {{-- ========================================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Kamera & Eksekusi Presensi (2 Kolom) --}}
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border-2 border-slate-300 shadow-sm space-y-4">
                
                {{-- Banner Mode Aktif --}}
                <div id="mode-banner" class="p-4 rounded-2xl bg-emerald-600 text-white flex items-center justify-between gap-3 shadow-md">
                    <div class="flex items-center gap-3">
                        <div id="mode-icon-box" class="w-11 h-11 rounded-xl bg-white text-emerald-700 flex items-center justify-center font-bold shrink-0 shadow-sm">
                            <i data-lucide="log-in" id="mode-icon" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-100 block" id="mode-badge-top">Target Presensi</span>
                            <h2 class="text-base sm:text-lg font-black tracking-tight" id="mode-title">PRESENSI MASUK KERJA</h2>
                        </div>
                    </div>
                    <span id="mode-status-pill" class="px-3 py-1.5 rounded-xl bg-emerald-800 text-white text-xs font-black shadow-inner">
                        Siap Masuk
                    </span>
                </div>

                {{-- Status Jika Sudah Absen Pada Mode Ini --}}
                <div id="already-done-card" class="hidden p-6 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto shadow-md">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-emerald-950" id="already-done-title">Presensi Masuk Sudah Tercatat!</h3>
                        <p class="text-xs text-emerald-800 mt-1" id="already-done-desc">
                            Anda sudah berhasil melakukan presensi ini hari ini.
                        </p>
                    </div>
                </div>

                {{-- VIEWFINDER KAMERA --}}
                <div id="camera-container" class="relative w-full aspect-[4/3] bg-[#0A0F1D] border-2 border-slate-800 rounded-2xl overflow-hidden shadow-2xl flex items-center justify-center">
                    
                    {{-- Video & Canvas Stream --}}
                    <video id="video" autoplay muted playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
                    <canvas id="overlay" class="absolute inset-0 w-full h-full pointer-events-none transform -scale-x-100"></canvas>

                    {{-- Laser Scanner Line Animation --}}
                    <div id="laser-line" class="laser-line hidden"></div>

                    {{-- HUD Pojok (Reticle Brackets) --}}
                    <div class="absolute inset-8 pointer-events-none flex flex-col justify-between">
                        <div class="flex justify-between">
                            <div id="reticle-tl" class="w-8 h-8 border-t-4 border-l-4 border-cyan-400 rounded-tl transition-colors duration-300"></div>
                            <div id="reticle-tr" class="w-8 h-8 border-t-4 border-r-4 border-cyan-400 rounded-tr transition-colors duration-300"></div>
                        </div>
                        <div class="flex justify-between">
                            <div id="reticle-bl" class="w-8 h-8 border-b-4 border-l-4 border-cyan-400 rounded-bl transition-colors duration-300"></div>
                            <div id="reticle-br" class="w-8 h-8 border-b-4 border-r-4 border-cyan-400 rounded-br transition-colors duration-300"></div>
                        </div>
                    </div>

                    {{-- Standby Overlay (Sebelum User Klik Tombol Mulai) --}}
                    <div id="standby-overlay" class="absolute inset-0 bg-[#0A0F1D]/90 backdrop-blur-xs flex flex-col items-center justify-center text-white p-6 text-center z-10">
                        <div class="w-16 h-16 rounded-2xl bg-[#001D5E] border-2 border-blue-400/40 flex items-center justify-center text-white mb-3 shadow-lg">
                            <i data-lucide="scan-face" class="w-8 h-8 text-[#F7941D]"></i>
                        </div>
                        <h4 class="font-black text-base text-slate-100">Kamera Siap Digunakan</h4>
                        <p class="text-xs text-slate-300 mt-1 max-w-xs">
                            Presensi baru akan dimulai ketika Anda menekan tombol di bawah. Cukup sekali saja saat wajah terverifikasi.
                        </p>
                    </div>

                    {{-- Loading Overlay (Saat Model Dimuat) --}}
                    <div id="loading-overlay" class="absolute inset-0 bg-[#0A0F1D] flex flex-col items-center justify-center text-white p-6 text-center z-20 hidden">
                        <div class="w-12 h-12 border-4 border-[#F7941D] border-t-transparent rounded-full animate-spin mb-4"></div>
                        <p id="loading-text" class="text-sm font-bold tracking-wide">Menghubungkan Sensor Kamera & Model AI...</p>
                        <p class="text-xs text-gray-400 mt-1">Harap berikan izin akses kamera pada browser Anda.</p>
                    </div>

                </div>

                {{-- Status Bar Bawah Kamera --}}
                <div id="status-card" class="p-3.5 rounded-2xl bg-white border-2 border-slate-300 text-center transition-colors">
                    <p id="status-text" class="text-xs sm:text-sm font-bold text-gray-700 flex items-center justify-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <span id="status-desc">Tekan tombol 'Mulai Presensi' di bawah untuk verifikasi wajah & lokasi.</span>
                    </p>
                </div>

                {{-- TOMBOL UTAMA PRESENSI (MULAI SAAT DIKLIK, CUKUP SEKALI) --}}
                <button type="button" id="btn-start-action" onclick="handleStartAttendanceClick()"
                        class="w-full py-4 rounded-2xl bg-[#001D5E] hover:bg-blue-900 border-2 border-[#001138] text-white font-black text-sm sm:text-base shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="camera" class="w-5 h-5 text-[#F7941D]" id="btn-action-icon"></i>
                    <span id="btn-action-text">MULAI PRESENSI MASUK (WAJAH & LOKASI)</span>
                </button>

            </div>

            {{-- Kolom Kanan: Rincian Profil & Info Jam Kerja (1 Kolom) --}}
            <div class="space-y-6">
                
                {{-- Master Profile Card --}}
                <div class="bg-white rounded-3xl p-6 border-2 border-slate-300 shadow-sm text-center">
                    @if($user->face_photo)
                        <img src="{{ asset('storage/' . $user->face_photo) }}" alt="{{ $user->name }}" 
                             class="w-20 h-20 rounded-2xl mx-auto object-cover border-2 border-[#001D5E] shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-2xl mx-auto bg-[#001D5E] text-white font-bold text-2xl flex items-center justify-center border-2 border-[#001D5E]">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="font-black text-gray-900 text-base mt-3">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 font-semibold">{{ $user->nip ?? 'Staff Stukka' }}</p>
                    <p class="text-xs text-[#F7941D] font-bold mt-0.5">{{ $user->position ?? 'Karyawan' }}</p>

                    <div class="mt-5 pt-4 border-t-2 border-slate-100 text-left space-y-2.5 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Status Master:</span>
                            <span class="font-bold text-emerald-700">✓ Biometrik Terdaftar</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tanggal:</span>
                            <span class="font-bold text-gray-800">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Target Kantor:</span>
                            <span class="font-bold text-[#001D5E]">{{ $settings->office_name ?? 'Kantor Stukka Digital' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Ketentuan Jam Kerja --}}
                <div class="bg-white rounded-3xl p-6 border-2 border-slate-300 shadow-sm space-y-3">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Aturan Waktu Presensi</p>
                    
                    <div class="space-y-2.5 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border-2 border-slate-200">
                            <p class="font-bold text-gray-800 flex justify-between">
                                <span>Jam Masuk:</span>
                                <span class="font-black text-emerald-700">{{ substr($settings->work_start_time, 0, 5) }} WIB</span>
                            </p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Toleransi keterlambatan: {{ $settings->late_tolerance_minutes }} menit.</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border-2 border-slate-200">
                            <p class="font-bold text-gray-800 flex justify-between">
                                <span>Jam Pulang:</span>
                                <span class="font-black text-[#001D5E]">{{ substr($settings->work_end_time, 0, 5) }} WIB</span>
                            </p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Dapat dilakukan saat selesai bertugas / lembur.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL POPUP NOTIFIKASI BERHASIL (CUKUP SEKALI PRESENSI)                   --}}
        {{-- ========================================================================= --}}
        <div id="success-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-3xl border-4 border-emerald-500 max-w-md w-full p-6 sm:p-8 text-center shadow-2xl relative">
                <div class="w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-emerald-500/40">
                    <i data-lucide="check" class="w-10 h-10"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900" id="success-title">PRESENSI BERHASIL!</h2>
                <p class="text-xs text-slate-500 mt-1" id="success-subtitle">Data presensi Anda berhasil diverifikasi dan tersimpan.</p>

                <div class="mt-5 p-4 rounded-2xl bg-emerald-50 border-2 border-emerald-200 text-left space-y-2.5 text-xs sm:text-sm">
                    <div class="flex justify-between items-center pb-2 border-b border-emerald-200/60">
                        <span class="text-emerald-900 font-medium">Nama Karyawan:</span>
                        <strong class="text-gray-900 font-bold">{{ $user->name }}</strong>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-emerald-200/60">
                        <span class="text-emerald-900 font-medium">Jenis Presensi:</span>
                        <strong class="text-emerald-700 font-black uppercase" id="success-type">PRESENSI MASUK</strong>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-emerald-200/60">
                        <span class="text-emerald-900 font-medium">Waktu Tercatat:</span>
                        <strong class="text-gray-900 font-black" id="success-time">--:-- WIB</strong>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-emerald-200/60">
                        <span class="text-emerald-900 font-medium">Status Kehadiran:</span>
                        <strong class="text-emerald-700 font-black" id="success-status">TEPAT WAKTU</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-900 font-medium">Lokasi Presensi:</span>
                        <strong class="text-emerald-700 font-black text-right" id="success-location">🏢 Di Kantor</strong>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-2">
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="w-full py-3.5 px-6 rounded-2xl bg-[#001D5E] hover:bg-blue-900 text-white font-black text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#F7941D]"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <button type="button" onclick="closeSuccessModal()"
                            class="w-full py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                        Tutup Jendela Ini
                    </button>
                </div>
            </div>
        </div>

    @endif

</div>
@endsection

@push('scripts')
@if($user->hasRegisteredFace())
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    // State Pengaturan
    const OFFICE_LAT = {{ $settings->office_latitude ?? -7.25747190 }};
    const OFFICE_LNG = {{ $settings->office_longitude ?? 112.75208830 }};
    const OFFICE_RADIUS = {{ $settings->office_radius_meters ?? 150 }};
    const OFFICE_NAME = "{{ $settings->office_name ?? 'Kantor Stukka Digital' }}";

    // Master Biometrik Wajah
    const rawMaster = {!! $user->face_descriptor !!};
    const masterDescriptor = new Float32Array(rawMaster);

    // State Halaman
    let currentMode = "{{ $hasCheckedIn && !$hasCheckedOut ? 'out' : 'in' }}";
    let hasIn = {{ $hasCheckedIn ? 'true' : 'false' }};
    let hasOut = {{ $hasCheckedOut ? 'true' : 'false' }};

    // State Lokasi GPS
    let userLat = null;
    let userLng = null;
    let userDistanceMeters = null;
    let userLocationStatus = 'office'; // 'office' atau 'outside'
    let userLocationName = '';
    let isGpsReady = false;

    // State Kamera & Scanner
    let isModelLoaded = false;
    let isCameraActive = false;
    let currentStream = null;
    let scanInterval = null;
    let isSubmitting = false;

    // Elements
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const laserLine = document.getElementById('laser-line');
    const standbyOverlay = document.getElementById('standby-overlay');
    const loadingOverlay = document.getElementById('loading-overlay');
    const statusCard = document.getElementById('status-card');
    const statusText = document.getElementById('status-text');
    const statusDesc = document.getElementById('status-desc');
    const btnStartAction = document.getElementById('btn-start-action');
    const btnActionText = document.getElementById('btn-action-text');
    const btnActionIcon = document.getElementById('btn-action-icon');

    const reticleTL = document.getElementById('reticle-tl');
    const reticleTR = document.getElementById('reticle-tr');
    const reticleBL = document.getElementById('reticle-bl');
    const reticleBR = document.getElementById('reticle-br');

    // Tab Buttons
    const tabInBtn = document.getElementById('tab-in-btn');
    const tabOutBtn = document.getElementById('tab-out-btn');

    // Location Elements
    const locBadge = document.getElementById('loc-badge');
    const locStatusText = document.getElementById('loc-status-text');
    const locDetailText = document.getElementById('loc-detail-text');
    const locIconContainer = document.getElementById('loc-icon-container');
    const locErrorBanner = document.getElementById('loc-error-banner');
    const locErrorMsg = document.getElementById('loc-error-msg');

    // Success Modal
    const successModal = document.getElementById('success-modal');
    const successTime = document.getElementById('success-time');
    const successStatus = document.getElementById('success-status');
    const successType = document.getElementById('success-type');
    const successLocation = document.getElementById('success-location');

    // Haversine Distance Calculator (dalam Meter)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    // Audio Chime Synthesizer
    function playChime(success = true) {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            if (success) {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1760, ctx.currentTime + 0.12);
                gain.gain.setValueAtTime(0.18, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.28);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.28);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime);
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.2);
            }
        } catch (e) {}
    }

    // Reticle Color Setter
    function setReticleColor(color = 'cyan') {
        const borderClass = color === 'green' ? 'border-emerald-400' : (color === 'red' ? 'border-rose-500' : 'border-cyan-400');
        [reticleTL, reticleTR, reticleBL, reticleBR].forEach(el => {
            el.className = el.className.replace(/border-(cyan|emerald|rose)-\d+/, borderClass);
        });
    }

    // Deteksi Lokasi GPS
    function detectGPSLocation(isManualClick = false) {
        if (!navigator.geolocation) {
            locErrorBanner.classList.remove('hidden');
            locErrorMsg.innerText = 'Perangkat / browser Anda tidak mendukung fitur lokasi Geolocation.';
            isGpsReady = false;
            updateActionState();
            return;
        }

        locBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300';
        locBadge.innerText = '⏳ Membaca GPS...';
        locStatusText.innerText = 'Mendeteksi koordinat dan jarak ke kantor...';
        locErrorBanner.classList.add('hidden');

        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                userLat = pos.coords.latitude;
                userLng = pos.coords.longitude;
                isGpsReady = true;

                // Hitung Jarak ke Kantor
                userDistanceMeters = calculateDistance(userLat, userLng, OFFICE_LAT, OFFICE_LNG);
                const isWithinOffice = userDistanceMeters <= OFFICE_RADIUS;
                userLocationStatus = isWithinOffice ? 'office' : 'outside';

                if (isWithinOffice) {
                    locIconContainer.className = 'w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm';
                    locBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300';
                    locBadge.innerText = '🏢 Di Kantor';
                    locStatusText.innerText = `${OFFICE_NAME} (Radius: ±${userDistanceMeters}m dari pusat kantor)`;
                    userLocationName = `${OFFICE_NAME}`;
                } else {
                    locIconContainer.className = 'w-10 h-10 rounded-xl bg-[#F7941D] text-white flex items-center justify-center shrink-0 shadow-sm';
                    locBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-orange-100 text-orange-800 border border-orange-300';
                    locBadge.innerText = '📍 Di Luar Kantor';
                    locStatusText.innerText = `Presensi di Luar Kantor (Jarak: ±${userDistanceMeters}m dari kantor)`;
                    userLocationName = `Di Luar Kantor (${userLat.toFixed(5)}, ${userLng.toFixed(5)})`;
                }

                locDetailText.innerText = `Koordinat: ${userLat.toFixed(6)}, ${userLng.toFixed(6)} • Akurasi: ±${Math.round(pos.coords.accuracy)}m`;
                updateActionState();

                if (isManualClick && window.notify) {
                    window.notify.success(`Lokasi terdeteksi: ${userLocationStatus === 'office' ? 'Di Kantor' : 'Di Luar Kantor'}`, 'GPS Siap');
                }
            },
            (err) => {
                isGpsReady = false;
                locIconContainer.className = 'w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm';
                locBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 border border-rose-300';
                locBadge.innerText = '⚠️ GPS Ditolak / Mati';
                locStatusText.innerText = 'Lokasi GPS belum diizinkan atau dinonaktifkan.';
                locDetailText.innerText = 'Harap aktifkan GPS dan izinkan browser mengakses lokasi untuk presensi.';
                locErrorBanner.classList.remove('hidden');
                locErrorMsg.innerText = 'Akses lokasi (GPS) wajib diaktifkan. Klik perbarui lokasi setelah menyalakan GPS.';
                updateActionState();
            },
            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
        );
    }

    // Switch Antara Mode Masuk dan Pulang
    function switchMode(mode) {
        currentMode = mode;

        // Reset visual state
        if (isCameraActive) {
            stopCamera();
        }

        // Update Tab Style
        if (mode === 'in') {
            tabInBtn.className = 'py-3 px-3 sm:px-6 rounded-xl font-black text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer bg-[#001D5E] text-white shadow-md ring-2 ring-[#001D5E]/20';
            tabOutBtn.className = 'py-3 px-3 sm:px-6 rounded-xl font-bold text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer text-slate-700 hover:bg-white/80';

            document.getElementById('mode-banner').className = 'p-4 rounded-2xl bg-emerald-600 text-white flex items-center justify-between gap-3 shadow-md';
            document.getElementById('mode-icon-box').className = 'w-11 h-11 rounded-xl bg-white text-emerald-700 flex items-center justify-center font-bold shrink-0 shadow-sm';
            document.getElementById('mode-icon').setAttribute('data-lucide', 'log-in');
            document.getElementById('mode-title').innerText = 'PRESENSI MASUK KERJA';
            document.getElementById('mode-status-pill').className = 'px-3 py-1.5 rounded-xl bg-emerald-800 text-white text-xs font-black shadow-inner';
            document.getElementById('mode-status-pill').innerText = hasIn ? 'Sudah Masuk' : 'Siap Masuk';
        } else {
            tabOutBtn.className = 'py-3 px-3 sm:px-6 rounded-xl font-black text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer bg-[#001D5E] text-white shadow-md ring-2 ring-[#001D5E]/20';
            tabInBtn.className = 'py-3 px-3 sm:px-6 rounded-xl font-bold text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-center gap-2 transition-all cursor-pointer text-slate-700 hover:bg-white/80';

            document.getElementById('mode-banner').className = 'p-4 rounded-2xl bg-[#001D5E] text-white flex items-center justify-between gap-3 shadow-md';
            document.getElementById('mode-icon-box').className = 'w-11 h-11 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-bold shrink-0 shadow-sm';
            document.getElementById('mode-icon').setAttribute('data-lucide', 'log-out');
            document.getElementById('mode-title').innerText = 'PRESENSI PULANG KERJA';
            document.getElementById('mode-status-pill').className = 'px-3 py-1.5 rounded-xl bg-orange-600 text-white text-xs font-black shadow-inner';
            document.getElementById('mode-status-pill').innerText = hasOut ? 'Sudah Pulang' : (hasIn ? 'Siap Pulang' : 'Wajib Masuk Dulu');
        }

        lucide.createIcons();
        updateActionState();
    }

    // Update Tampilan Tombol Aksi Sesuai Kondisi
    function updateActionState() {
        const alreadyDoneCard = document.getElementById('already-done-card');
        const cameraContainer = document.getElementById('camera-container');

        if (currentMode === 'in' && hasIn) {
            alreadyDoneCard.classList.remove('hidden');
            document.getElementById('already-done-title').innerText = 'Presensi Masuk Hari Ini Sudah Selesai!';
            document.getElementById('already-done-desc').innerText = 'Anda sudah tercatat presensi masuk hari ini. Silakan pilih menu Absen Akhir (Pulang) jika sudah selesai jam kerja.';
            btnStartAction.disabled = true;
            btnStartAction.className = 'w-full py-4 rounded-2xl bg-emerald-700 opacity-60 cursor-not-allowed text-white font-black text-sm flex items-center justify-center gap-2';
            btnActionText.innerText = 'SUDAH SELESAI ABSEN MASUK';
            statusDesc.innerText = 'Presensi masuk hari ini sudah tuntas.';
            return;
        }

        if (currentMode === 'out' && hasOut) {
            alreadyDoneCard.classList.remove('hidden');
            document.getElementById('already-done-title').innerText = 'Presensi Pulang Hari Ini Sudah Selesai!';
            document.getElementById('already-done-desc').innerText = 'Anda sudah berhasil melakukan presensi pulang hari ini. Terima kasih atas kerja keras Anda!';
            btnStartAction.disabled = true;
            btnStartAction.className = 'w-full py-4 rounded-2xl bg-[#001D5E] opacity-60 cursor-not-allowed text-white font-black text-sm flex items-center justify-center gap-2';
            btnActionText.innerText = 'SUDAH SELESAI ABSEN PULANG';
            statusDesc.innerText = 'Presensi hari ini sudah lengkap 100%.';
            return;
        }

        if (currentMode === 'out' && !hasIn) {
            alreadyDoneCard.classList.remove('hidden');
            document.getElementById('already-done-title').innerText = 'Belum Melakukan Absen Masuk!';
            document.getElementById('already-done-desc').innerText = 'Anda wajib melakukan Absen Awal (Masuk) terlebih dahulu sebelum dapat mencatat absen pulang.';
            btnStartAction.disabled = true;
            btnStartAction.className = 'w-full py-4 rounded-2xl bg-slate-300 opacity-60 cursor-not-allowed text-slate-700 font-black text-sm flex items-center justify-center gap-2';
            btnActionText.innerText = 'LAKUKAN ABSEN MASUK TERLEBIH DAHULU';
            statusDesc.innerText = 'Silakan klik tab Absen Awal (Masuk).';
            return;
        }

        alreadyDoneCard.classList.add('hidden');

        if (!isGpsReady) {
            btnStartAction.disabled = true;
            btnStartAction.className = 'w-full py-4 rounded-2xl bg-slate-300 opacity-60 cursor-not-allowed text-slate-700 font-black text-sm flex items-center justify-center gap-2';
            btnActionText.innerText = 'AKTIFKAN LOKASI (GPS) UNTUK MELANJUTKAN';
            statusDesc.innerText = 'Menunggu izin akses GPS perangkat Anda...';
            return;
        }

        btnStartAction.disabled = false;
        btnStartAction.className = 'w-full py-4 rounded-2xl bg-[#001D5E] hover:bg-blue-900 border-2 border-[#001138] text-white font-black text-sm sm:text-base shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer';
        
        if (currentMode === 'in') {
            btnActionText.innerText = isCameraActive 
                ? 'KAMERA SEDANG MENCOCOKKAN WAJAH...' 
                : 'MULAI PRESENSI MASUK SEKARANG';
        } else {
            btnActionText.innerText = isCameraActive 
                ? 'KAMERA SEDANG MENCOCOKKAN WAJAH...' 
                : 'MULAI PRESENSI PULANG SEKARANG';
        }

        if (!isCameraActive) {
            statusDesc.innerText = `GPS Siap (${userLocationStatus === 'office' ? 'Di Kantor' : 'Di Luar Kantor'}). Tekan tombol untuk mulai memindai.`;
        }
    }

    // Klik Tombol Mulai Presensi
    async function handleStartAttendanceClick() {
        if (!isGpsReady) {
            detectGPSLocation(true);
            return;
        }

        if (isCameraActive) {
            // Jika sudah aktif, user klik manual untuk instant capture jika wajah sudah cocok
            return;
        }

        await startCameraAndScan();
    }

    // Nyalakan Kamera dan Mulai Scan
    async function startCameraAndScan() {
        if (isCameraActive) return;

        standbyOverlay.classList.add('hidden');
        loadingOverlay.classList.remove('hidden');

        try {
            // 1. Muat Model AI jika belum
            if (!isModelLoaded) {
                const MODEL_URL = '{{ asset("models/face-api") }}';
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);
                isModelLoaded = true;
            }

            // 2. Akses Kamera
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                audio: false
            });

            currentStream = stream;
            video.srcObject = stream;
            await new Promise(resolve => video.onloadedmetadata = resolve);
            video.play();

            isCameraActive = true;
            loadingOverlay.classList.add('hidden');
            laserLine.classList.remove('hidden');
            setReticleColor('cyan');

            statusCard.className = 'p-3.5 rounded-2xl bg-white border-2 border-slate-300 text-center transition-colors';
            statusText.innerHTML = `
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="font-bold text-gray-800">Arahkan wajah Anda ke tengah lensa kamera...</span>
            `;

            updateActionState();
            startFaceLoop();

        } catch (err) {
            console.error(err);
            loadingOverlay.classList.add('hidden');
            standbyOverlay.classList.remove('hidden');
            isCameraActive = false;
            alert('Gagal menyalakan kamera: ' + (err.message || 'Izin kamera ditolak. Pastikan webcam diizinkan.'));
            updateActionState();
        }
    }

    // Matikan Kamera
    function stopCamera() {
        if (scanInterval) {
            clearInterval(scanInterval);
            scanInterval = null;
        }
        if (currentStream) {
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        video.srcObject = null;
        isCameraActive = false;

        const ctx = overlay.getContext('2d');
        ctx.clearRect(0, 0, overlay.width, overlay.height);

        laserLine.classList.add('hidden');
        standbyOverlay.classList.remove('hidden');
        setReticleColor('cyan');
        updateActionState();
    }

    // Loop Deteksi Wajah
    function startFaceLoop() {
        if (scanInterval) clearInterval(scanInterval);

        const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
        faceapi.matchDimensions(overlay, displaySize);

        let matchConsecutive = 0;

        scanInterval = setInterval(async () => {
            if (!isCameraActive || isSubmitting) return;

            const detection = await faceapi.detectSingleFace(
                video, 
                new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 })
            ).withFaceLandmarks().withFaceDescriptor();

            const ctx = overlay.getContext('2d');
            ctx.clearRect(0, 0, overlay.width, overlay.height);

            if (detection) {
                const distance = faceapi.euclideanDistance(detection.descriptor, masterDescriptor);
                let matchPercent = 0;
                if (distance <= 0.58) {
                    matchPercent = Math.round(100 - (distance * 75));
                    matchPercent = Math.max(55, Math.min(100, matchPercent));
                } else {
                    matchPercent = Math.max(0, Math.round(50 - ((distance - 0.58) * 120)));
                }

                if (distance <= 0.58) {
                    setReticleColor('green');
                    matchConsecutive++;

                    statusCard.className = 'p-3.5 rounded-2xl bg-emerald-50 border-2 border-emerald-500 text-center transition-colors';
                    statusText.innerHTML = `
                        <span class="w-3 h-3 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span class="text-emerald-950 font-black text-sm">Wajah Cocok: {{ $user->name }} (${matchPercent}%)</span>
                    `;

                    // Jika wajah stabil cocok, otomatis ambil sekali presensi
                    if (matchConsecutive >= 2) {
                        clearInterval(scanInterval);
                        scanInterval = null;
                        executeSingleAttendance(matchPercent);
                    }
                } else {
                    matchConsecutive = 0;
                    setReticleColor('red');
                    statusCard.className = 'p-3.5 rounded-2xl bg-rose-50 border-2 border-rose-300 text-center transition-colors';
                    statusText.innerHTML = `
                        <span class="w-3 h-3 rounded-full bg-rose-600"></span>
                        <span class="text-rose-950 font-bold text-xs sm:text-sm">Wajah belum sesuai (${matchPercent}%). Posisikan wajah Anda lebih dekat.</span>
                    `;
                }
            } else {
                matchConsecutive = 0;
                setReticleColor('cyan');
                statusCard.className = 'p-3.5 rounded-2xl bg-white border-2 border-slate-300 text-center transition-colors';
                statusText.innerHTML = `
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-gray-800 font-bold text-xs sm:text-sm">Mendeteksi wajah Anda...</span>
                `;
            }
        }, 200);
    }

    // Eksekusi Submit Presensi (CUKUP SEKALI PRESENSI SAJA)
    async function executeSingleAttendance(score) {
        if (isSubmitting) return;
        isSubmitting = true;

        statusCard.className = 'p-3.5 rounded-2xl bg-blue-50 border-2 border-blue-400 text-center transition-colors';
        statusText.innerHTML = `
            <span class="inline-block animate-spin mr-1">⌛</span>
            <span class="text-blue-950 font-black">Memproses data kehadiran & lokasi...</span>
        `;

        // Ambil Snapshot Gambar dari Video
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/jpeg', 0.85);

        try {
            const res = await fetch("{{ route('karyawan.attendance.record') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: currentMode,
                    image: imageData,
                    face_match_score: score || 85,
                    latitude: userLat,
                    longitude: userLng,
                    location_name: userLocationName,
                    notes: userLocationStatus === 'outside' ? `Presensi di luar kantor (±${userDistanceMeters}m)` : ''
                })
            });

            const data = await res.json();

            // CUKUP SEKALI: Segera matikan kamera agar lampu webcam mati
            stopCamera();

            if (data.success) {
                playChime(true);

                // Update Local State
                if (currentMode === 'in') {
                    hasIn = true;
                } else {
                    hasOut = true;
                }

                // Tampilkan Modal Berhasil
                successTime.innerText = data.time_formatted || '--:-- WIB';
                successType.innerText = currentMode === 'in' ? 'PRESENSI AWAL (MASUK)' : 'PRESENSI AKHIR (PULANG)';
                successStatus.innerText = data.status === 'late' ? 'TERLAMBAT' : (data.status === 'on_time' ? 'TEPAT WAKTU' : 'BERHASIL');
                
                if (data.location_status === 'office') {
                    successLocation.innerText = '🏢 Di Kantor (' + (data.distance_meters ?? 0) + 'm)';
                    successLocation.className = 'text-emerald-700 font-black text-right';
                } else {
                    successLocation.innerText = '📍 Di Luar Kantor (±' + (data.distance_meters ?? 0) + 'm)';
                    successLocation.className = 'text-[#F7941D] font-black text-right';
                }

                successModal.classList.remove('hidden');
                lucide.createIcons();

                if (window.notify) {
                    window.notify.success(data.message, 'Presensi Berhasil');
                }

            } else {
                playChime(false);
                isSubmitting = false;
                alert(data.message || 'Presensi gagal.');
                updateActionState();
            }

        } catch (err) {
            console.error(err);
            playChime(false);
            isSubmitting = false;
            stopCamera();
            alert('Terjadi kesalahan koneksi saat mengirim presensi.');
            updateActionState();
        }
    }

    function closeSuccessModal() {
        successModal.classList.add('hidden');
        switchMode(currentMode);
    }

    // Inisialisasi saat halaman dibuka
    document.addEventListener('DOMContentLoaded', () => {
        switchMode(currentMode);
        detectGPSLocation(false);
    });
</script>
@endif
@endpush
