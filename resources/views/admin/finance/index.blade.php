@extends('layouts.admin')

@section('title', 'Rekap Keuangan Event')
@section('header_title', 'Perhitungan Keuangan per Event')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Perhitungan Keuangan Event</h1>
            <p class="text-sm text-slate-500 mt-1">
                Laporan komprehensif omset kontrak, realisasi pembayaran klien, biaya produksi vendor, hutang vendor, dan laba bersih.
            </p>
        </div>
        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            {{-- Unduh Excel Keuangan --}}
            <a href="{{ route('admin.scheduled-events.export.excel', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm border-2 border-emerald-700 shadow-xs transition-colors whitespace-nowrap"
               title="Unduh laporan keuangan event ke format Excel (.xls)">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-white"></i>
                <span>Unduh Excel</span>
            </a>

            {{-- Cetak / Simpan PDF Keuangan --}}
            <a href="{{ route('admin.scheduled-events.export.pdf', request()->all()) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm border-2 border-[#001138] shadow-xs transition-colors whitespace-nowrap"
               title="Buka laporan keuangan cetak A4 dan simpan PDF">
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

    {{-- 4 GLOBAL FINANCIAL METRIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. Total Omset Proyek --}}
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase">Total Nilai Kontrak</span>
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-[#001D5E] mt-3">
                Rp {{ number_format($totalContracts, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-500 mt-1">Total nilai seluruh proyek</p>
        </div>

        {{-- 2. Pembayaran Masuk Diterima vs Piutang --}}
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 uppercase">Pemasukan Riil (Cash In)</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-emerald-600 mt-3">
                Rp {{ number_format($totalReceived, 0, ',', '.') }}
            </p>
            <div class="flex items-center justify-between text-xs mt-1">
                <span class="text-slate-500">Sisa Piutang Klien:</span>
                <span class="font-bold @if($totalReceivable > 0) text-rose-600 @else text-emerald-600 @endif">
                    Rp {{ number_format($totalReceivable, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- 3. Biaya Produksi vs Hutang Vendor --}}
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-orange-700 uppercase">Total Biaya Produksi</span>
                <div class="w-9 h-9 rounded-xl bg-[#F7941D] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-800 mt-3">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </p>
            <div class="flex items-center justify-between text-xs mt-1">
                <span class="text-slate-500">Hutang Vendor (Unpaid):</span>
                <span class="font-bold @if($totalUnpaidExpenses > 0) text-rose-600 @else text-emerald-600 @endif">
                    Rp {{ number_format($totalUnpaidExpenses, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- 4. Laba Bersih & Margin --}}
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 uppercase">Total Laba Bersih</span>
                <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-[#F7941D] shadow-sm flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black @if($totalNetProfit >= 0) text-emerald-600 @else text-rose-600 @endif mt-3">
                Rp {{ number_format($totalNetProfit, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-slate-600 mt-1">
                Margin Rata-rata: <span class="text-[#001D5E] font-black">{{ $globalMargin }}%</span>
            </p>
        </div>
    </div>

    {{-- Filter Bar (Sebaris Penuh Mentok Sampai Kanan di Desktop & Tablet) --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('admin.finance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end w-full">
            {{-- Search --}}
            <div class="sm:col-span-2 lg:col-span-4 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pencarian Event / Klien</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama event atau klien..."
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>
            </div>

            {{-- Filter Bulan --}}
            <div class="sm:col-span-1 lg:col-span-2 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Periode Bulan</label>
                <input type="month" name="month" value="{{ $month ?? '' }}"
                       style="min-height: 44px;"
                       class="w-full px-3 py-2.5 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
            </div>

            {{-- Filter Status Klien --}}
            <div class="sm:col-span-1 lg:col-span-2 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status Klien</label>
                <select name="client_status" 
                        style="min-height: 44px;"
                        class="w-full px-3 py-2.5 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    <option value="">Semua Klien</option>
                    <option value="paid" {{ $clientStatus === 'paid' ? 'selected' : '' }}>Klien: Lunas</option>
                    <option value="partial" {{ $clientStatus === 'partial' ? 'selected' : '' }}>Klien: DP / Cicil</option>
                    <option value="unpaid" {{ $clientStatus === 'unpaid' ? 'selected' : '' }}>Klien: Belum Bayar</option>
                </select>
            </div>

            {{-- Filter Status Vendor --}}
            <div class="sm:col-span-1 lg:col-span-2 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Biaya Vendor</label>
                <select name="vendor_status" 
                        style="min-height: 44px;"
                        class="w-full px-3 py-2.5 text-xs sm:text-sm bg-white border-2 border-slate-300 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    <option value="">Semua Vendor</option>
                    <option value="paid" {{ $vendorStatus === 'paid' ? 'selected' : '' }}>Vendor: Lunas</option>
                    <option value="partial" {{ $vendorStatus === 'partial' ? 'selected' : '' }}>Vendor: Sebagian</option>
                    <option value="unpaid" {{ $vendorStatus === 'unpaid' ? 'selected' : '' }}>Vendor: Ada Hutang</option>
                </select>
            </div>

            {{-- Tombol Aksi (Mentok Sampai Kanan) --}}
            <div class="sm:col-span-1 lg:col-span-2 w-full flex items-center gap-2">
                <button type="submit" 
                        style="min-height: 44px;"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm transition-all border-2 border-[#001138] cursor-pointer whitespace-nowrap shadow-xs">
                    <i data-lucide="filter" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.finance.index') }}" 
                   style="min-height: 44px;"
                   class="inline-flex items-center justify-center py-2.5 px-3 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer whitespace-nowrap shadow-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Financial Table --}}
    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b-2 border-slate-200 text-slate-700 font-black uppercase tracking-wider">
                        <th class="py-3.5 px-4">Event & Tanggal</th>
                        <th class="py-3.5 px-4">Klien</th>
                        <th class="py-3.5 px-4 text-right">Nilai Kontrak</th>
                        <th class="py-3.5 px-4 text-right">Pembayaran Masuk</th>
                        <th class="py-3.5 px-4 text-right">Biaya Produksi</th>
                        <th class="py-3.5 px-4 text-right">Laba Bersih</th>
                        <th class="py-3.5 px-4 text-center">Margin</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            {{-- Event & Tanggal --}}
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.scheduled-events.show', ['scheduled_event' => $event->id, 'tab' => 'finance']) }}" 
                                   class="font-black text-[#001D5E] hover:text-[#F7941D] transition-colors block text-sm">
                                    {{ $event->title }}
                                </a>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>{{ \Carbon\Carbon::parse($event->start_date)->isoFormat('D MMM Y') }}</span>
                                    <span>•</span>
                                    <span class="font-semibold">{{ $event->location }}</span>
                                </div>
                            </td>

                            {{-- Klien --}}
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $event->client_name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $event->client_phone ?? '-' }}</span>
                            </td>

                            {{-- Nilai Kontrak --}}
                            <td class="py-3.5 px-4 text-right font-black text-slate-900">
                                Rp {{ number_format($event->contract_amount, 0, ',', '.') }}
                            </td>

                            {{-- Pembayaran Masuk --}}
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-black text-emerald-600 block">
                                    Rp {{ number_format($event->total_paid, 0, ',', '.') }}
                                </span>
                                @if($event->remaining_receivable > 0)
                                    <span class="text-[10px] font-bold text-rose-600 block">
                                        Piutang: Rp {{ number_format($event->remaining_receivable, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black bg-emerald-600 text-white shadow-xs">
                                        LUNAS
                                    </span>
                                @endif
                            </td>

                            {{-- Biaya Produksi --}}
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-black text-slate-800 block">
                                    Rp {{ number_format($event->total_expense, 0, ',', '.') }}
                                </span>
                                @if($event->remaining_expense > 0)
                                    <span class="text-[10px] font-bold text-rose-600 block">
                                        Hutang: Rp {{ number_format($event->remaining_expense, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black {{ $event->expenses->count() > 0 ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-white' }} shadow-xs">
                                        {{ $event->expenses->count() > 0 ? 'LUNAS' : 'Nihil' }}
                                    </span>
                                @endif
                            </td>

                            {{-- Laba Bersih --}}
                            <td class="py-3.5 px-4 text-right font-black @if($event->net_profit >= 0) text-emerald-600 @else text-rose-600 @endif">
                                Rp {{ number_format($event->net_profit, 0, ',', '.') }}
                            </td>

                            {{-- Margin --}}
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black text-white shadow-xs
                                    @if($event->profit_margin_percentage >= 25) bg-emerald-600
                                    @elseif($event->profit_margin_percentage >= 0) bg-[#F7941D]
                                    @else bg-rose-600 @endif">
                                    {{ $event->profit_margin_percentage }}%
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.scheduled-events.show', ['scheduled_event' => $event->id, 'tab' => 'finance']) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#001D5E] text-white hover:bg-blue-900 font-bold text-xs transition-colors shadow-xs">
                                    <i data-lucide="calculator" class="w-3.5 h-3.5"></i>
                                    <span>Catat & Kelola</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 px-4">
                                <div class="max-w-md mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-[#001D5E] text-white flex items-center justify-center mx-auto shadow-sm">
                                        <i data-lucide="wallet-cards" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="font-black text-slate-800 text-base">Belum Ada Jadwal Event</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Mulai tambahkan jadwal event operasional untuk mencatat nilai kontrak klien, pembayaran masuk, dan biaya produksi vendor.
                                    </p>
                                    <div class="pt-2">
                                        <a href="{{ route('admin.scheduled-events.create') }}" 
                                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-xs shadow-md transition-colors">
                                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                            <span>Tambah Jadwal Event</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $events->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
