@extends('layouts.admin')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-[#001D5E]">Manajemen Karyawan</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                Kelola akun karyawan, jabatan, dan status pendaftaran master biometrik wajah.
            </p>
        </div>
        <a href="{{ route('admin.employees.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-sm border-2 border-[#D67B0F] transition-colors self-start sm:self-auto whitespace-nowrap shrink-0 shadow-sm">
            <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
            <span>Tambah Karyawan</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-white border-2 border-emerald-600 text-emerald-950 text-xs sm:text-sm font-bold flex items-center gap-2">
            <span class="p-1 rounded bg-emerald-600 text-white flex items-center justify-center">
                <i data-lucide="check" class="w-4 h-4"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter Bar (Sebaris di Layar Besar, 2 Baris Simetris Seimbang di Layar Sedang/Tablet) --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-3.5 items-end w-full">
            {{-- Search Box: Full-width di layar sedang, 5 kolom di layar besar --}}
            <div class="sm:col-span-2 xl:col-span-5 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari Karyawan</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           placeholder="Cari berdasarkan nama, email, NIP, atau jabatan..." 
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                </div>
            </div>

            {{-- Status Biometrik: 1 kolom (kiri 50%) di layar sedang, 3 kolom di layar besar --}}
            <div class="sm:col-span-1 xl:col-span-3 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status Biometrik</label>
                <select name="status" 
                        style="min-height: 44px;"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                    <option value="">Semua Status Wajah</option>
                    <option value="registered" {{ ($status ?? '') === 'registered' ? 'selected' : '' }}>Wajah Terdaftar</option>
                    <option value="unregistered" {{ ($status ?? '') === 'unregistered' ? 'selected' : '' }}>Belum Terdaftar</option>
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
                <a href="{{ route('admin.employees.index') }}" 
                   style="min-height: 44px;"
                   class="inline-flex items-center justify-center py-2.5 px-4 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer whitespace-nowrap shadow-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- KONTEN UTAMA: EMPTY STATE ATAU LIST --}}
    @if($employees->isEmpty())
        <div class="bg-white rounded-2xl border-2 border-slate-300 p-12 text-center text-gray-400">
            <i data-lucide="users" class="w-10 h-10 mx-auto text-gray-300 mb-2"></i>
            <p class="font-bold text-gray-700 text-sm">Tidak Ada Karyawan Ditemukan</p>
            <p class="text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian atau status filter Anda.</p>
        </div>
    @else
        {{-- ============================================== --}}
        {{-- 1. MOBILE VIEW: KARTU LIST RAPI (Layar HP)     --}}
        {{-- ============================================== --}}
        <div class="sm:hidden space-y-3">
            @foreach($employees as $emp)
                <div class="bg-white rounded-2xl border-2 border-slate-300 p-4">
                    {{-- Baris 1: Avatar, Profil & Aksi --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($emp->face_photo)
                                <img src="{{ asset('storage/' . $emp->face_photo) }}" class="w-11 h-11 rounded-xl object-cover border-2 border-slate-300 flex-shrink-0">
                            @else
                                <div class="w-11 h-11 rounded-xl bg-[#001D5E] text-white font-black flex items-center justify-center text-sm flex-shrink-0 border-2 border-[#001138]">
                                    {{ substr($emp->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="font-black text-sm text-[#001D5E] truncate leading-tight">{{ $emp->name }}</h4>
                                <p class="text-xs text-gray-600 truncate mt-0.5">{{ $emp->email }}</p>
                                <p class="text-xs font-bold text-orange-700 mt-0.5 truncate">{{ $emp->position ?? 'Karyawan' }}</p>
                            </div>
                        </div>

                        {{-- Tombol Aksi Mobile (Solid Filled) --}}
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            {{-- Edit --}}
                            <a href="{{ route('admin.employees.edit', $emp->id) }}" 
                               class="w-8 h-8 rounded-lg bg-[#001D5E] hover:bg-blue-900 text-white flex items-center justify-center transition-colors border-2 border-[#001138]" 
                               title="Edit Karyawan">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>

                            {{-- Reset Face --}}
                            @if($emp->hasRegisteredFace())
                                <form method="POST" action="{{ route('admin.employees.reset-face', $emp->id) }}" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin mereset data biometrik wajah karyawan ini?');">
                                    @csrf
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-lg bg-amber-500 hover:bg-amber-600 text-white flex items-center justify-center transition-colors border-2 border-amber-600" 
                                            title="Reset Data Wajah">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" 
                                  onsubmit="return confirm('Hapus data karyawan {{ $emp->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white flex items-center justify-center transition-colors border-2 border-red-700" 
                                        title="Hapus Karyawan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Baris 2: NIP & Status Master Wajah (Solid Filled) --}}
                    <div class="pt-3 mt-3 border-t-2 border-slate-200 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-mono text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border-2 border-slate-300">
                            <span class="text-slate-500 text-[10px] uppercase font-bold">NIP:</span>
                            <span class="font-bold">{{ $emp->nip ?? '-' }}</span>
                        </div>

                        <div>
                            @if($emp->hasRegisteredFace())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-600 text-white border-2 border-emerald-700">
                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                    Wajah Terdaftar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#F7941D] text-white border-2 border-[#D67B0F]">
                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                    Belum Merekam
                                </span>
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
                        <tr class="bg-slate-100 border-b-2 border-slate-300 text-xs font-black text-slate-700 uppercase tracking-wider">
                            <th class="py-3.5 px-5">Karyawan</th>
                            <th class="py-3.5 px-5">NIP</th>
                            <th class="py-3.5 px-5">Jabatan</th>
                            <th class="py-3.5 px-5">Biometrik Wajah</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-slate-100 text-gray-700">
                        @foreach($employees as $emp)
                            <tr class="hover:bg-slate-100 transition-colors">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        @if($emp->face_photo)
                                            <img src="{{ asset('storage/' . $emp->face_photo) }}" class="w-9 h-9 rounded-xl object-cover border-2 border-slate-300 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-[#001D5E] text-white font-black flex items-center justify-center text-xs flex-shrink-0 border-2 border-[#001138]">
                                                {{ substr($emp->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-bold text-sm text-gray-900 leading-tight truncate">{{ $emp->name }}</p>
                                            <p class="text-xs text-gray-500 truncate leading-tight">{{ $emp->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-700 whitespace-nowrap">
                                    {{ $emp->nip ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-xs font-bold text-gray-800 whitespace-nowrap">
                                    {{ $emp->position ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    @if($emp->hasRegisteredFace())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white border-2 border-emerald-700">
                                            <span class="w-2 h-2 rounded-full bg-white"></span>
                                            Terdaftar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F7941D] text-white border-2 border-[#D67B0F]">
                                            <span class="w-2 h-2 rounded-full bg-white"></span>
                                            Belum Merekam
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.employees.edit', $emp->id) }}" 
                                           class="p-2 rounded-lg bg-[#001D5E] hover:bg-blue-900 text-white transition-colors border-2 border-[#001138]" title="Edit Karyawan">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>

                                        {{-- Reset Face --}}
                                        @if($emp->hasRegisteredFace())
                                            <form method="POST" action="{{ route('admin.employees.reset-face', $emp->id) }}" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin mereset data biometrik wajah karyawan ini?');">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors border-2 border-amber-600" title="Reset Data Wajah">
                                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" 
                                              onsubmit="return confirm('Hapus data karyawan {{ $emp->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors border-2 border-red-700" title="Hapus Karyawan">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300">
                {{ $employees->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
