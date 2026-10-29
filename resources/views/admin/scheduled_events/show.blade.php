@extends('layouts.admin')

@section('title', 'Detail Event: ' . $event->title)
@section('header_title', $event->title)

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('tab', 'finance') }}', paymentModal: false, expenseModal: false }">

    {{-- Top Action & Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('admin.scheduled-events.index') }}" 
           class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#001D5E] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Jadwal Event</span>
        </a>

        <div class="flex items-center flex-wrap gap-2">
            <a href="{{ route('admin.scheduled-events.print', $event->id) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#001D5E] text-white hover:bg-blue-900 font-bold text-xs transition-colors shadow-xs">
                <i data-lucide="printer" class="w-4 h-4 text-[#F7941D]"></i>
                <span>Cetak SPK / PDF</span>
            </a>
            <a href="{{ route('admin.scheduled-events.edit', $event->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-slate-300 text-slate-700 hover:bg-slate-100 font-bold text-xs transition-colors">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span>Edit Jadwal</span>
            </a>
            <form action="{{ route('admin.scheduled-events.destroy', $event->id) }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal event \'{{ $event->title }}\' beserta seluruh data keuangannya?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-rose-300 text-rose-600 hover:bg-rose-50 font-bold text-xs transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Hapus Event</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Event Banner Card --}}
    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 md:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wide text-white shadow-xs
                        @if($event->status === 'scheduled') bg-blue-600
                        @elseif($event->status === 'ongoing') bg-[#F7941D]
                        @elseif($event->status === 'completed') bg-emerald-600
                        @else bg-rose-600 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-[#001D5E] text-white">
                        {{ $event->event_type }}
                    </span>
                    @if($event->booking_id)
                        <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-[#F7941D] text-white">
                            Booking #{{ $event->booking_id }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-black text-[#001D5E]">
                    {{ $event->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-xs md:text-sm text-slate-600">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D]"></i>
                        <span class="font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('dddd, D MMMM Y') }}
                            @if($event->end_date && $event->end_date !== $event->start_date)
                                - {{ \Carbon\Carbon::parse($event->end_date)->isoFormat('dddd, D MMMM Y') }}
                            @endif
                        </span>
                    </div>

                    @if($event->start_time)
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
                            <span>{{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 'WIB' }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                        <span>{{ $event->location }} {{ $event->venue_detail ? "• {$event->venue_detail}" : '' }}</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        <span>Klien: <strong>{{ $event->client_name }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Quick Profit Badge --}}
            <div class="bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 sm:p-5 shrink-0 flex flex-col justify-center">
                <span class="text-xs font-bold text-slate-500 uppercase">Laba Bersih Project</span>
                <p class="text-2xl md:text-3xl font-black @if($event->net_profit >= 0) text-emerald-600 @else text-rose-600 @endif mt-1">
                    Rp {{ number_format($event->net_profit, 0, ',', '.') }}
                </p>
                <div class="flex items-center gap-2 mt-2 text-xs">
                    <span class="text-slate-500">Margin:</span>
                    <span class="font-black text-white @if($event->profit_margin_percentage >= 20) bg-emerald-600 @elseif($event->profit_margin_percentage >= 0) bg-[#F7941D] @else bg-rose-600 @endif px-2.5 py-0.5 rounded-lg shadow-xs">
                        {{ $event->profit_margin_percentage }}%
                    </span>
                </div>
            </div>
        </div>

        {{-- Nav Tabs (Segmented Control - Full Visibility on Mobile without scrolling) --}}
        <div class="mt-6 pt-5 border-t-2 border-slate-100">
            <div class="bg-slate-100/90 p-1.5 rounded-2xl border-2 border-slate-200/80 grid grid-cols-3 gap-1 sm:gap-2 shadow-inner">
                {{-- Tab 1: Keuangan & Biaya --}}
                <button type="button" @click="activeTab = 'finance'"
                        class="py-2.5 px-1.5 sm:px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-1.5 sm:gap-2 transition-all min-h-[44px]"
                        :class="activeTab === 'finance' 
                            ? 'bg-[#001D5E] text-white shadow-md ring-2 ring-[#001D5E]/10' 
                            : 'text-slate-600 hover:text-slate-900 hover:bg-white/70'">
                    <i data-lucide="wallet-cards" class="w-4 h-4 shrink-0" :class="activeTab === 'finance' ? 'text-[#F7941D]' : 'text-slate-400'"></i>
                    <span class="truncate">
                        <span class="inline sm:hidden">Keuangan</span>
                        <span class="hidden sm:inline">Keuangan & Biaya</span>
                    </span>
                    <span class="px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] font-black shrink-0 transition-colors"
                          :class="activeTab === 'finance' ? 'bg-[#F7941D] text-white' : 'bg-slate-200 text-slate-700'">
                        {{ $event->payments->count() + $event->expenses->count() }}
                    </span>
                </button>

                {{-- Tab 2: Kru Tim Lapangan --}}
                <button type="button" @click="activeTab = 'crew'"
                        class="py-2.5 px-1.5 sm:px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-1.5 sm:gap-2 transition-all min-h-[44px]"
                        :class="activeTab === 'crew' 
                            ? 'bg-[#001D5E] text-white shadow-md ring-2 ring-[#001D5E]/10' 
                            : 'text-slate-600 hover:text-slate-900 hover:bg-white/70'">
                    <i data-lucide="users" class="w-4 h-4 shrink-0" :class="activeTab === 'crew' ? 'text-[#F7941D]' : 'text-slate-400'"></i>
                    <span class="truncate">
                        <span class="inline sm:hidden">Kru Tim</span>
                        <span class="hidden sm:inline">Kru Lapangan</span>
                    </span>
                    <span class="px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] font-black shrink-0 transition-colors"
                          :class="activeTab === 'crew' ? 'bg-[#F7941D] text-white' : 'bg-slate-200 text-slate-700'">
                        {{ $event->crewAssignments->count() }}
                    </span>
                </button>

                {{-- Tab 3: Detail Jadwal & Venue --}}
                <button type="button" @click="activeTab = 'overview'"
                        class="py-2.5 px-1.5 sm:px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-1.5 sm:gap-2 transition-all min-h-[44px]"
                        :class="activeTab === 'overview' 
                            ? 'bg-[#001D5E] text-white shadow-md ring-2 ring-[#001D5E]/10' 
                            : 'text-slate-600 hover:text-slate-900 hover:bg-white/70'">
                    <i data-lucide="info" class="w-4 h-4 shrink-0" :class="activeTab === 'overview' ? 'text-[#F7941D]' : 'text-slate-400'"></i>
                    <span class="truncate">
                        <span class="inline sm:hidden">Detail Event</span>
                        <span class="hidden sm:inline">Detail & Venue</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 1: PERHITUNGAN KEUANGAN & BIAYA PRODUKSI                               --}}
    {{-- ========================================================================= --}}
    <div x-show="activeTab === 'finance'" class="space-y-6">

        {{-- 4 METRIC CARDS KEUANGAN EVENT --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- 1. Nilai Kontrak --}}
            <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase">Nilai Kontrak Klien</span>
                    <div class="w-8 h-8 rounded-lg bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                    </div>
                </div>
                <p class="text-xl font-black text-[#001D5E] mt-3">
                    Rp {{ number_format($event->contract_amount, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-500 mt-1">Total tagihan ke klien</p>
            </div>

            {{-- 2. Pembayaran Masuk Klien --}}
            <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase">Pembayaran Diterima</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white shadow-sm flex items-center justify-center">
                        <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                    </div>
                </div>
                <p class="text-xl font-black text-emerald-600 mt-3">
                    Rp {{ number_format($event->total_paid, 0, ',', '.') }}
                </p>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500">Sisa Piutang:</span>
                    <span class="font-bold @if($event->remaining_receivable > 0) text-rose-600 @else text-emerald-600 @endif">
                        Rp {{ number_format($event->remaining_receivable, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- 3. Biaya Produksi / Pengeluaran --}}
            <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase">Total Biaya Produksi</span>
                    <div class="w-8 h-8 rounded-lg bg-[#F7941D] text-white shadow-sm flex items-center justify-center">
                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    </div>
                </div>
                <p class="text-xl font-black text-slate-800 mt-3">
                    Rp {{ number_format($event->total_expense, 0, ',', '.') }}
                </p>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500">Hutang Vendor:</span>
                    <span class="font-bold @if($event->remaining_expense > 0) text-rose-600 @else text-emerald-600 @endif">
                        Rp {{ number_format($event->remaining_expense, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- 4. Laba Bersih --}}
            <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase">Laba Bersih & Margin</span>
                    <div class="w-8 h-8 rounded-lg bg-[#001D5E] text-[#F7941D] shadow-sm flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                </div>
                <p class="text-xl font-black @if($event->net_profit >= 0) text-emerald-600 @else text-rose-600 @endif mt-3">
                    Rp {{ number_format($event->net_profit, 0, ',', '.') }}
                </p>
                <p class="text-xs font-bold text-slate-600 mt-1">
                    Margin: {{ $event->profit_margin_percentage }}% dari nilai kontrak
                </p>
            </div>
        </div>

        {{-- GRID DUA KOLOM: PEMBAYARAN KLIEN & BIAYA PRODUKSI VENDOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- SECTION A: PEMBAYARAN MASUK DARI KLIEN (5 KOLOM) --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-[#001D5E]">Pembayaran Klien</h2>
                            <p class="text-xs text-slate-500">Termin DP, angsuran, dan pelunasan.</p>
                        </div>
                        <button type="button" @click="paymentModal = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Catat Pembayaran</span>
                        </button>
                    </div>

                    {{-- Progress Pembayaran Klien --}}
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700">Progress Pelunasan</span>
                            <span class="font-black text-[#001D5E]">{{ $event->payment_progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300
                                @if($event->payment_progress_percentage >= 100) bg-emerald-500
                                @elseif($event->payment_progress_percentage > 0) bg-[#F7941D]
                                @else bg-slate-300 @endif"
                                style="width: {{ min(100, $event->payment_progress_percentage) }}%">
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-600">
                            <span>Diterima: <strong class="text-emerald-700">Rp {{ number_format($event->total_paid, 0, ',', '.') }}</strong></span>
                            <span>Sisa: <strong class="text-rose-700">Rp {{ number_format($event->remaining_receivable, 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    {{-- Riwayat Pembayaran Masuk --}}
                    <div class="space-y-3">
                        @forelse($event->payments as $payment)
                            <div class="p-3 bg-white rounded-xl border border-slate-200 hover:border-slate-300 flex items-start justify-between gap-3 transition-colors">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-[#001D5E]">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">
                                            {{ $payment->payment_method }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500">
                                        <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->isoFormat('D MMMM Y') }}
                                        @if($payment->reference_no)
                                            • Ref: {{ $payment->reference_no }}
                                        @endif
                                    </p>
                                    @if($payment->notes)
                                        <p class="text-[11px] text-slate-600 italic">{{ $payment->notes }}</p>
                                    @endif
                                    @if($payment->receipt_proof)
                                        <a href="{{ asset('storage/' . $payment->receipt_proof) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:underline pt-0.5">
                                            <i data-lucide="paperclip" class="w-3 h-3"></i>
                                            <span>Lihat Bukti Bayar</span>
                                        </a>
                                    @endif
                                </div>

                                <form action="{{ route('admin.finance.payment.destroy', $payment->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus data pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                <i data-lucide="receipt" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                                <p class="text-xs font-bold text-slate-600">Belum ada pembayaran klien</p>
                                <p class="text-[11px] text-slate-600 mt-0.5">Klik tombol di atas untuk mencatat pembayaran DP.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SECTION B: BIAYA PRODUKSI & PENGELUARAN VENDOR (7 KOLOM) --}}
            <div class="lg:col-span-7 space-y-4">
                <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-[#001D5E]">Biaya Produksi & Vendor</h2>
                            <p class="text-xs text-slate-500">Sound, Lighting, Venue, Kru, Logistik, dll.</p>
                        </div>
                        <button type="button" @click="expenseModal = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-xs transition-colors shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Catat Biaya Produksi</span>
                        </button>
                    </div>

                    {{-- Ringkasan Biaya Produksi (Warna Solid) --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-emerald-600 text-white p-3.5 rounded-xl shadow-xs">
                            <span class="text-[11px] font-black text-emerald-100 uppercase tracking-wider block">Sudah Dibayar (Lunas)</span>
                            <span class="text-base font-black text-white block mt-0.5">
                                Rp {{ number_format($event->total_paid_expense, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="bg-rose-600 text-white p-3.5 rounded-xl shadow-xs">
                            <span class="text-[11px] font-black text-rose-100 uppercase tracking-wider block">Belum Dibayar (Hutang Vendor)</span>
                            <span class="text-base font-black text-white block mt-0.5">
                                Rp {{ number_format($event->remaining_expense, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Daftar Pos Biaya --}}
                    <div class="space-y-3">
                        @forelse($event->expenses as $expense)
                            <div class="p-3.5 bg-white rounded-xl border-2 border-slate-200 hover:border-slate-300 transition-all flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            @if(in_array(strtolower($expense->category), ['sound', 'sound system & multimedia'])) bg-purple-100 text-purple-700
                                            @elseif(in_array(strtolower($expense->category), ['stage', 'stage & lighting', 'panggung, tenda & lighting'])) bg-amber-100 text-amber-700
                                            @elseif(in_array(strtolower($expense->category), ['catering', 'konsumsi & catering'])) bg-rose-100 text-rose-700
                                            @elseif(in_array(strtolower($expense->category), ['crew', 'honor kru freelance / talent'])) bg-blue-100 text-blue-700
                                            @elseif(in_array(strtolower($expense->category), ['logistics', 'transportasi & logistik'])) bg-cyan-100 text-cyan-700
                                            @elseif(in_array(strtolower($expense->category), ['venue', 'sewa venue & perizinan'])) bg-emerald-100 text-emerald-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $expense->category_details['label'] ?? $expense->category }}
                                        </span>
                                        <h4 class="text-sm font-black text-[#001D5E]">
                                            {{ $expense->item_name }}
                                        </h4>
                                    </div>

                                    <div class="text-xs text-slate-500 flex items-center gap-2 flex-wrap">
                                        @if($expense->vendor_name)
                                            <span class="font-medium text-slate-700">Vendor: {{ $expense->vendor_name }}</span>
                                            <span>•</span>
                                        @endif
                                        <span class="font-bold text-slate-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                        @if($expense->invoice_proof)
                                            <span>•</span>
                                            <a href="{{ asset('storage/' . $expense->invoice_proof) }}" target="_blank"
                                               class="font-bold text-blue-600 hover:underline inline-flex items-center gap-1">
                                                <i data-lucide="paperclip" class="w-3 h-3"></i>
                                                <span>Invoice</span>
                                            </a>
                                        @endif
                                    </div>

                                    @if($expense->notes)
                                        <p class="text-[11px] text-slate-500 italic">{{ $expense->notes }}</p>
                                    @endif
                                </div>

                                {{-- Status Bayar & Aksi --}}
                                <div class="flex items-center gap-2 shrink-0">
                                    {{-- Toggle Status Lunas / Belum Lunas --}}
                                    <form action="{{ route('admin.finance.expense.toggle-status', $expense->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1.5 rounded-lg text-xs font-black transition-all flex items-center gap-1.5
                                                @if($expense->is_paid) bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm @else bg-rose-600 hover:bg-rose-700 text-white shadow-sm @endif"
                                                title="Klik untuk ubah status pembayaran">
                                            @if($expense->is_paid)
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                                <span>Lunas</span>
                                            @else
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                                <span>Hutang</span>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.finance.expense.destroy', $expense->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus biaya produksi \'{{ $expense->item_name }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Biaya">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                <i data-lucide="coins" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                                <p class="text-xs font-bold text-slate-600">Belum ada biaya produksi yang dicatat</p>
                                <p class="text-[11px] text-slate-600 mt-0.5">Catat pengeluaran vendor untuk menghitung laba bersih riil.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 2: TIM KRU LAPANGAN                                                   --}}
    {{-- ========================================================================= --}}
    <div x-show="activeTab === 'crew'" class="space-y-6" style="display: none;">
        <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div>
                    <h2 class="text-lg font-black text-[#001D5E]">Kru Bertugas di Acara Ini</h2>
                    <p class="text-xs text-slate-500">Daftar staf Stukka yang ditugaskan on-site pada event ini.</p>
                </div>
                <a href="{{ route('admin.scheduled-events.edit', $event->id) }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs border-2 border-[#001138] shadow-sm transition-colors">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#F7941D]"></i>
                    <span>Ubah Alokasi Kru</span>
                </a>
            </div>

            @if($event->crewAssignments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($event->crewAssignments as $member)
                        <div class="p-4 bg-slate-50 rounded-2xl border-2 border-slate-200 flex items-center gap-3">
                            @if($member->isInternal() && $member->user && $member->user->face_photo)
                                <img src="{{ asset('storage/' . $member->user->face_photo) }}" alt="Foto" class="w-12 h-12 rounded-xl object-cover border-2 border-[#001D5E] shrink-0">
                            @elseif($member->isInternal())
                                <div class="w-12 h-12 rounded-xl bg-[#001D5E] text-white flex items-center justify-center font-black text-base shrink-0">
                                    {{ substr($member->crew_name, 0, 1) }}
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-[#F7941D] text-white flex items-center justify-center font-black text-base shrink-0">
                                    {{ substr($member->crew_name, 0, 1) }}
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-[#001D5E] truncate">{{ $member->crew_name }}</h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-xs font-black text-[#F7941D] truncate">{{ $member->role_in_event ?? 'Crew' }}</span>
                                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded text-white {{ $member->isInternal() ? 'bg-[#001D5E]' : 'bg-[#F7941D]' }}">
                                        {{ $member->isInternal() ? 'Karyawan' : 'Freelance' }}
                                    </span>
                                </div>
                                @if($member->isInternal())
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                        Jabatan: {{ $member->user->position ?? 'Staff' }}
                                    </p>
                                @elseif($member->external_phone)
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5 font-mono">
                                        WA: {{ $member->external_phone }}
                                    </p>
                                @endif
                            </div>

                            @if($member->crew_phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $member->crew_phone)) }}" 
                                   target="_blank" class="p-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shrink-0 shadow-xs" title="Chat WhatsApp">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl">
                    <i data-lucide="users" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                    <h4 class="text-sm font-bold text-slate-700">Belum Ada Kru Ditugaskan</h4>
                    <p class="text-xs text-slate-600 mt-1">Tambahkan karyawan bertugas dengan mengklik tombol "Ubah Alokasi Kru".</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 3: DETAIL JADWAL & VENUE                                              --}}
    {{-- ========================================================================= --}}
    <div x-show="activeTab === 'overview'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Info Acara & Tempat --}}
            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 space-y-4">
                <h2 class="text-base font-black text-[#001D5E] pb-3 border-b border-slate-100">Informasi Pelaksanaan</h2>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Tanggal Acara</span>
                        <p class="font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('dddd, D MMMM Y') }}
                            @if($event->end_date && $event->end_date !== $event->start_date)
                                - {{ \Carbon\Carbon::parse($event->end_date)->isoFormat('dddd, D MMMM Y') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Waktu Operasional</span>
                        <p class="font-bold text-slate-800">
                            {{ $event->start_time ? substr($event->start_time, 0, 5) : '08:00' }} s/d 
                            {{ $event->end_time ? substr($event->end_time, 0, 5) : 'Selesai' }} WIB
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Kota / Area</span>
                        <p class="font-bold text-slate-800">{{ $event->location }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Detail Gedung / Venue</span>
                        <p class="font-bold text-slate-800">{{ $event->venue_detail ?? 'Belum ditentukan' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Catatan & Rundown</span>
                        <p class="text-xs text-slate-700 whitespace-pre-line bg-slate-50 p-3 rounded-xl border border-slate-200 mt-1">
                            {{ $event->notes ?? 'Tidak ada catatan tambahan.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Klien --}}
            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm p-6 space-y-4">
                <h2 class="text-base font-black text-[#001D5E] pb-3 border-b border-slate-100">Data Kontak Klien</h2>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Nama Klien / Penanggung Jawab</span>
                        <p class="font-bold text-slate-800">{{ $event->client_name }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">WhatsApp / Telepon</span>
                        @if($event->client_phone)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-bold text-slate-800">{{ $event->client_phone }}</span>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $event->client_phone)) }}" 
                                   target="_blank" class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 hover:bg-emerald-200 font-bold text-xs inline-flex items-center gap-1">
                                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                    <span>Chat WhatsApp</span>
                                </a>
                            </div>
                        @else
                            <p class="text-xs text-slate-600">Tidak tersedia</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Email Klien</span>
                        <p class="font-bold text-slate-800">{{ $event->client_email ?? 'Tidak tersedia' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase block">Status Reservasi Booking</span>
                        @if($event->booking)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-blue-100 text-blue-800 inline-block mt-1">
                                Reservasi #{{ $event->booking->id }} ({{ ucfirst($event->booking->status) }})
                            </span>
                        @else
                            <span class="text-xs text-slate-600 inline-block mt-1">Dibuat langsung oleh Admin (Direct Schedule)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL: CATAT PEMBAYARAN KLIEN                                             --}}
    {{-- ========================================================================= --}}
    <div x-show="paymentModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
         style="display: none;"
         @keydown.escape.window="paymentModal = false">
        
        <div class="bg-white rounded-2xl border-2 border-slate-300 max-w-lg w-full p-6 shadow-2xl relative"
             @click.away="paymentModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white shadow-sm flex items-center justify-center">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-base font-black text-[#001D5E]">Catat Pembayaran Klien</h3>
                </div>
                <button type="button" @click="paymentModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.finance.payment.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nominal Pembayaran <span class="text-rose-500">*</span></label>
                    <div x-data="{
                        raw: '{{ $event->remaining_receivable > 0 ? (int)$event->remaining_receivable : '' }}',
                        format(val) {
                            if (!val) return '';
                            let num = val.toString().replace(/[^0-9]/g, '');
                            return num ? new Intl.NumberFormat('id-ID').format(num) : '';
                        },
                        update(e) {
                            let clean = e.target.value.replace(/[^0-9]/g, '');
                            this.raw = clean;
                            e.target.value = clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
                        }
                    }">
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs font-black text-slate-500 select-none pointer-events-none">Rp</span>
                            <input type="text"
                                   inputmode="numeric"
                                   required
                                   :value="format(raw)"
                                   @input="update($event)"
                                   placeholder="0"
                                   class="w-full pl-10 pr-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E] font-bold text-[#001D5E]">
                            <input type="hidden" name="amount" :value="raw">
                        </div>
                    </div>
                    <span class="text-[11px] text-slate-500 mt-1 block">Sisa piutang klien saat ini: <strong>Rp {{ number_format($event->remaining_receivable, 0, ',', '.') }}</strong></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="payment_method" required
                                class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                            <option value="Transfer Bank BCA">Transfer BCA</option>
                            <option value="Transfer Bank Mandiri">Transfer Mandiri</option>
                            <option value="Transfer Bank BNI">Transfer BNI</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Referensi / No Transaksi</label>
                        <input type="text" name="reference_no" placeholder="Nomor referensi / transaksi (opsional)"
                               class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bukti Transfer (Opsional)</label>
                    <input type="file" name="receipt_proof" accept="image/*,application/pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#001D5E] hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan</label>
                    <input type="text" name="notes" placeholder="Catatan pembayaran (opsional)"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="paymentModal = false"
                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL: CATAT BIAYA PRODUKSI VENDOR                                        --}}
    {{-- ========================================================================= --}}
    <div x-show="expenseModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
         style="display: none;"
         @keydown.escape.window="expenseModal = false">
        
        <div class="bg-white rounded-2xl border-2 border-slate-300 max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto"
             @click.away="expenseModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#F7941D] text-white shadow-sm flex items-center justify-center">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-base font-black text-[#001D5E]">Catat Biaya Produksi Event</h3>
                </div>
                <button type="button" @click="expenseModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.finance.expense.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Biaya <span class="text-rose-500">*</span></label>
                        <select name="category" required
                                class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                            @foreach($expenseCategories as $catKey => $cat)
                                @php
                                    $catValue = is_array($cat) ? $catKey : $cat;
                                    $catLabel = is_array($cat) ? ($cat['label'] ?? $catKey) : $cat;
                                @endphp
                                <option value="{{ $catValue }}">{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="is_paid" required
                                class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                            <option value="1">Sudah Lunas</option>
                            <option value="0">Belum Lunas (Hutang Vendor)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Item / Pengeluaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="item_name" required placeholder="Masukkan nama item / kebutuhan biaya"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Vendor / Penerima</label>
                        <input type="text" name="vendor_name" placeholder="Nama pihak vendor / toko (opsional)"
                               class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nominal Biaya <span class="text-rose-500">*</span></label>
                        <div x-data="{
                            raw: '',
                            format(val) {
                                if (!val) return '';
                                let num = val.toString().replace(/[^0-9]/g, '');
                                return num ? new Intl.NumberFormat('id-ID').format(num) : '';
                            },
                            update(e) {
                                let clean = e.target.value.replace(/[^0-9]/g, '');
                                this.raw = clean;
                                e.target.value = clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
                            }
                        }">
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-xs font-black text-slate-500 select-none pointer-events-none">Rp</span>
                                <input type="text"
                                       inputmode="numeric"
                                       required
                                       :value="format(raw)"
                                       @input="update($event)"
                                       placeholder="0"
                                       class="w-full pl-10 pr-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E] font-bold text-[#001D5E]">
                                <input type="hidden" name="amount" :value="raw">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Metode Bayar (Opsional)</label>
                        <select name="payment_method"
                                class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                            <option value="">-- Pilih Metode (Opsional) --</option>
                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BNI">Transfer Bank BNI</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Bayar (Opsional)</label>
                        <input type="date" name="paid_at" value="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bukti Tagihan / Invoice (Opsional)</label>
                    <input type="file" name="invoice_proof" accept="image/*,application/pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#001D5E] hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                    <input type="text" name="notes" placeholder="Catatan tambahan / rincian biaya (opsional)"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="expenseModal = false"
                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-xs font-bold text-white bg-[#F7941D] hover:bg-orange-600 rounded-xl shadow-md transition-colors">
                        Simpan Biaya Produksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
