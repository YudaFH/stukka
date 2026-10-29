@extends('layouts.admin')

@section('title', 'Pusat Kendali Admin')

@section('content')
<div class="space-y-6">

    {{-- Welcome & Overview Banner --}}
    <div class="bg-[#001D5E] border-2 border-[#001138] rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-md">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-orange-300 border border-white/20 text-xs font-black uppercase tracking-wider mb-2">
                Stukka Command Center
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1">
                Pusat kendali operasional Stukka: pantau jadwal event on-site, performa keuangan proyek, kehadiran staf hari ini, dan reservasi klien.
            </p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-6 translate-y-6">
            <i data-lucide="layout-dashboard" class="w-56 h-56 text-white"></i>
        </div>
    </div>

    {{-- 4 METRIK UTAMA OPERASIONAL (Solid Icons & High-Contrast Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. Jadwal Event Aktif --}}
        <a href="{{ route('admin.scheduled-events.index') }}" 
           class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Event Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-[#001D5E] group-hover:text-[#F7941D] transition-colors mt-3">
                {{ $activeEventsCount }} Event
            </p>
            <p class="text-xs text-slate-500 mt-1">Terjadwal & sedang on-site</p>
        </a>

        {{-- 2. Total Laba Bersih --}}
        <a href="{{ route('admin.finance.index') }}" 
           class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Laba Bersih</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black @if($totalNetProfit >= 0) text-emerald-600 @else text-rose-600 @endif mt-3">
                Rp {{ number_format($totalNetProfit, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-500 mt-1">Margin rata-rata: <strong class="text-slate-700">{{ $globalMargin }}%</strong></p>
        </a>

        {{-- 3. Presensi Karyawan Hari Ini --}}
        <a href="{{ route('admin.attendances.index') }}" 
           class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Presensi Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="scan-face" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-[#001D5E] mt-3">
                {{ $presentCount }} / {{ $totalEmployees }} <span class="text-sm font-bold text-slate-500">Staf</span>
            </p>
            <p class="text-xs text-slate-500 mt-1">
                <span class="text-emerald-600 font-bold">{{ $onTimeCount }} Tepat</span> • 
                <span class="text-amber-600 font-bold">{{ $lateCount }} Terlambat</span>
            </p>
        </a>

        {{-- 4. Manajemen Karyawan --}}
        <a href="{{ route('admin.employees.index') }}" 
           class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Karyawan Stukka</span>
                <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-[#F7941D]"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-[#001D5E] mt-3">
                {{ $totalEmployees }} <span class="text-sm font-bold text-slate-500">Staf</span>
            </p>
            <p class="text-xs text-slate-500 mt-1">Kelola data & biometrik</p>
        </a>
    </div>

    {{-- DUA KOLOM SEIMBANG: JADWAL EVENT & LIVE PRESENSI --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 1. Jadwal Event Terdekat & Berlangsung --}}
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm p-6 space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#001D5E] text-white flex items-center justify-center shadow-xs">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-[#001D5E]">Jadwal Event Mendatang & On-Site</h2>
                            <p class="text-xs text-slate-500">Agenda terdekat yang sedang atau akan berjalan.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.scheduled-events.index') }}" 
                       class="text-xs font-bold text-[#001D5E] hover:text-[#F7941D] transition-colors inline-flex items-center gap-1">
                        <span>Semua Event</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                @if($upcomingEvents->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingEvents as $ev)
                            <div class="p-4 rounded-2xl border-2 border-slate-200 hover:border-slate-300 transition-all bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="space-y-1.5 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase text-white shadow-xs
                                            @if($ev->status === 'ongoing') bg-[#F7941D]
                                            @else bg-blue-600 @endif">
                                            {{ ucfirst($ev->status) }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-[#001D5E] text-white">
                                            {{ $ev->event_type }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-black text-[#001D5E] truncate">
                                        {{ $ev->title }}
                                    </h3>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                                        <span class="font-bold text-slate-700">
                                            {{ \Carbon\Carbon::parse($ev->start_date)->isoFormat('D MMMM Y') }}
                                        </span>
                                        <span>•</span>
                                        <span class="truncate">{{ $ev->location }}</span>
                                        <span>•</span>
                                        <span class="truncate">Klien: <strong class="text-slate-800">{{ $ev->client_name }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-2 pt-1">
                                        <span class="text-[11px] font-bold text-slate-500">Kru:</span>
                                        <div class="flex items-center -space-x-1.5">
                                            @forelse($ev->crew->take(3) as $c)
                                                <div class="w-5 h-5 rounded-full bg-[#001D5E] text-white text-[9px] font-bold flex items-center justify-center border border-white" title="{{ $c->name }} ({{ $c->pivot->role_in_event }})">
                                                    {{ substr($c->name, 0, 1) }}
                                                </div>
                                            @empty
                                                <span class="text-[10px] text-slate-400 italic">Belum ada kru</span>
                                            @endforelse
                                            @if($ev->crew->count() > 3)
                                                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[8px] font-bold flex items-center justify-center border border-white">
                                                    +{{ $ev->crew->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    <a href="{{ route('admin.scheduled-events.show', $ev->id) }}" 
                                       class="px-3.5 py-2 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs transition-colors">
                                        Detail & Keuangan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 rounded-2xl border-2 border-dashed border-slate-200 text-center text-slate-500 text-xs">
                        <div class="w-12 h-12 rounded-xl bg-[#001D5E] text-white flex items-center justify-center mx-auto mb-2 shadow-xs">
                            <i data-lucide="calendar-x" class="w-6 h-6"></i>
                        </div>
                        <p class="font-bold text-slate-700">Tidak ada jadwal event mendatang saat ini.</p>
                        <a href="{{ route('admin.scheduled-events.create') }}" class="inline-flex items-center gap-1.5 mt-3 px-3.5 py-1.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-xs shadow-xs transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Tambah Jadwal Event Baru</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Presensi Staf Hari Ini --}}
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm p-6 space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-xs">
                            <i data-lucide="scan-face" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-[#001D5E]">Presensi Staf Hari Ini</h2>
                            <p class="text-xs text-slate-500">Log kehadiran biometrik karyawan.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.attendances.index') }}" 
                       class="text-xs font-bold text-[#001D5E] hover:text-[#F7941D] transition-colors inline-flex items-center gap-1">
                        <span>Rekap Lengkap</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                {{-- Daftar Hadir Terbaru --}}
                @if($recentAttendances->count() > 0)
                    <div class="space-y-2 pt-1">
                        @foreach($recentAttendances as $att)
                            <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-[#001D5E] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($att->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate">{{ $att->user->name ?? 'Staff' }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $att->user->position ?? 'Karyawan' }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="flex items-center justify-end gap-1 mb-0.5">
                                        <span class="font-black text-slate-900">{{ substr($att->time_in, 0, 5) }} WIB</span>
                                        @if(($att->location_status_in ?? $att->location_status_out) === 'outside')
                                            <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded text-orange-800 bg-orange-100 border border-orange-300">Luar</span>
                                        @elseif(($att->location_status_in ?? $att->location_status_out) === 'office')
                                            <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded text-emerald-800 bg-emerald-100 border border-emerald-300">Kantor</span>
                                        @endif
                                    </div>
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded text-white shadow-xs {{ $att->status === 'late' ? 'bg-[#F7941D]' : 'bg-emerald-600' }}">
                                        {{ $att->status === 'late' ? 'Terlambat' : 'Tepat Waktu' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 rounded-2xl border-2 border-dashed border-slate-200 text-center text-slate-500 text-xs">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center mx-auto mb-2 shadow-xs">
                            <i data-lucide="scan-face" class="w-6 h-6"></i>
                        </div>
                        <p class="font-bold text-slate-700">Belum ada karyawan yang absen hari ini.</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">Kehadiran staf yang memindai wajah akan tercatat di sini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Ringkasan Portofolio & Konten Publik --}}
    <div class="p-4 rounded-2xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between text-xs gap-3">
        <span class="font-bold text-slate-600">Konten Portofolio Website:</span>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}" class="font-bold text-[#001D5E] hover:text-[#F7941D]">
                <strong>{{ $totalProjects }}</strong> Project
            </a>
            <span>•</span>
            <a href="{{ route('admin.clients.index') }}" class="font-bold text-[#001D5E] hover:text-[#F7941D]">
                <strong>{{ $totalClients }}</strong> Client
            </a>
            <span>•</span>
            <a href="{{ route('admin.testimonials.index') }}" class="font-bold text-[#001D5E] hover:text-[#F7941D]">
                <strong>{{ $totalTestimonials }}</strong> Testimoni
            </a>
        </div>
    </div>

</div>
@endsection
