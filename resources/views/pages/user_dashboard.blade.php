@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-14 min-h-screen">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-[#001D5E]">Riwayat Permintaan Event</h2>
            <p class="mt-2 text-gray-600">
                Pantau status permintaan Anda. Tim kami akan menghubungi via WhatsApp untuk konfirmasi detail.
            </p>
        </div>
    </div>

    {{-- Success message (lebih meyakinkan + SLA) --}}
    @if(session('success'))
        <div class="mb-8 rounded-2xl border-2 border-emerald-600 bg-white p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="p-1.5 rounded-lg bg-emerald-600 text-white shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p class="font-extrabold text-emerald-950">Permintaan event berhasil kami terima.</p>
                    <p class="text-slate-700 mt-1">
                        Tim Stukka akan menghubungi Anda via WhatsApp <span class="font-bold">maksimal 1×24 jam</span>.
                        Tidak ada biaya pada tahap ini.
                    </p>
                    <p class="text-sm font-semibold text-emerald-800 mt-2">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Overview Stats --}}
    @php
        $countPending = $myBookings->where('status', 'pending')->count();
        $countApproved = $myBookings->where('status', 'approved')->count();
        $countRejected = $myBookings->where('status', 'rejected')->count();
        $countTotal = $myBookings->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 font-bold">Total Permintaan</p>
            <p class="text-3xl font-black text-[#001D5E] mt-1">{{ $countTotal }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 font-bold">Menunggu</p>
            <p class="text-3xl font-black text-yellow-600 mt-1">{{ $countPending }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 font-bold">Disetujui</p>
            <p class="text-3xl font-black text-green-600 mt-1">{{ $countApproved }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 font-bold">Ditolak</p>
            <p class="text-3xl font-black text-red-600 mt-1">{{ $countRejected }}</p>
        </div>
    </div>

    {{-- Empty State --}}
    @if($myBookings->isEmpty())
        <div class="text-center py-14 bg-white rounded-3xl border border-gray-100 shadow-sm">
            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i data-lucide="calendar-off" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-extrabold text-gray-800">Belum ada permintaan event.</h3>
            <p class="text-gray-500 mt-2 mb-6">Mulai dari cek tanggal dan ajukan kebutuhan event Anda.</p>
            <a href="{{ route('services') }}"
               class="inline-flex items-center gap-2 px-7 py-3 bg-[#001D5E] text-white font-bold rounded-xl hover:bg-blue-900 transition shadow">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
                Ajukan Permintaan Pertama
            </a>
        </div>
    @else

        {{-- List --}}
        <div class="space-y-6">
            @foreach($myBookings as $booking)

                @php
                    // Booking reference sederhana (kalau belum punya kolom booking_code)
                    // Ini bukan ideal, tapi cukup untuk "feel professional".
                    $ref = 'STK-' . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);

                    $statusLabel = match($booking->status) {
                        'approved' => 'Disetujui',
                        'pending' => 'Menunggu Konfirmasi',
                        'rejected' => 'Ditolak',
                        default => 'Status Tidak Diketahui',
                    };
                @endphp

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition hover:shadow-lg">
                    {{-- Top row --}}
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-500">Booking Ref</p>
                                    <p class="font-extrabold text-gray-800">{{ $ref }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-500">Dibuat</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <h4 class="mt-4 font-black text-2xl text-[#001D5E]">
                                {{ $booking->event_type }}
                            </h4>

                            <div class="mt-3 flex flex-wrap items-center gap-4 text-gray-600 text-sm">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    {{ \Carbon\Carbon::parse($booking->event_date)->format('d F Y') }}
                                </span>

                                <span class="flex items-center gap-2">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                    Est. {{ $booking->guest_estimate ?? '-' }} tamu
                                </span>

                                <span class="flex items-center gap-2">
                                    <i data-lucide="wallet" class="w-4 h-4"></i>
                                    Budget: {{ $booking->budget_estimate ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Status + actions --}}
                        <div class="flex flex-col items-start lg:items-end gap-3">
                            @if($booking->status == 'approved')
                                <span class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-full font-extrabold text-sm shadow-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    {{ $statusLabel }}
                                </span>
                            @elseif($booking->status == 'pending')
                                <span class="inline-flex items-center gap-2 bg-[#F7941D] text-white px-4 py-2 rounded-full font-extrabold text-sm shadow-sm">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                    {{ $statusLabel }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-full font-extrabold text-sm shadow-sm">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    {{ $statusLabel }}
                                </span>
                            @endif

                            {{-- Timeline kecil buat pending --}}
                            @if($booking->status == 'pending')
                                <div class="w-full lg:w-72 bg-white border-2 border-slate-300 rounded-2xl p-4">
                                    <p class="text-xs font-bold text-gray-500 mb-3">Tahapan</p>
                                    <div class="space-y-2 text-sm text-gray-700">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Permintaan diterima
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            Konfirmasi tim (≤ 1×24 jam)
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                            Finalisasi jadwal & penawaran
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Admin notes --}}
                    @if($booking->admin_notes)
                        @if($booking->status == 'rejected')
                            <div class="mt-6 p-4 bg-white border-2 border-red-600 rounded-2xl">
                                <div class="flex items-start gap-3">
                                    <span class="p-1 rounded bg-red-600 text-white flex items-center justify-center">
                                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-extrabold text-red-700 uppercase mb-1">Catatan Admin</p>
                                        <p class="text-gray-800 italic">"{{ $booking->admin_notes }}"</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 p-4 bg-white border-2 border-[#001D5E] rounded-2xl">
                                <div class="flex items-start gap-3">
                                    <span class="p-1 rounded bg-[#001D5E] text-white flex items-center justify-center">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-extrabold text-[#001D5E] uppercase mb-1">Catatan Admin</p>
                                        <p class="text-gray-800 italic">"{{ $booking->admin_notes }}"</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- Footer actions --}}
                    <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <p class="text-sm text-gray-600">
                            Butuh cepat? Hubungi admin dan sebutkan <span class="font-extrabold text-gray-800">{{ $ref }}</span>.
                        </p>

                        <a href="https://wa.me/6285813505686?text={{ urlencode('Halo admin Stukka, saya ingin follow-up permintaan event. Ref: '.$ref) }}"
                           target="_blank"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white border border-gray-200 text-gray-800 font-bold hover:bg-gray-50 transition">
                            <i data-lucide="message-circle" class="w-5 h-5"></i>
                            Follow-up WhatsApp
                        </a>
                    </div>
                </div>

            @endforeach
        </div>
    @endif

</div>
@endsection
