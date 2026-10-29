@extends('layouts.admin')

@section('title', 'Portofolio Project')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Kelola Portofolio</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar project dan event yang telah dan sedang ditangani oleh Stukka.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-sm border-2 border-[#D67B0F] transition-colors self-start sm:self-auto shadow-sm whitespace-nowrap shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Project</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-white border-2 border-emerald-600 text-emerald-950 p-4 rounded-xl shadow-sm font-semibold flex items-center gap-3">
            <span class="p-1 rounded bg-emerald-600 text-white font-black text-xs">
                <i data-lucide="check" class="w-4 h-4"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter Bar (Sebaris di Layar Besar, 2 Baris Simetris Seimbang di Layar Sedang/Tablet) --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('admin.events.index') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-3.5 items-end w-full">
            {{-- Search Box: Full-width di layar sedang, 5 kolom di layar besar --}}
            <div class="sm:col-span-2 xl:col-span-5 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari Project / Klien / Lokasi</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           placeholder="Cari berdasarkan nama acara, nama klien, atau lokasi..." 
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                </div>
            </div>

            {{-- Status: 1 kolom (kiri 50%) di layar sedang, 3 kolom di layar besar --}}
            <div class="sm:col-span-1 xl:col-span-3 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status Project</label>
                <select name="status" 
                        style="min-height: 44px;"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                    <option value="">Semua Status</option>
                    <option value="upcoming" {{ ($status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ ($status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="finished" {{ ($status ?? '') === 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
            </div>

            {{-- Tombol Aksi Filter: 1 kolom (kanan 50% mentok kanan) di layar sedang, 4 kolom di layar besar --}}
            <div class="sm:col-span-1 xl:col-span-4 w-full flex items-center gap-2">
                <button type="submit" 
                        style="min-height: 44px;"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm transition-all border-2 border-[#001138] cursor-pointer whitespace-nowrap shadow-xs">
                    <i data-lucide="filter" class="w-4 h-4 text-[#F7941D]"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.events.index') }}" 
                   style="min-height: 44px;"
                   class="inline-flex items-center justify-center py-2.5 px-4 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer whitespace-nowrap shadow-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-100 text-slate-700 uppercase text-xs font-black border-b-2 border-slate-200">
                <tr>
                    <th class="p-4">Cover</th>
                    <th class="p-4">Judul Project</th>
                    <th class="p-4">Tanggal Acara</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($events as $event)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <img src="{{ $event->image }}" class="w-16 h-12 object-cover rounded-xl border-2 border-slate-300">
                    </td>
                    <td class="p-4 font-bold text-[#001D5E] text-sm">{{ $event->title }}</td>
                    <td class="p-4 text-sm text-gray-600 font-medium">{{ $event->date }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold text-white 
                            {{ $event->status == 'upcoming' ? 'bg-[#001D5E]' : ($event->status == 'ongoing' ? 'bg-[#F7941D]' : 'bg-slate-700') }}">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="bg-[#001D5E] text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-900 border-2 border-[#001138] transition-colors">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700 border-2 border-red-700 transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-slate-400">
                        <i data-lucide="folder-x" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                        <p class="font-bold text-slate-700 text-sm">Belum Ada Project Portofolio</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
@endsection