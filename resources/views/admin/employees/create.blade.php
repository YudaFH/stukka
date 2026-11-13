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
                    <div class="relative">
                        <input type="password" id="emp_password" name="password" required minlength="8"
                               class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 pl-3 pr-11 focus:border-[#001D5E] focus:ring-0 @error('password') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePasswordVisibility('emp_password', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                                tabindex="-1" title="Lihat / Sembunyikan password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Konfirmasi Password *</label>
                    <div class="relative">
                        <input type="password" id="emp_password_confirmation" name="password_confirmation" required minlength="8"
                               class="w-full text-sm rounded-xl border-2 border-slate-300 py-2.5 pl-3 pr-11 focus:border-[#001D5E] focus:ring-0"
                               placeholder="Ulangi password">
                        <button type="button" onclick="togglePasswordVisibility('emp_password_confirmation', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                                tabindex="-1" title="Lihat / Sembunyikan password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
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

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        btn.innerHTML = isPass
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>`;
    }
</script>
@endsection
