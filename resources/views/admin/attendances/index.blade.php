@extends('layouts.admin')

@section('title', 'Rekap Absensi Karyawan')

@section('content')
<div class="space-y-6">

    {{-- 1. HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Rekap Presensi Karyawan</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pantau kehadiran harian, keterlambatan, verifikasi GPS lokasi, dan unduh laporan resmi.
            </p>
        </div>

        {{-- Tombol Aksi & Export --}}
        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            {{-- Unduh Excel --}}
            <a href="{{ route('admin.attendances.export.excel', request()->all()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm border-2 border-emerald-700 transition-colors shadow-xs whitespace-nowrap"
               title="Unduh data sesuai filter ke format Microsoft Excel (.xls)">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-white"></i>
                <span>Unduh Excel</span>
            </a>

            {{-- Cetak / Simpan PDF --}}
            <a href="{{ route('admin.attendances.export.pdf', request()->all()) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm border-2 border-[#001138] transition-colors shadow-xs whitespace-nowrap"
               title="Buka laporan format cetak A4 dan simpan sebagai PDF">
                <i data-lucide="printer" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Cetak / PDF</span>
            </a>

            {{-- Tombol Pengaturan Jam Kerja --}}
            <a href="{{ route('admin.attendances.settings.view') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm border-2 border-slate-300 transition-colors shadow-xs whitespace-nowrap">
                <i data-lucide="settings-2" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Aturan Jam</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-white border-2 border-emerald-600 text-emerald-950 text-sm font-bold flex items-center gap-2">
            <span class="p-1 rounded bg-emerald-600 text-white flex items-center justify-center">
                <i data-lucide="check" class="w-4 h-4"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 2. STATS HARI INI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Karyawan</p>
            <p class="text-3xl font-black text-[#001D5E] mt-1">{{ $totalEmployees }} <span class="text-xs font-semibold text-gray-400">Orang</span></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hadir Hari Ini</p>
            <p class="text-3xl font-black text-blue-600 mt-1">{{ $todayPresent }} <span class="text-xs font-semibold text-gray-400">Presensi</span></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tepat Waktu Hari Ini</p>
            <p class="text-3xl font-black text-emerald-600 mt-1">{{ $todayOnTime }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Terlambat Hari Ini</p>
            <p class="text-3xl font-black text-amber-600 mt-1">{{ $todayLate }}</p>
        </div>
    </div>

    {{-- 3. FILTER BAR --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                {{-- Tanggal Tunggal --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Tanggal Spesifik</label>
                    <input type="date" name="date" value="{{ $date }}"
                           style="min-height: 44px;"
                           class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                </div>

                {{-- Rentang Tanggal Mulai --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           style="min-height: 44px;"
                           placeholder="Mulai..."
                           class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                </div>

                {{-- Rentang Tanggal Selesai --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           style="min-height: 44px;"
                           placeholder="Sampai..."
                           class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                </div>

                {{-- Karyawan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Karyawan</label>
                    <select name="user_id" 
                            style="min-height: 44px;"
                            class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status & Tombol Filter --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Status Presensi</label>
                    <select name="status" 
                            style="min-height: 44px;"
                            class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0">
                        <option value="">Semua Status</option>
                        <option value="on_time" {{ $status === 'on_time' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="late" {{ $status === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="overtime" {{ $status === 'overtime' ? 'selected' : '' }}>Lembur / Tugas</option>
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 flex-wrap gap-2">
                <div class="text-xs text-slate-500 font-semibold">
                    *Gunakan Tanggal Spesifik untuk cek harian, atau isi Dari/Sampai untuk rekap mingguan/bulanan.
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" 
                            style="min-height: 42px;"
                            class="inline-flex items-center justify-center gap-2 py-2 px-5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white text-xs sm:text-sm font-bold border-2 border-[#001138] transition-all cursor-pointer whitespace-nowrap shadow-xs">
                        <i data-lucide="filter" class="w-4 h-4 text-[#F7941D]"></i>
                        <span>Terapkan Filter</span>
                    </button>
                    <a href="{{ route('admin.attendances.index') }}" 
                       style="min-height: 42px;"
                       class="inline-flex items-center justify-center py-2 px-4 rounded-xl border-2 border-slate-300 bg-white text-slate-700 hover:bg-slate-100 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap shadow-xs" 
                       title="Reset Filter">
                        Reset
                    </a>
                </div>
            </div>

        </form>
    </div>
    </div>

    {{-- 4. KONTEN REKAP: EMPTY ATAU DATA --}}
    @if($attendances->isEmpty())
        <div class="bg-white rounded-2xl border-2 border-slate-300 p-12 text-center text-gray-400">
            <i data-lucide="calendar-x" class="w-10 h-10 mx-auto text-gray-300 mb-2"></i>
            <p class="font-bold text-gray-700 text-sm">Tidak Ada Data Presensi</p>
            <p class="text-xs text-gray-500 mt-1">Tidak ditemukan data absensi untuk filter tanggal ini.</p>
        </div>
    @else
        {{-- ============================================== --}}
        {{-- 1. MOBILE VIEW: KARTU LIST RAPI (Layar HP)     --}}
        {{-- ============================================== --}}
        <div class="sm:hidden space-y-3">
            @foreach($attendances as $att)
                <div class="bg-white rounded-2xl border-2 border-slate-300 p-4 space-y-3">
                    {{-- Baris 1: Karyawan & Status Badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($att->user && $att->user->face_photo)
                                <img src="{{ asset('storage/' . $att->user->face_photo) }}" class="w-10 h-10 rounded-xl object-cover border-2 border-slate-300 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white font-black text-sm flex items-center justify-center flex-shrink-0 border-2 border-[#001D5E]">
                                    {{ $att->user ? substr($att->user->name, 0, 1) : '?' }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="font-black text-sm text-[#001D5E] truncate leading-tight">{{ $att->user->name ?? 'User Terhapus' }}</h4>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $att->user->position ?? 'Karyawan' }}</p>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Status Badge (Solid Filled) --}}
                        <div class="flex-shrink-0">
                            @if($att->status === 'on_time')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-600 text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Tepat Waktu
                                </span>
                            @elseif($att->status === 'late')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-[#F7941D] text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Terlambat
                                </span>
                            @elseif($att->status === 'overtime')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-[#001D5E] text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#F7941D]"></span>
                                    Lembur / Tugas
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-700 text-white">
                                    {{ ucfirst($att->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Baris 2: Waktu Masuk & Pulang + Foto Bukti --}}
                    <div class="pt-3 border-t-2 border-slate-100 grid grid-cols-2 gap-3 text-xs">
                        {{-- Presensi Masuk --}}
                        <div class="bg-white p-2.5 rounded-xl border-2 border-slate-300 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Masuk</span>
                                <span class="font-mono font-bold text-slate-800">{{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}</span>
                            </div>
                            @if($att->photo_in)
                                <button type="button" onclick="previewImage('{{ asset('storage/' . $att->photo_in) }}', 'Absen Masuk: {{ $att->user->name ?? '' }} ({{ $att->time_in }})')" 
                                        class="group relative" title="Lihat Foto Masuk">
                                    <img src="{{ asset('storage/' . $att->photo_in) }}" class="w-9 h-9 rounded-lg object-cover border-2 border-slate-300">
                                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-600 text-white rounded-full flex items-center justify-center text-[7px] font-black">IN</span>
                                </button>
                            @endif
                        </div>

                        {{-- Presensi Pulang --}}
                        <div class="bg-white p-2.5 rounded-xl border-2 border-slate-300 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Pulang</span>
                                <span class="font-mono font-bold text-slate-800">{{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}</span>
                            </div>
                            @if($att->photo_out)
                                <button type="button" onclick="previewImage('{{ asset('storage/' . $att->photo_out) }}', 'Absen Pulang: {{ $att->user->name ?? '' }} ({{ $att->time_out }})')" 
                                        class="group relative" title="Lihat Foto Pulang">
                                    <img src="{{ asset('storage/' . $att->photo_out) }}" class="w-9 h-9 rounded-lg object-cover border-2 border-slate-300">
                                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-[#F7941D] text-white rounded-full flex items-center justify-center text-[7px] font-black">OUT</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Baris 3: Skor Cocok Wajah & Catatan --}}
                    @if($att->face_match_score || $att->notes)
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            @if($att->face_match_score)
                                <span>Skor Biometrik: <strong class="text-[#001D5E] font-mono">{{ $att->face_match_score }}%</strong></span>
                            @endif
                            @if($att->notes)
                                <span class="truncate max-w-[180px] italic">{{ $att->notes }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ============================================== --}}
        {{-- 2. DESKTOP VIEW: TABEL LENGKAP (Layar Lebar)   --}}
        {{-- ============================================== --}}
        <div class="hidden sm:block bg-white rounded-2xl border-2 border-slate-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100 border-b-2 border-slate-300 text-xs font-black text-slate-700 uppercase tracking-wider">
                            <th class="py-3 px-4">Karyawan</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Masuk</th>
                            <th class="py-3 px-4">Pulang</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">Skor Wajah</th>
                            <th class="py-3 px-4">Foto Bukti</th>
                            <th class="py-3 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-gray-700">
                        @foreach($attendances as $att)
                            <tr class="hover:bg-slate-100 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($att->user && $att->user->face_photo)
                                            <img src="{{ asset('storage/' . $att->user->face_photo) }}" class="w-9 h-9 rounded-lg object-cover border-2 border-slate-300 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-lg bg-[#001D5E] text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                                                {{ $att->user ? substr($att->user->name, 0, 1) : '?' }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-bold text-sm text-gray-900 leading-tight truncate">{{ $att->user->name ?? 'User Terhapus' }}</p>
                                            <p class="text-xs text-gray-400 truncate leading-tight">{{ $att->user->position ?? 'Karyawan' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-[#001D5E] text-xs whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 px-4 font-mono text-xs whitespace-nowrap">
                                    {{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-4 font-mono text-xs whitespace-nowrap">
                                    {{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($att->status === 'on_time')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">
                                            Tepat Waktu
                                        </span>
                                    @elseif($att->status === 'late')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F7941D] text-white">
                                            Terlambat
                                        </span>
                                    @elseif($att->status === 'overtime')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#001D5E] text-white">
                                            Lembur / Tugas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-white">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $locStatus = $att->location_status_in ?? $att->location_status_out;
                                        $lat = $att->latitude_in ?? $att->latitude_out;
                                        $lng = $att->longitude_in ?? $att->longitude_out;
                                        $locName = $att->location_name_in ?? $att->location_name_out;
                                    @endphp
                                    @if($locStatus === 'outside')
                                        <div class="space-y-0.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-black bg-orange-100 text-orange-800 border border-orange-300">
                                                📍 Di Luar Kantor
                                            </span>
                                            @if($lat && $lng)
                                                <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" target="_blank"
                                                   class="block text-[10px] text-blue-600 hover:underline font-mono truncate max-w-[140px]" title="{{ $locName ?? 'Buka Peta' }}">
                                                    {{ $locName ? \Illuminate\Support\Str::limit($locName, 20) : "{$lat}, {$lng}" }}
                                                </a>
                                            @endif
                                        </div>
                                    @elseif($locStatus === 'office')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            🏢 Di Kantor
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono text-xs text-gray-500 whitespace-nowrap">
                                    {{ $att->face_match_score ? $att->face_match_score . '%' : '-' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3 whitespace-nowrap">
                                        @if($att->photo_in)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <button type="button" onclick="previewImage('{{ asset('storage/' . $att->photo_in) }}', 'Absen Masuk: {{ $att->user->name ?? '' }} ({{ $att->time_in }})')" 
                                                        class="w-10 h-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px] rounded-xl overflow-hidden border-2 border-emerald-500 shadow-xs hover:scale-110 transition-transform block shrink-0 bg-slate-100 cursor-pointer" title="Lihat Foto Masuk">
                                                    <img src="{{ asset('storage/' . $att->photo_in) }}" class="w-full h-full object-cover">
                                                </button>
                                                <span class="text-[9px] font-black uppercase text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-md leading-none border border-emerald-200">IN</span>
                                            </div>
                                        @endif

                                        @if($att->photo_out)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <button type="button" onclick="previewImage('{{ asset('storage/' . $att->photo_out) }}', 'Absen Pulang: {{ $att->user->name ?? '' }} ({{ $att->time_out }})')" 
                                                        class="w-10 h-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px] rounded-xl overflow-hidden border-2 border-[#F7941D] shadow-xs hover:scale-110 transition-transform block shrink-0 bg-slate-100 cursor-pointer" title="Lihat Foto Pulang">
                                                    <img src="{{ asset('storage/' . $att->photo_out) }}" class="w-full h-full object-cover">
                                                </button>
                                                <span class="text-[9px] font-black uppercase text-orange-700 bg-orange-100 px-1.5 py-0.5 rounded-md leading-none border border-orange-200">OUT</span>
                                            </div>
                                        @endif

                                        @if(!$att->photo_in && !$att->photo_out)
                                            <span class="text-xs text-slate-400 font-bold">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-500 max-w-xs truncate">
                                    {{ $att->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($attendances->hasPages())
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300">
                {{ $attendances->links() }}
            </div>
        @endif
    @endif

</div>

{{-- MODAL PREVIEW FOTO --}}
<div id="modal-preview" class="fixed inset-0 bg-black/75 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden border-2 border-slate-300 space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 id="modal-title" class="font-bold text-[#001D5E] text-sm">Bukti Foto Presensi</h3>
            <button type="button" onclick="document.getElementById('modal-preview').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <img id="modal-img" src="" alt="Preview" class="w-full aspect-[4/3] object-cover rounded-xl border border-gray-200">
    </div>
</div>

{{-- MODAL SETTING JAM KERJA --}}
<div id="modal-settings" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="font-black text-[#001D5E] text-base flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-[#F7941D]"></i>
                Pengaturan Jam Kerja
            </h3>
            <button type="button" onclick="document.getElementById('modal-settings').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.attendances.settings') }}" class="space-y-4 pt-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Jam Masuk Kerja *</label>
                <input type="time" name="work_start_time" value="{{ $settings->work_start_time }}" required
                       class="w-full text-sm rounded-xl border-gray-200 focus:border-[#F7941D] focus:ring-[#F7941D]">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Toleransi Keterlambatan (Menit) *</label>
                <input type="number" name="late_tolerance_minutes" value="{{ $settings->late_tolerance_minutes }}" min="0" max="120" required
                       class="w-full text-sm rounded-xl border-gray-200 focus:border-[#F7941D] focus:ring-[#F7941D]">
                <p class="text-xs text-gray-400 mt-1">Presensi setelah jam masuk + menit ini dihitung terlambat.</p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Jam Pulang Kerja *</label>
                <input type="time" name="work_end_time" value="{{ $settings->work_end_time }}" required
                       class="w-full text-sm rounded-xl border-gray-200 focus:border-[#F7941D] focus:ring-[#F7941D]">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-settings').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white text-xs font-bold shadow transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(url, title) {
    document.getElementById('modal-img').src = url;
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-preview').classList.remove('hidden');
}
</script>
@endsection
