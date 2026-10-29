@extends('layouts.karyawan')

@section('title', 'Jadwal Event & Kru')
@section('header_title', 'Jadwal Event Operasional')

@section('content')
<div class="space-y-6">

    {{-- Top Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Jadwal Event Operasional</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau agenda event produksi Stukka, alokasi kru lapangan, dan rundown acara on-site.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 whitespace-nowrap shrink-0">
                <i data-lucide="user-check" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Tugas Anda: <strong class="text-[#001D5E]">{{ $stats['my'] }} Event</strong></span>
            </span>
        </div>
    </div>

    {{-- 4 Stat Cards (2 Kolom di Layar Sedang/Tablet, 4 Kolom di Layar Besar) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold text-slate-500 uppercase truncate">Total Event</span>
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-[#001D5E] mt-3">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-1 truncate">Keseluruhan agenda</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold text-blue-600 uppercase truncate">Terjadwal</span>
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white shadow-sm flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-blue-600 mt-3">{{ $stats['scheduled'] }}</p>
            <p class="text-xs text-slate-500 mt-1 truncate">Akan datang</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold text-amber-600 uppercase truncate">Berlangsung</span>
                <div class="w-9 h-9 rounded-xl bg-[#F7941D] text-white shadow-sm flex items-center justify-center shrink-0">
                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-amber-600 mt-3">{{ $stats['ongoing'] }}</p>
            <p class="text-xs text-slate-500 mt-1 truncate">Sedang on-site</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold text-emerald-600 uppercase truncate">Selesai</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white shadow-sm flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-emerald-600 mt-3">{{ $stats['completed'] }}</p>
            <p class="text-xs text-slate-500 mt-1 truncate">Proyek tuntas</p>
        </div>
    </div>

    {{-- Filter & Search Bar (Responsif Sempurna di Layar Sedang & Besar) --}}
    <div class="bg-white p-4 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('karyawan.events.index') }}" class="flex flex-col xl:flex-row items-stretch xl:items-center gap-3 w-full">
            {{-- Tabs Filter --}}
            <div class="flex items-center gap-1.5 overflow-x-auto w-full xl:w-auto pb-1 xl:pb-0 shrink-0">
                <a href="{{ route('karyawan.events.index', array_merge(request()->query(), ['tab' => 'all', 'status' => null])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ ($tab === 'all' && empty($status)) ? 'bg-[#001D5E] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>

                <a href="{{ route('karyawan.events.index', array_merge(request()->query(), ['tab' => 'my', 'status' => null])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors flex items-center gap-1.5 {{ $tab === 'my' ? 'bg-[#F7941D] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    <span>Tugas Saya</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-black {{ $tab === 'my' ? 'bg-white text-orange-600' : 'bg-slate-200 text-slate-700' }}">
                        {{ $stats['my'] }}
                    </span>
                </a>

                <a href="{{ route('karyawan.events.index', array_merge(request()->query(), ['status' => 'scheduled'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'scheduled' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Scheduled
                </a>
                <a href="{{ route('karyawan.events.index', array_merge(request()->query(), ['status' => 'ongoing'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'ongoing' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Ongoing
                </a>
                <a href="{{ route('karyawan.events.index', array_merge(request()->query(), ['status' => 'completed'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Completed
                </a>
            </div>

            {{-- Input Pencarian, Bulan & Tombol (Mengisi sisa ruang & mentok kanan) --}}
            <div class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full min-w-0">
                {{-- Search Input (Stretches) --}}
                <div class="flex-1 w-full relative min-w-0">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama event, klien, atau venue..."
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                {{-- Month Filter & Button (Mentok Kanan) --}}
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <input type="month" name="month" value="{{ request('month') }}"
                           style="min-height: 44px;"
                           class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    
                    <button type="submit" 
                            style="min-height: 44px;"
                            class="px-4 py-2 bg-[#001D5E] hover:bg-blue-900 text-white rounded-xl text-xs sm:text-sm font-bold border-2 border-[#001138] transition-all cursor-pointer whitespace-nowrap shadow-xs">
                        Filter
                    </button>

                    @if(!empty($search) || !empty($status) || !empty($month) || $tab === 'my')
                        <a href="{{ route('karyawan.events.index') }}" 
                           style="min-height: 44px;"
                           class="px-3.5 py-2 border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap shadow-xs flex items-center justify-center" 
                           title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Event Cards Grid --}}
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($events as $event)
                @php
                    $isAssigned = $event->crew->contains('id', $user->id);
                    $myRole = $isAssigned ? $event->crew->firstWhere('id', $user->id)->pivot->role_in_event : null;
                @endphp
                <div class="bg-white rounded-2xl border-2 {{ $isAssigned ? 'border-orange-400 shadow-md ring-2 ring-orange-400/20' : 'border-slate-200 shadow-sm' }} hover:border-slate-300 transition-all flex flex-col justify-between overflow-hidden">
                    
                    {{-- Header Card --}}
                    <div class="p-5 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wide text-white shadow-xs
                                @if($event->status === 'scheduled') bg-blue-600
                                @elseif($event->status === 'ongoing') bg-[#F7941D]
                                @elseif($event->status === 'completed') bg-emerald-600
                                @else bg-rose-600 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="text-xs font-black text-white bg-[#001D5E] px-2.5 py-1 rounded-md">
                                {{ $event->event_type }}
                            </span>
                        </div>

                        <a href="{{ route('karyawan.events.show', $event->id) }}" class="block group">
                            <h3 class="text-lg font-black text-[#001D5E] group-hover:text-[#F7941D] transition-colors line-clamp-1">
                                {{ $event->title }}
                            </h3>
                        </a>
                        <p class="text-xs text-slate-500 mt-0.5">Klien: <strong class="text-slate-700">{{ $event->client_name }}</strong></p>
                    </div>

                    {{-- Body Card --}}
                    <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                        
                        {{-- Peran Karyawan jika ditugaskan --}}
                        @if($isAssigned)
                            <div class="p-3 rounded-xl bg-[#F7941D] text-white flex items-center gap-2.5 shadow-xs">
                                <div class="w-7 h-7 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-black text-orange-100 uppercase tracking-wider block">Tugas Anda di Lapangan</span>
                                    <span class="text-xs font-black text-white truncate block">{{ $myRole ?? 'Crew' }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- Info Waktu & Tempat --}}
                        <div class="space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D] shrink-0"></i>
                                <span class="font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('dddd, D MMMM Y') }}
                                </span>
                            </div>
                            @if($event->start_time)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <span>{{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 'WIB' }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $event->location }} {{ $event->venue_detail ? "({$event->venue_detail})" : '' }}</span>
                            </div>
                        </div>

                        {{-- Kru Lapangan --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-500">Kru Bertugas ({{ $event->crew->count() }}):</span>
                            <div class="flex items-center -space-x-2">
                                @forelse($event->crew->take(4) as $c)
                                    <div class="w-6 h-6 rounded-full bg-[#001D5E] text-white text-[9px] font-bold flex items-center justify-center border-2 border-white" title="{{ $c->name }} ({{ $c->pivot->role_in_event }})">
                                        {{ substr($c->name, 0, 1) }}
                                    </div>
                                @empty
                                    <span class="text-[11px] text-slate-400 italic">Belum ada kru</span>
                                @endforelse
                                @if($event->crew->count() > 4)
                                    <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-[8px] font-bold flex items-center justify-center border-2 border-white">
                                        +{{ $event->crew->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Footer Button --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('karyawan.events.show', $event->id) }}" 
                           class="w-full text-center py-2 px-3 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 shadow-xs">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Lihat Rundown & Detail Tim</span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-12 text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-[#001D5E] text-white shadow-md flex items-center justify-center mx-auto mb-2">
                <i data-lucide="calendar-x" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">
                @if($tab === 'my')
                    Belum Ada Tugas Event untuk Anda
                @else
                    Belum Ada Jadwal Event
                @endif
            </h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                @if($tab === 'my')
                    Admin belum mengalokasikan Anda sebagai kru event di agenda saat ini. Anda dapat beralih ke tab <strong>Semua</strong> untuk melihat seluruh agenda produksi.
                @else
                    Tidak ada agenda event operasional yang cocok dengan kriteria pencarian Anda.
                @endif
            </p>
            @if($tab === 'my')
                <div class="pt-2">
                    <a href="{{ route('karyawan.events.index', ['tab' => 'all']) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#001D5E] text-white font-bold text-xs hover:bg-blue-900 transition-colors shadow-xs">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span>Lihat Semua Agenda Stukka</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
