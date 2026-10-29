@extends('layouts.admin')

@section('title', 'Tambah Karyawan Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Tambah Karyawan Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Buat akun untuk crew atau staf Stukka Digital.</p>
        </div>
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all whitespace-nowrap shrink-0 shadow-xs">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl p-6 border-2 border-slate-300">
        <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-5">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 @error('name') border-red-500 @enderror"
                       placeholder="Contoh: Budi Pratama">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Email Akun *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 @error('email') border-red-500 @enderror"
                       placeholder="karyawan@stukka.com">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid NIP & Posisi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">NIP / ID Karyawan</label>
                    <input type="text" name="nip" value="{{ old('nip') }}"
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0"
                           placeholder="STK-2026-002">
                    @error('nip')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Jabatan / Posisi</label>
                    <input type="text" name="position" value="{{ old('position') }}"
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0"
                           placeholder="Contoh: Sound Engineer / EO Crew">
                    @error('position')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Grid Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t-2 border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Password *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0 @error('password') border-red-500 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 px-3 focus:border-[#001D5E] focus:ring-0"
                           placeholder="Ulangi password">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 rounded-xl border-2 border-slate-300 bg-white text-slate-700 text-sm font-bold hover:bg-slate-100 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white font-bold text-sm border-2 border-[#D67B0F] transition-all cursor-pointer">
                    Simpan Karyawan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
