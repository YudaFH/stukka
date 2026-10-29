@extends('layouts.admin')

@section('title', 'Pengaturan Jam & Aturan Absensi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb & Title --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Pengaturan Jam & Aturan Absensi</h1>
            <p class="text-sm text-gray-500 mt-1">
                Atur jendela waktu absensi masuk/pulang, hari kerja aktif, serta parameter AI Face Recognition.
            </p>
        </div>
        <a href="{{ route('admin.attendances.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-300 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold transition-all whitespace-nowrap shrink-0 shadow-xs">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
            <span>Kembali ke Rekap</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-white border-2 border-emerald-600 text-emerald-950 text-sm font-bold flex items-center gap-2">
            <span class="p-1 rounded bg-emerald-600 text-white flex items-center justify-center">
                <i data-lucide="check" class="w-4 h-4"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.attendances.settings') }}" class="space-y-6">
        @csrf

        {{-- SECTION 1: JENDELA WAKTU ABSENSI MASUK --}}
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b-2 border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-bold">
                    <i data-lucide="log-in" class="w-5 h-5 text-[#F7941D]"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Jendela Waktu Absensi Masuk</h2>
                    <p class="text-xs text-gray-400">Atur rentang jam karyawan diizinkan melakukan presensi masuk.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Jam Buka Masuk --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Jam Buka Masuk *
                    </label>
                    <input type="time" name="check_in_start_time" value="{{ old('check_in_start_time', substr($settings->check_in_start_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Mulai dibuka</p>
                </div>

                {{-- Jam Masuk Resmi --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Jam Masuk Resmi *
                    </label>
                    <input type="time" name="work_start_time" value="{{ old('work_start_time', substr($settings->work_start_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-bold text-[#001D5E]">
                    <p class="text-[11px] text-gray-400 mt-1">Target masuk kantor</p>
                </div>

                {{-- Toleransi Keterlambatan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Toleransi (Menit) *
                    </label>
                    <input type="number" name="late_tolerance_minutes" value="{{ old('late_tolerance_minutes', $settings->late_tolerance_minutes) }}" min="0" max="180" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Lewat batas = Terlambat</p>
                </div>

                {{-- Batas Akhir Masuk --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Batas Akhir Masuk *
                    </label>
                    <input type="time" name="check_in_end_time" value="{{ old('check_in_end_time', substr($settings->check_in_end_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Setelah jam ini ditutup</p>
                </div>
            </div>
        </div>

        {{-- SECTION 2: JENDELA WAKTU ABSENSI PULANG --}}
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b-2 border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-bold">
                    <i data-lucide="log-out" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Jendela Waktu Absensi Pulang</h2>
                    <p class="text-xs text-gray-400">Atur rentang jam karyawan diizinkan melakukan presensi pulang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Jam Buka Pulang --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Jam Buka Pulang *
                    </label>
                    <input type="time" name="check_out_start_time" value="{{ old('check_out_start_time', substr($settings->check_out_start_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Mulai boleh absen pulang</p>
                </div>

                {{-- Jam Pulang Resmi --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Jam Pulang Resmi *
                    </label>
                    <input type="time" name="work_end_time" value="{{ old('work_end_time', substr($settings->work_end_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-bold text-[#001D5E]">
                    <p class="text-[11px] text-gray-400 mt-1">Target akhir kerja</p>
                </div>

                {{-- Batas Akhir Pulang --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Batas Akhir Pulang *
                    </label>
                    <input type="time" name="check_out_end_time" value="{{ old('check_out_end_time', substr($settings->check_out_end_time, 0, 5)) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Tutup sistem harian</p>
                </div>
            </div>
        </div>

        {{-- SECTION 3: HARI KERJA AKTIF --}}
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b-2 border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-bold">
                    <i data-lucide="calendar" class="w-5 h-5 text-[#F7941D]"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Hari Kerja Aktif</h2>
                    <p class="text-xs text-gray-400">Pilih hari kerja di mana karyawan wajib melakukan absensi (hari di luar ini otomatis libur).</p>
                </div>
            </div>

            @php
                $activeDays = is_array($settings->work_days) ? $settings->work_days : ['monday','tuesday','wednesday','thursday','friday'];
                $allDays = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa',
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                    'saturday' => 'Sabtu',
                    'sunday' => 'Minggu',
                ];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                @foreach($allDays as $k => $label)
                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border-2 {{ in_array($k, $activeDays) ? 'border-[#001D5E] bg-white ring-2 ring-[#001D5E]' : 'border-slate-300 bg-white hover:bg-slate-100' }} cursor-pointer transition-all">
                        <input type="checkbox" name="work_days[]" value="{{ $k }}" {{ in_array($k, $activeDays) ? 'checked' : '' }}
                                class="rounded border-2 border-slate-300 text-[#001D5E] focus:ring-[#001D5E] w-4 h-4 mb-1">
                        <span class="text-xs font-bold text-gray-800">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- SECTION 4: PARAMETER BIOMETRIK AI & SCANNER --}}
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b-2 border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-bold">
                    <i data-lucide="scan-face" class="w-5 h-5 text-[#F7941D]"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Parameter AI Biometrik & Scanner</h2>
                    <p class="text-xs text-gray-400">Pengaturan akurasi pencocokan wajah dan otomatisasi scanner.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Minimal Skor Akurasi --}}
                <div class="p-4 rounded-xl bg-white border-2 border-slate-300 space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-700">Minimal Skor Akurasi Wajah</label>
                        <span id="score-display" class="font-mono font-black text-sm text-[#001D5E] bg-slate-100 px-2 py-0.5 rounded border-2 border-slate-300">{{ $settings->min_face_match_score }}%</span>
                    </div>
                    <input type="range" name="min_face_match_score" min="40" max="95" value="{{ old('min_face_match_score', $settings->min_face_match_score) }}"
                           oninput="document.getElementById('score-display').innerText = this.value + '%'"
                           class="w-full accent-[#001D5E] cursor-pointer">
                    <p class="text-[11px] text-gray-500">Rekomendasi standar: <strong>60% - 70%</strong>. Nilai yang terlalu tinggi mempersulit scan jika sudut wajah sedikit miring.</p>
                </div>

                {{-- Auto-Submit Countdown --}}
                <div class="p-4 rounded-xl bg-white border-2 border-slate-300 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Hitung Mundur Otomatis (Detik)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="auto_submit_countdown" min="1" max="10" value="{{ old('auto_submit_countdown', $settings->auto_submit_countdown) }}" required
                               class="w-24 text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-bold text-gray-800">
                        <span class="text-xs text-gray-500 font-semibold">Detik setelah wajah terverifikasi</span>
                    </div>
                    <p class="text-[11px] text-gray-500">Kamera akan otomatis merekam dan mengirim presensi begitu wajah cocok selama durasi ini.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 5: LOKASI KANTOR & RADIUS GEOLOKASI --}}
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b-2 border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-bold">
                        <i data-lucide="map-pin" class="w-5 h-5 text-[#F7941D]"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Titik Lokasi Kantor & Radius Presensi</h2>
                        <p class="text-xs text-gray-400">Pusat koordinat GPS untuk mendeteksi apakah karyawan absen di kantor atau di luar kantor.</p>
                    </div>
                </div>
                <button type="button" onclick="getCurrentOfficeLocation()"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 text-slate-800 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="crosshair" class="w-4 h-4 text-[#F7941D]"></i>
                    <span>Set Titik Dari GPS Saya</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Nama Kantor --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nama Lokasi / Kantor *
                    </label>
                    <input type="text" name="office_name" value="{{ old('office_name', $settings->office_name ?? 'Kantor Stukka Digital') }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                    <p class="text-[11px] text-gray-400 mt-1">Nama identitas lokasi kantor resmi.</p>
                </div>

                {{-- Radius Toleransi --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Radius Toleransi Kantor (Meter) *
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="office_radius_meters" min="10" max="5000" value="{{ old('office_radius_meters', $settings->office_radius_meters ?? 150) }}" required
                               class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-bold text-gray-800">
                        <span class="text-xs text-slate-600 font-bold shrink-0">Meter</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Jarak maksimal dihitung "Di Kantor" (Rekomendasi: 100 - 200m).</p>
                </div>

                {{-- Latitude --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Latitude Kantor *
                    </label>
                    <input type="text" id="office_lat" name="office_latitude" value="{{ old('office_latitude', $settings->office_latitude ?? -7.25747190) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-mono">
                    <p class="text-[11px] text-gray-400 mt-1">Garis lintang GPS kantor.</p>
                </div>

                {{-- Longitude --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Longitude Kantor *
                    </label>
                    <input type="text" id="office_lng" name="office_longitude" value="{{ old('office_longitude', $settings->office_longitude ?? 112.75208830) }}" required
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 font-mono">
                    <p class="text-[11px] text-gray-400 mt-1">Garis bujur GPS kantor.</p>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-blue-50 border-2 border-blue-200 text-xs text-blue-900 flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                <p>
                    <strong>Ketentuan Presensi Lapangan:</strong> Karyawan yang absen di luar radius ini tetap dapat presensi, namun sistem otomatis menandainya dengan status <strong>"Di Luar Kantor"</strong> beserta rincian titik koordinat dan jaraknya.
                </p>
            </div>
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.attendances.index') }}" 
               class="px-6 py-3.5 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-sm font-bold transition-all">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3.5 rounded-xl bg-[#001D5E] hover:bg-[#001542] border-2 border-[#001138] text-white text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="check" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Simpan Semua Pengaturan</span>
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    function getCurrentOfficeLocation() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung deteksi lokasi (Geolocation).');
            return;
        }

        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="inline-block animate-spin mr-1">⌛</span> Mendeteksi...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                document.getElementById('office_lat').value = pos.coords.latitude.toFixed(8);
                document.getElementById('office_lng').value = pos.coords.longitude.toFixed(8);
                btn.innerHTML = originalText;
                btn.disabled = false;
                if (window.notify) {
                    window.notify.success('Koordinat GPS berhasil diperoleh!', 'Lokasi Kantor');
                } else {
                    alert('Koordinat GPS kantor berhasil diperbarui dari lokasi Anda saat ini!');
                }
                lucide.createIcons();
            },
            (err) => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                alert('Gagal mengambil lokasi GPS: ' + err.message + '. Pastikan izin lokasi diaktifkan.');
                lucide.createIcons();
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }
</script>
@endpush
@endsection
