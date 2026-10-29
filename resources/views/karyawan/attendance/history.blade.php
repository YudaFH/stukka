@extends('layouts.karyawan')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-[#001D5E]">Riwayat Presensi Kehadiran</h1>
        <p class="text-sm text-gray-500 mt-1">
            Catatan rekapan kehadiran Anda beserta foto snapshot dan status.
        </p>
    </div>

    {{-- Filter Bar (Sebaris di Layar Besar, 2 Baris Simetris Seimbang di Layar Sedang/Tablet) --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('karyawan.attendance.history') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-3.5 items-end w-full">
            {{-- Bulan Presensi --}}
            <div class="sm:col-span-1 xl:col-span-5 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bulan Presensi</label>
                <select name="month" 
                        style="min-height: 44px;"
                        class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 bg-white py-2.5 px-3.5 focus:border-[#001D5E] focus:ring-0 font-medium cursor-pointer">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Tahun Presensi --}}
            <div class="sm:col-span-1 xl:col-span-3 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tahun Presensi</label>
                <select name="year" 
                        style="min-height: 44px;"
                        class="w-full text-xs sm:text-sm rounded-xl border-2 border-slate-300 bg-white py-2.5 px-3.5 focus:border-[#001D5E] focus:ring-0 font-medium cursor-pointer">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Tombol Aksi Filter: Mentok kanan --}}
            <div class="sm:col-span-2 xl:col-span-4 w-full flex items-center gap-2">
                <button type="submit" 
                        style="min-height: 44px;"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm transition-all border-2 border-[#001138] cursor-pointer whitespace-nowrap shadow-xs">
                    <i data-lucide="filter" class="w-4 h-4 text-[#F7941D]"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('karyawan.attendance.history') }}" 
                   style="min-height: 44px;"
                   class="inline-flex items-center justify-center py-2.5 px-4 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer whitespace-nowrap shadow-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- KONTEN RIWAYAT: EMPTY ATAU DATA --}}
    @if($attendances->isEmpty())
        <div class="bg-white rounded-2xl border-2 border-slate-300 p-12 text-center text-gray-400">
            <i data-lucide="calendar-x" class="w-10 h-10 mx-auto text-gray-300 mb-2"></i>
            <p class="font-bold text-gray-700 text-sm">Tidak Ada Catatan Presensi</p>
            <p class="text-xs text-gray-500 mt-1">Belum ada absensi pada periode bulan yang dipilih.</p>
        </div>
    @else
        {{-- ============================================== --}}
        {{-- 1. MOBILE VIEW: KARTU LIST RAPI (Layar HP)     --}}
        {{-- ============================================== --}}
        <div class="sm:hidden space-y-3">
            @foreach($attendances as $att)
                <div class="bg-white rounded-2xl border-2 border-slate-300 p-4 space-y-3">
                    {{-- Baris 1: Tanggal & Status --}}
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <span class="text-xs font-bold text-[#001D5E] block">
                                {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d F Y') }}
                            </span>
                            @if($att->face_match_score)
                                <span class="text-xs text-slate-500 font-medium">Skor: {{ $att->face_match_score }}%</span>
                            @endif
                        </div>

                        <div>
                            @php
                                $locStatus = $att->location_status_in ?? $att->location_status_out;
                                $lat = $att->latitude_in ?? $att->latitude_out;
                                $lng = $att->longitude_in ?? $att->longitude_out;
                            @endphp
                            @if($locStatus === 'outside')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-300 mr-1">
                                    📍 Luar Kantor
                                </span>
                            @elseif($locStatus === 'office')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 mr-1">
                                    🏢 Di Kantor
                                </span>
                            @endif

                            @if($att->status === 'on_time')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Tepat Waktu
                                </span>
                            @elseif($att->status === 'late')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#F7941D] text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Terlambat
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-600 text-white">
                                    {{ ucfirst($att->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Baris 2: Waktu Masuk & Pulang + Foto --}}
                    <div class="pt-3 border-t-2 border-slate-100 grid grid-cols-2 gap-3 text-xs">
                        {{-- Presensi Masuk --}}
                        <div class="bg-white p-2.5 rounded-xl border-2 border-slate-300 flex items-center justify-between">
                            <div>
                                <span class="text-xs uppercase font-bold text-slate-400 block">Masuk</span>
                                <span class="font-semibold text-slate-800">{{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}</span>
                            </div>
                            @if($att->photo_in)
                                <a href="{{ asset('storage/' . $att->photo_in) }}" target="_blank" title="Foto Masuk" class="relative group">
                                    <img src="{{ asset('storage/' . $att->photo_in) }}" class="w-9 h-9 rounded-lg object-cover border-2 border-slate-300 shadow-sm">
                                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-600 text-white rounded-full flex items-center justify-center text-[8px] font-bold">IN</span>
                                </a>
                            @endif
                        </div>

                        {{-- Presensi Pulang --}}
                        <div class="bg-white p-2.5 rounded-xl border-2 border-slate-300 flex items-center justify-between">
                            <div>
                                <span class="text-xs uppercase font-bold text-slate-400 block">Pulang</span>
                                <span class="font-semibold text-slate-800">{{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}</span>
                            </div>
                            @if($att->photo_out)
                                <a href="{{ asset('storage/' . $att->photo_out) }}" target="_blank" title="Foto Pulang" class="relative group">
                                    <img src="{{ asset('storage/' . $att->photo_out) }}" class="w-9 h-9 rounded-lg object-cover border-2 border-slate-300 shadow-sm">
                                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-[#F7941D] text-white rounded-full flex items-center justify-center text-[8px] font-bold">OUT</span>
                                </a>
                            @endif
                        </div>
                    </div>
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
                        <tr class="bg-slate-100 border-b-2 border-slate-300 text-xs font-bold text-slate-700 uppercase tracking-wider">
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-5">Jam Masuk</th>
                            <th class="py-3 px-5">Jam Pulang</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5">Lokasi</th>
                            <th class="py-3 px-5">Skor Wajah</th>
                            <th class="py-3 px-5 whitespace-nowrap min-w-[130px]">Bukti Foto</th>
                            <th class="py-3 px-5">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-gray-700">
                        @foreach($attendances as $att)
                            <tr class="hover:bg-slate-100 transition-colors">
                                <td class="py-3 px-5 font-semibold text-[#001D5E]">
                                    {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="py-3 px-5 font-medium text-gray-800 whitespace-nowrap">
                                    {{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-5 font-medium text-gray-800 whitespace-nowrap">
                                    {{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-5 whitespace-nowrap">
                                    @if($att->status === 'on_time')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">
                                            Tepat Waktu
                                        </span>
                                    @elseif($att->status === 'late')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F7941D] text-white">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-white">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 whitespace-nowrap">
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
                                                   class="block text-[10px] text-blue-600 hover:underline font-mono truncate max-w-[130px]" title="{{ $locName ?? 'Peta' }}">
                                                    {{ $locName ? \Illuminate\Support\Str::limit($locName, 18) : "{$lat}, {$lng}" }}
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
                                <td class="py-3 px-5 text-xs font-medium text-gray-600 whitespace-nowrap">
                                    {{ $att->face_match_score ? $att->face_match_score . '%' : '-' }}
                                </td>
                                <td class="py-3 px-5 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3 whitespace-nowrap">
                                        @if($att->photo_in)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <a href="{{ asset('storage/' . $att->photo_in) }}" target="_blank" title="Foto Masuk" 
                                                   class="w-10 h-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px] rounded-xl overflow-hidden border-2 border-emerald-500 shadow-xs hover:scale-110 transition-transform block shrink-0 bg-slate-100">
                                                    <img src="{{ asset('storage/' . $att->photo_in) }}" class="w-full h-full object-cover">
                                                </a>
                                                <span class="text-[9px] font-black uppercase text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-md leading-none border border-emerald-200">IN</span>
                                            </div>
                                        @endif

                                        @if($att->photo_out)
                                            <div class="flex flex-col items-center gap-1 shrink-0">
                                                <a href="{{ asset('storage/' . $att->photo_out) }}" target="_blank" title="Foto Pulang" 
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
                                <td class="py-3 px-5 text-xs text-gray-500 max-w-xs truncate">
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
@endsection
