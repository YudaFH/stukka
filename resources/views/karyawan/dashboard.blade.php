@extends('layouts.karyawan')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- 1. HEADER & GREETING --}}
    <div class="bg-white p-6 rounded-2xl border-2 border-slate-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Foto Profil / Avatar Inisial --}}
            @if($user->face_photo)
                <img src="{{ asset('storage/' . $user->face_photo) }}" alt="Foto {{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-300 shadow-sm shrink-0">
            @else
                <div class="w-14 h-14 rounded-2xl bg-[#001D5E] text-white flex items-center justify-center font-bold text-xl border-2 border-[#001138] shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif

            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-[#001D5E] text-white text-xs font-semibold mb-1">
                    <i data-lucide="badge-check" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                    <span>Stukka Staff</span>
                </div>
                <h1 class="text-2xl font-bold text-[#001D5E] leading-tight">
                    Halo, {{ $user->name }} 👋
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 font-normal mt-0.5">
                    {{ $user->position ?? 'Staff / Crew' }} &bull; NIP: <span class="font-semibold text-gray-700">{{ $user->nip ?? '-' }}</span>
                </p>
            </div>
        </div>

        {{-- Waktu & Tanggal Realtime --}}
        <div class="pt-3 md:pt-0 border-t md:border-t-0 border-slate-200 flex flex-col md:items-end justify-center">
            <div class="flex items-center justify-between md:justify-end gap-3 w-full">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Sistem</span>
                <span id="liveClock" class="text-xl sm:text-2xl font-black text-[#001D5E] tracking-tight">--:--:-- WIB</span>
            </div>
            <p class="text-xs font-medium text-slate-500 mt-0.5 text-right w-full">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- ALERT JIKA BELUM REGISTRASI MASTER WAJAH --}}
    @if(!$user->hasRegisteredFace())
        <div class="bg-white border-2 border-[#F7941D] p-5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#F7941D] text-white flex items-center justify-center shrink-0">
                    <i data-lucide="camera" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base text-gray-900">Perekaman Wajah Diperlukan!</h3>
                    <p class="text-xs text-gray-600 mt-0.5">Anda belum merekam data biometrik wajah. Lakukan perekaman satu kali untuk mengaktifkan scanner presensi.</p>
                </div>
            </div>
            <a href="{{ route('karyawan.face.register') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-semibold text-xs transition-colors shrink-0 whitespace-nowrap border-2 border-[#001138]">
                <span>Rekam Wajah Sekarang</span>
                <i data-lucide="arrow-right" class="w-4 h-4 text-[#F7941D]"></i>
            </a>
        </div>
    @endif

    {{-- 2. STATUS PRESENSI HARI INI --}}
    <div x-data="{ showScheduleModal: false }">
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 flex flex-col justify-between">
            <div>
                {{-- Header Status --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b-2 border-slate-100 mb-5">
                    <div class="min-w-0">
                        <h2 class="text-base font-bold text-[#001D5E] flex items-center gap-2">
                            <i data-lucide="clock" class="w-5 h-5 text-[#F7941D]"></i>
                            <span>Presensi Hari Ini</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Status kehadiran dan pencatatan jam kerja Anda.</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 flex-nowrap">
                        {{-- Tombol Info Ketentuan Jam Kerja --}}
                        <button type="button" 
                                @click="showScheduleModal = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition-colors cursor-pointer whitespace-nowrap shrink-0"
                                title="Lihat Ketentuan Jam Kerja">
                            <i data-lucide="info" class="w-4 h-4 text-[#F7941D]"></i>
                            <span>Ketentuan Jam Kerja</span>
                        </button>

                        {{-- Badge Status Hari Ini --}}
                        @if($todayAttendance)
                            @if($todayAttendance->status === 'on_time')
                                <span class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold inline-flex items-center gap-1.5 whitespace-nowrap shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Tepat Waktu
                                </span>
                            @elseif($todayAttendance->status === 'late')
                                <span class="px-3 py-1.5 rounded-lg bg-[#F7941D] text-white text-xs font-semibold inline-flex items-center gap-1.5 whitespace-nowrap shrink-0">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Terlambat
                                </span>
                            @elseif($todayAttendance->status === 'overtime')
                                <span class="px-3 py-1.5 rounded-lg bg-[#001D5E] text-white text-xs font-semibold inline-flex items-center gap-1.5 whitespace-nowrap shrink-0">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#F7941D]"></i> Lembur
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-lg bg-[#001D5E] text-white text-xs font-semibold whitespace-nowrap shrink-0">Hadir</span>
                            @endif
                        @else
                            <span class="px-3 py-1.5 rounded-lg bg-slate-700 text-white text-xs font-semibold whitespace-nowrap shrink-0">Belum Absen</span>
                        @endif
                    </div>
                </div>

                {{-- Box Jam Masuk & Jam Pulang --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Box Jam Masuk --}}
                    <div class="p-4 rounded-xl border-2 border-slate-200 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Absen Masuk</span>
                            <span class="p-1.5 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-[#001D5E] mt-2">
                            {{ $todayAttendance && $todayAttendance->time_in ? substr($todayAttendance->time_in, 0, 5) . ' WIB' : '--:--' }}
                        </p>
                        <div class="mt-2 text-xs text-gray-500 space-y-0.5">
                            <p><span class="font-semibold text-gray-700">Target Masuk:</span> {{ substr($settings->work_start_time, 0, 5) }} WIB</p>
                            <p><span class="font-semibold text-gray-700">Status Waktu:</span> Fleksibel (Toleransi {{ $settings->late_tolerance_minutes }} menit, telat tetap bisa absen)</p>
                        </div>
                    </div>

                    {{-- Box Jam Pulang --}}
                    <div class="p-4 rounded-xl border-2 border-slate-200 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Absen Pulang</span>
                            <span class="p-1.5 rounded-lg bg-[#F7941D] text-white flex items-center justify-center">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-[#001D5E] mt-2">
                            {{ $todayAttendance && $todayAttendance->time_out ? substr($todayAttendance->time_out, 0, 5) . ' WIB' : '--:--' }}
                        </p>
                        <div class="mt-2 text-xs text-gray-500 space-y-0.5">
                            <p><span class="font-semibold text-gray-700">Target Pulang:</span> {{ substr($settings->work_end_time, 0, 5) }} WIB</p>
                            <p><span class="font-semibold text-gray-700">Lembur:</span> Bebas kapan saja saat tugas selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Utama --}}
            <div class="mt-6 pt-5 border-t-2 border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-500 text-center sm:text-left">
                    Verifikasi kehadiran otomatis via pemindai kamera wajah.
                </p>
                <a href="{{ route('karyawan.attendance.scan') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-sm transition-colors border-2 border-[#D67B0F] whitespace-nowrap shrink-0 shadow-sm">
                    <i data-lucide="scan-face" class="w-5 h-5"></i>
                    <span>Buka Scanner Presensi</span>
                </a>
            </div>
        </div>

        {{-- Modal Info Ketentuan Operasional --}}
        <div x-show="showScheduleModal" 
             x-cloak
             x-transition.opacity.duration.200ms
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div @click.away="showScheduleModal = false"
                 class="bg-white rounded-2xl border-2 border-slate-300 max-w-md w-full p-6 shadow-2xl space-y-4">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#001D5E] text-white text-xs font-bold tracking-wider uppercase mb-2">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                            <span>JADWAL RESMI KANTOR</span>
                        </div>
                        <h3 class="font-bold text-lg text-[#001D5E]">Ketentuan Operasional</h3>
                    </div>
                    <button type="button" 
                            @click="showScheduleModal = false" 
                            class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                {{-- List Ketentuan Jadwal --}}
                <ul class="space-y-3 text-xs text-slate-700 pt-1">
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border-2 border-slate-200">
                        <span class="p-1.5 rounded-lg bg-emerald-600 text-white shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </span>
                        <div>
                            <span class="font-bold text-slate-900 block text-sm">Presensi Masuk</span>
                            <span class="text-slate-600 text-xs mt-0.5 block">Jam masuk resmi pukul {{ substr($settings->work_start_time, 0, 5) }} WIB (Toleransi {{ $settings->late_tolerance_minutes }} menit). Karyawan yang telat tetap dapat melakukan absensi masuk.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border-2 border-slate-200">
                        <span class="p-1.5 rounded-lg bg-[#F7941D] text-white shrink-0 mt-0.5">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </span>
                        <div>
                            <span class="font-bold text-slate-900 block text-sm">Presensi Pulang</span>
                            <span class="text-slate-600 text-xs mt-0.5 block">Target jam kerja berakhir pukul {{ substr($settings->work_end_time, 0, 5) }} WIB. Karyawan yang lembur atau bertugas event dapat melakukan presensi pulang kapan pun saat tugas tuntas.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border-2 border-slate-200">
                        <span class="p-1.5 rounded-lg bg-[#001D5E] text-white shrink-0 mt-0.5">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D]"></i>
                        </span>
                        <div>
                            <span class="font-bold text-slate-900 block text-sm">Lembur & Hari Libur</span>
                            <span class="text-slate-600 text-xs mt-0.5 block">Presensi tetap dibuka untuk karyawan atau kru yang bertugas di akhir pekan atau hari libur operasional.</span>
                        </div>
                    </li>
                </ul>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" 
                            @click="showScheduleModal = false"
                            class="w-full py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs transition-colors border-2 border-[#001138] cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- 3. STATISTIK BULAN INI --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border-2 border-slate-300 rounded-2xl p-5">
            <div class="flex items-center justify-between gap-2 text-gray-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider truncate">Total Kehadiran</span>
                <span class="p-1.5 rounded-lg bg-[#001D5E] text-white shrink-0">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-[#001D5E]">{{ $totalPresent }} <span class="text-xs font-semibold text-gray-400">Hari</span></p>
            <p class="text-xs text-gray-500 mt-1 truncate">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>

        <div class="bg-white border-2 border-slate-300 rounded-2xl p-5">
            <div class="flex items-center justify-between gap-2 text-gray-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider truncate">Tepat Waktu</span>
                <span class="p-1.5 rounded-lg bg-emerald-600 text-white shrink-0">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ $totalOnTime }} <span class="text-xs font-semibold text-gray-400">Hari</span></p>
            <p class="text-xs text-gray-500 mt-1 truncate">Presensi sesuai jam kerja</p>
        </div>

        <div class="bg-white border-2 border-slate-300 rounded-2xl p-5">
            <div class="flex items-center justify-between gap-2 text-gray-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider truncate">Terlambat</span>
                <span class="p-1.5 rounded-lg bg-[#F7941D] text-white shrink-0">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-[#F7941D]">{{ $totalLate }} <span class="text-xs font-semibold text-gray-400">Hari</span></p>
            <p class="text-xs text-gray-500 mt-1 truncate">Presensi lewat batas toleransi</p>
        </div>
    </div>

    {{-- 4. RIWAYAT ABSENSI TERAKHIR --}}
    <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden">
        <div class="p-6 border-b-2 border-slate-200 bg-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#001D5E] flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-[#F7941D]"></i>
                <span>Riwayat Presensi Terbaru</span>
            </h2>
            <a href="{{ route('karyawan.attendance.history') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border-2 border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors">
                <span>Lihat Semua</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        @if($recentAttendances->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <div class="w-14 h-14 rounded-2xl bg-white border-2 border-slate-300 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <i data-lucide="calendar-x" class="w-7 h-7"></i>
                </div>
                <p class="font-bold text-slate-700 text-sm">Belum Ada Riwayat Presensi</p>
                <p class="text-xs text-slate-500 mt-1">Catatan absensi Anda akan otomatis terekam di sini setelah melakukan scan wajah.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-5">Jam Masuk</th>
                            <th class="py-3 px-5">Jam Pulang</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5">Lokasi</th>
                            <th class="py-3 px-5 text-center whitespace-nowrap min-w-[140px]">Foto Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-gray-700">
                        @foreach($recentAttendances as $att)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-[#001D5E] whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    @if($att->status === 'on_time')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-600 text-white">
                                             Tepat Waktu
                                        </span>
                                    @elseif($att->status === 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-[#F7941D] text-white">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-700 text-white">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    @php
                                        $locStatus = $att->location_status_in ?? $att->location_status_out;
                                    @endphp
                                    @if($locStatus === 'outside')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-black bg-orange-100 text-orange-800 border border-orange-300">
                                            📍 Luar Kantor
                                        </span>
                                    @elseif($locStatus === 'office')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            🏢 Di Kantor
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-3 whitespace-nowrap">
                                        @if($att->photo_in)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <a href="{{ asset('storage/' . $att->photo_in) }}" target="_blank" title="Foto Masuk ({{ $att->time_in ? substr($att->time_in, 0, 5) : '' }})" 
                                                   class="w-10 h-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px] rounded-xl overflow-hidden border-2 border-emerald-500 shadow-xs hover:scale-110 transition-transform block shrink-0 bg-slate-100">
                                                    <img src="{{ asset('storage/' . $att->photo_in) }}" class="w-full h-full object-cover">
                                                </a>
                                                <span class="text-[9px] font-black uppercase text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-md leading-none border border-emerald-200">IN</span>
                                            </div>
                                        @endif

                                        @if($att->photo_out)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <a href="{{ asset('storage/' . $att->photo_out) }}" target="_blank" title="Foto Pulang ({{ $att->time_out ? substr($att->time_out, 0, 5) : '' }})" 
                                                   class="w-10 h-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px] rounded-xl overflow-hidden border-2 border-[#F7941D] shadow-xs hover:scale-110 transition-transform block shrink-0 bg-slate-100">
                                                    <img src="{{ asset('storage/' . $att->photo_out) }}" class="w-full h-full object-cover">
                                                </a>
                                                <span class="text-[9px] font-black uppercase text-orange-700 bg-orange-100 px-1.5 py-0.5 rounded-md leading-none border border-orange-200">OUT</span>
                                            </div>
                                        @endif

                                        @if(!$att->photo_in && !$att->photo_out)
                                            <span class="text-xs text-slate-400 font-bold">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Script Live Clock --}}
<script>
    function updateLiveClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('liveClock');
        if (el) {
            el.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    updateLiveClock();
    setInterval(updateLiveClock, 1000);
</script>
@endsection
