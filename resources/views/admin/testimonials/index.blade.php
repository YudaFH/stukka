@extends('layouts.admin')

@section('title', 'Testimoni Client')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Kelola Testimoni Client</h1>
            <p class="text-sm text-gray-500 mt-1">Ulasan kepuasan dan penilaian dari klien yang pernah bekerja sama dengan Stukka.</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="text-gray-700 hover:text-[#001D5E] font-bold flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border-2 border-slate-300 transition-colors self-start sm:self-auto whitespace-nowrap shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Project
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-white border-2 border-emerald-600 text-emerald-950 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm font-semibold">
            <div class="p-1 rounded-lg bg-emerald-600 text-white">
                <i data-lucide="check" class="w-4 h-4"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- BAGIAN 1: FORM TAMBAH TESTIMONI (Sebelah Kiri / Atas) --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border-2 border-slate-300 sticky top-24">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="message-square-plus" class="w-5 h-5 text-[#F7941D]"></i>
                    Tambah Testimoni
                </h3>
                
                <form action="{{ route('admin.testimonials.store') }}" method="POST">
                    @csrf
                    
                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Client</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-lg focus:ring-[#001D5E] focus:border-[#001D5E]" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    {{-- Jabatan / Role --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan (Opsional)</label>
                        <input type="text" name="role" class="w-full border-gray-300 rounded-lg focus:ring-[#001D5E] focus:border-[#001D5E]" placeholder="CEO, PT Maju Mundur">
                    </div>

                    {{-- Rating Bintang --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rating Bintang</label>
                        <select name="stars" class="w-full border-gray-300 rounded-lg focus:ring-[#001D5E] focus:border-[#001D5E]">
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sempurna)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                            <option value="3">⭐⭐⭐ (3 - Oke)</option>
                            <option value="2">⭐⭐ (2 - Kurang)</option>
                            <option value="1">⭐ (1 - Buruk)</option>
                        </select>
                    </div>

                    {{-- Isi Testimoni --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kata Mereka</label>
                        <textarea name="content" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-[#001D5E] focus:border-[#001D5E]" placeholder="Tulis pengalaman klien di sini..." required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-[#001D5E] hover:bg-blue-900 text-white font-bold py-3 rounded-lg transition-colors flex justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </button>
                </form>
            </div>
        </div>

        {{-- BAGIAN 2: DAFTAR TESTIMONI (Sebelah Kanan / Bawah) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden">
                <div class="p-6 border-b-2 border-slate-200 bg-slate-100">
                    <h3 class="font-black text-slate-800">Daftar Testimoni ({{ $testimonials->count() }})</h3>
                </div>

                @if($testimonials->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($testimonials as $testi)
                        <div class="p-6 hover:bg-gray-50 transition-colors group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <h4 class="font-bold text-[#001D5E] text-lg">{{ $testi->name }}</h4>
                                        @if($testi->role)
                                            <span class="text-xs bg-[#001D5E] text-white px-2.5 py-0.5 rounded-md font-bold">{{ $testi->role }}</span>
                                        @else
                                            <span class="text-xs bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-md font-bold">Client</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Tampilan Bintang --}}
                                    <div class="flex text-yellow-400 mb-2">
                                        @for($i=0; $i < $testi->stars; $i++)
                                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        @endfor
                                    </div>

                                    <p class="text-gray-600 text-sm italic">"{{ $testi->content }}"</p>
                                    <p class="text-xs text-gray-400 mt-2">Dibuat: {{ $testi->created_at->format('d M Y') }}</p>
                                </div>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni dari {{ $testi->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-red-600" title="Hapus">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center text-gray-500">
                        <i data-lucide="message-square-off" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>Belum ada testimoni yang ditambahkan.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection