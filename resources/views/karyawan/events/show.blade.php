@extends('layouts.karyawan')

@section('title', 'Detail Event: ' . $event->title)
@section('header_title', 'Detail Event Kru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div>
        <a href="{{ route('karyawan.events.index') }}" 
           class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-[#001D5E] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Jadwal Event</span>
        </a>
    </div>

    {{-- Main Banner --}}
    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 md:p-8 space-y-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-lg text-xs font-black uppercase
                @if($event->status === 'scheduled') bg-blue-100 text-blue-700
                @elseif($event->status === 'ongoing') bg-amber-100 text-amber-700
                @elseif($event->status === 'completed') bg-emerald-100 text-emerald-700
                @else bg-rose-100 text-rose-700 @endif">
                {{ ucfirst($event->status) }}
            </span>
            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                {{ $event->event_type }}
            </span>
        </div>

        <div>
            <h1 class="text-2xl md:text-3xl font-black text-[#001D5E]">
                {{ $event->title }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Klien: <strong class="text-slate-800">{{ $event->client_name }}</strong>
            </p>
        </div>

        {{-- Highlight Peran Saya --}}
        @if($myAssignment)
            <div class="p-4 rounded-2xl bg-orange-50 border-2 border-orange-300 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#F7941D] text-white flex items-center justify-center shrink-0">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-orange-800 uppercase block">Penugasan Peran Anda</span>
                    <h3 class="text-base font-black text-orange-950">{{ $myAssignment->pivot->role_in_event ?? 'Kru Lapangan' }}</h3>
                </div>
            </div>
        @endif

        {{-- Detail Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs sm:text-sm">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase block">Tanggal Pelaksanaan</span>
                <p class="font-bold text-slate-800">
                    {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('dddd, D MMMM Y') }}
                    @if($event->end_date && $event->end_date !== $event->start_date)
                        - {{ \Carbon\Carbon::parse($event->end_date)->isoFormat('dddd, D MMMM Y') }}
                    @endif
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase block">Waktu Standby / Acara</span>
                <p class="font-bold text-slate-800">
                    {{ $event->start_time ? substr($event->start_time, 0, 5) : '08:00' }} s/d 
                    {{ $event->end_time ? substr($event->end_time, 0, 5) : 'Selesai' }} WIB
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase block">Kota / Wilayah</span>
                <p class="font-bold text-slate-800">{{ $event->location }}</p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-500 uppercase block">Venue / Lokasi Gedung</span>
                <p class="font-bold text-slate-800">{{ $event->venue_detail ?? 'Akan diinformasikan lebih lanjut' }}</p>
            </div>
        </div>

        {{-- Rundown / Catatan Tambahan --}}
        @if($event->notes)
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase block mb-1">Rundown / Catatan On-Site</span>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 whitespace-pre-line">
                    {{ $event->notes }}
                </div>
            </div>
        @endif
    </div>

    {{-- Rekan Tim Kru Lapangan --}}
    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-[#001D5E]">Tim Kru Bertugas ({{ $event->crewAssignments->count() }})</h3>
                <p class="text-xs text-slate-500">Koordinasi bersama rekan staf lapangan dan tim freelance.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($event->crewAssignments as $member)
                <div class="p-3.5 rounded-xl border-2 {{ ($member->isInternal() && $member->user_id === Auth::id()) ? 'border-[#F7941D] bg-orange-50/40' : 'border-slate-200 bg-slate-50' }} flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        @if($member->isInternal() && $member->user && $member->user->face_photo)
                            <img src="{{ asset('storage/' . $member->user->face_photo) }}" alt="Foto" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-slate-300">
                        @elseif($member->isInternal())
                            <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                {{ substr($member->crew_name, 0, 1) }}
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                {{ substr($member->crew_name, 0, 1) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h4 class="font-bold text-xs sm:text-sm text-slate-800 truncate">
                                {{ $member->crew_name }}
                                @if($member->isInternal() && $member->user_id === Auth::id())
                                    <span class="text-[10px] font-black text-orange-600">(Anda)</span>
                                @endif
                            </h4>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] font-black text-[#F7941D] truncate">{{ $member->role_in_event ?? 'Crew' }}</span>
                                <span class="text-[8px] font-bold px-1.5 py-0.2 rounded text-white {{ $member->isInternal() ? 'bg-[#001D5E]' : 'bg-[#F7941D]' }}">
                                    {{ $member->isInternal() ? 'Karyawan' : 'Freelance' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($member->crew_phone)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $member->crew_phone)) }}" 
                           target="_blank" class="p-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shrink-0 shadow-xs" title="Hubungi via WhatsApp">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-xs text-slate-500 italic col-span-2 text-center py-4">Belum ada tim yang dialokasikan.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
