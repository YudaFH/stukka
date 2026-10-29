@extends('layouts.admin')

@section('title', 'Jadwal Event & Kru')
@section('header_title', 'Jadwal Event Operasional')

@section('content')
<div class="space-y-6">

    {{-- Top Action & Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Jadwal Event Operasional</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola agenda event produksi Stukka, alokasi kru lapangan, dan pantau keuangan proyek.</p>
        </div>
        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            {{-- Unduh Excel --}}
            <a href="{{ route('admin.scheduled-events.export.excel', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm border-2 border-emerald-700 shadow-xs transition-colors whitespace-nowrap"
               title="Unduh rekap data event dan keuangan ke Excel (.xls)">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-white"></i>
                <span>Unduh Excel</span>
            </a>

            {{-- Cetak / Simpan PDF --}}
            <a href="{{ route('admin.scheduled-events.export.pdf', request()->all()) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm border-2 border-[#001138] shadow-xs transition-colors whitespace-nowrap"
               title="Buka laporan cetak event dan simpan PDF">
                <i data-lucide="printer" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Cetak / PDF</span>
            </a>

            <a href="{{ route('admin.scheduled-events.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-xs sm:text-sm border-2 border-[#D67B0F] shadow-md transition-colors whitespace-nowrap shrink-0">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Tambah Jadwal Event</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards (2 Kolom di Layar Sedang/Tablet, 4 Kolom di Layar Besar) --}}
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
        <form method="GET" action="{{ route('admin.scheduled-events.index') }}" class="flex flex-col xl:flex-row items-stretch xl:items-center gap-3 w-full">
            {{-- Status Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto w-full xl:w-auto pb-1 xl:pb-0 shrink-0">
                <a href="{{ route('admin.scheduled-events.index', array_merge(request()->query(), ['status' => null])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ empty($status) ? 'bg-[#001D5E] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.scheduled-events.index', array_merge(request()->query(), ['status' => 'scheduled'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'scheduled' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Scheduled
                </a>
                <a href="{{ route('admin.scheduled-events.index', array_merge(request()->query(), ['status' => 'ongoing'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'ongoing' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Ongoing
                </a>
                <a href="{{ route('admin.scheduled-events.index', array_merge(request()->query(), ['status' => 'completed'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Completed
                </a>
                <a href="{{ route('admin.scheduled-events.index', array_merge(request()->query(), ['status' => 'cancelled'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Cancelled
                </a>
            </div>

            {{-- Input Pencarian, Bulan & Tombol (Mengisi sisa ruang & mentok kanan) --}}
            <div class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full min-w-0">
                {{-- Search Box (Fleksibel) --}}
                <div class="flex-1 w-full relative min-w-0">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama event, klien, atau venue..."
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                {{-- Month & Action Buttons (Mentok Kanan) --}}
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <input type="month" name="month" value="{{ request('month') }}"
                           style="min-height: 44px;"
                           class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    <input type="hidden" name="filter_month" value="1">
                    
                    <button type="submit" 
                            style="min-height: 44px;"
                            class="px-4 py-2 bg-[#001D5E] hover:bg-blue-900 text-white rounded-xl text-xs sm:text-sm font-bold border-2 border-[#001138] transition-all cursor-pointer whitespace-nowrap shadow-xs">
                        Filter
                    </button>
                    @if($search || $status || request('month'))
                        <a href="{{ route('admin.scheduled-events.index') }}" 
                           style="min-height: 44px;"
                           class="px-3.5 py-2 border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap shadow-xs flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Events Grid / List --}}
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
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

                        <a href="{{ route('admin.scheduled-events.show', $event->id) }}" class="block group">
                            <h3 class="text-lg font-black text-[#001D5E] group-hover:text-[#F7941D] transition-colors line-clamp-1">
                                {{ $event->title }}
                            </h3>
                        </a>

                        <div class="mt-3 space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D] shrink-0"></i>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('D MMMM Y') }}
                                    @if($event->end_date && $event->end_date !== $event->start_date)
                                        - {{ \Carbon\Carbon::parse($event->end_date)->isoFormat('D MMMM Y') }}
                                    @endif
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
                            <div class="flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                <span class="font-semibold text-slate-700 truncate">{{ $event->client_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Financial & Crew Mini Progress --}}
                    <div class="p-5 bg-slate-50/70 border-b border-slate-100 space-y-3">
                        {{-- Nilai Kontrak & Pembayaran Masuk --}}
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-slate-500 font-medium">Pembayaran Klien</span>
                                <span class="font-black text-[#001D5E]">
                                    Rp {{ number_format($event->total_paid, 0, ',', '.') }} 
                                    <span class="text-slate-500 font-normal">/ {{ number_format($event->contract_amount, 0, ',', '.') }}</span>
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300
                                    @if($event->payment_progress_percentage >= 100) bg-emerald-500
                                    @elseif($event->payment_progress_percentage > 0) bg-[#F7941D]
                                    @else bg-slate-300 @endif"
                                    style="width: {{ min(100, $event->payment_progress_percentage) }}%">
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                <span>Status: 
                                    <strong class="@if($event->client_payment_status === 'paid') text-emerald-600 @elseif($event->client_payment_status === 'partial') text-amber-600 @else text-rose-600 @endif">
                                        @if($event->client_payment_status === 'paid') Lunas
                                        @elseif($event->client_payment_status === 'partial') DP/Termin
                                        @else Belum Bayar @endif
                                    </strong>
                                </span>
                                <span>{{ $event->payment_progress_percentage }}%</span>
                            </div>
                        </div>

                        {{-- Biaya Produksi vs Laba --}}
                        <div class="pt-2 border-t border-slate-200/60 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-slate-600 text-[11px] block">Biaya Produksi:</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($event->total_expense, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-600 text-[11px] block">Laba Bersih:</span>
                                <span class="font-bold @if($event->net_profit >= 0) text-emerald-600 @else text-rose-600 @endif">
                                    Rp {{ number_format($event->net_profit, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Kru Bertugas --}}
                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-slate-500">Kru Ditugaskan:</span>
                            <div class="flex items-center -space-x-2">
                                @forelse($event->crewAssignments->take(4) as $assignment)
                                    <div class="w-6 h-6 rounded-full text-white text-[10px] font-bold flex items-center justify-center border-2 border-white {{ $assignment->isInternal() ? 'bg-[#001D5E]' : 'bg-[#F7941D]' }}" title="{{ $assignment->crew_name }} ({{ $assignment->role_in_event }})">
                                        {{ substr($assignment->crew_name, 0, 1) }}
                                    </div>
                                @empty
                                    <span class="text-[11px] text-slate-600 italic">Belum ada kru</span>
                                @endforelse
                                @if($event->crewAssignments->count() > 4)
                                    <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-[9px] font-bold flex items-center justify-center border-2 border-white">
                                        +{{ $event->crewAssignments->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="p-4 bg-white flex items-center justify-between gap-2">
                        <a href="{{ route('admin.scheduled-events.show', $event->id) }}" 
                           class="flex-1 text-center py-2 px-3 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs transition-colors">
                            Detail & Keuangan
                        </a>
                        <a href="{{ route('admin.scheduled-events.edit', $event->id) }}" 
                           class="p-2 rounded-xl border-2 border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-400 transition-colors" title="Edit Jadwal">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.scheduled-events.destroy', $event->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal event \'{{ $event->title }}\' beserta seluruh data keuangannya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl border-2 border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-400 transition-colors" title="Hapus Event">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border-2 border-slate-200 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-[#001D5E] text-white shadow-md flex items-center justify-center mx-auto mb-4">
                <i data-lucide="calendar-x" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Belum Ada Jadwal Event</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                Mulai jadwalkan event operasional Stukka untuk mengalokasikan kru lapangan dan menghitung biaya produksi.
            </p>
            <div class="mt-6">
                <a href="{{ route('admin.scheduled-events.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-sm shadow-md transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Buat Jadwal Event Pertama</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
