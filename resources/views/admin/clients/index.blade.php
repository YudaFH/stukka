@extends('layouts.admin')

@section('title', 'Client Trust (Logo)')

@section('content')
<div class="space-y-6">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Kelola Logo Client</h1>
            <p class="text-gray-500 text-sm mt-1">Mitra yang telah mempercayakan event mereka kepada Stukka.</p>
        </div>
        <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-[#001D5E] font-bold flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border-2 border-slate-300 transition-colors whitespace-nowrap shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-white border-2 border-emerald-600 text-emerald-950 px-4 py-4 rounded-xl flex items-center gap-3 shadow-sm font-semibold">
            <div class="bg-emerald-600 text-white p-1 rounded-lg"><i data-lucide="check" class="w-4 h-4"></i></div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- BAGIAN 1: FORM UPLOAD (Sticky di Kiri) --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border-2 border-slate-300 sticky top-24">
                <div class="border-b border-gray-100 pb-4 mb-4">
                    <h3 class="font-bold text-lg text-[#001D5E] flex items-center gap-2">
                        <i data-lucide="upload-cloud" class="w-5 h-5 text-[#F7941D]"></i>
                        Upload Logo Baru
                    </h3>
                </div>
                
                <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Nama Perusahaan --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Perusahaan / Instansi</label>
                        <input type="text" name="name" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl focus:ring-2 focus:ring-[#001D5E] focus:border-transparent transition-all p-3" placeholder="Contoh: PT. Pertamina" required>
                    </div>

                    {{-- Upload File dengan Preview --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">File Logo (PNG/JPG)</label>
                        
                        {{-- Custom File Input Wrapper --}}
                        <div class="relative group">
                            <input type="file" name="logo" id="logoInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required onchange="previewImage(event)">
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center group-hover:border-[#001D5E] group-hover:bg-slate-100 transition-all">
                                <i data-lucide="image-plus" class="w-8 h-8 text-gray-400 mx-auto mb-2 group-hover:text-[#001D5E]"></i>
                                <p class="text-sm text-gray-500 font-medium group-hover:text-[#001D5E]">Klik untuk pilih gambar</p>
                                <p class="text-xs text-gray-400 mt-1">Maks. 2MB</p>
                            </div>
                        </div>

                        {{-- Image Preview Container --}}
                        <div id="previewContainer" class="hidden mt-4 bg-gray-100 rounded-xl p-4 text-center border border-gray-200">
                            <p class="text-xs text-gray-500 mb-2 text-left font-bold">Preview:</p>
                            <img id="preview" src="#" alt="Preview Logo" class="h-20 mx-auto object-contain">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#001D5E] hover:bg-blue-900 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-900/20 transition-all transform active:scale-95 flex justify-center items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i> Tambahkan Logo
                    </button>
                </form>
            </div>
        </div>

        {{-- BAGIAN 2: GALERI LOGO (Kanan) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden min-h-[400px]">
                <div class="p-6 border-b-2 border-slate-200 bg-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="grid" class="w-4 h-4 text-slate-500"></i>
                        Galeri Logo ({{ $clients->count() }})
                    </h3>
                </div>

                <div class="p-6">
                    @if($clients->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($clients as $client)
                            <div class="group relative bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-lg hover:border-blue-100 transition-all duration-300 flex flex-col items-center justify-center h-40">
                                
                                {{-- Logo Image (KEMBALI KE KODE AWAL SUPAYA MUNCUL) --}}
                                <img src="{{ $client->logo }}" 
                                     alt="{{ $client->name }}" 
                                     class="h-16 w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300 transform group-hover:scale-110">
                                
                                {{-- Tooltip Name --}}
                                <p class="mt-4 text-xs font-bold text-gray-400 group-hover:text-[#001D5E] text-center line-clamp-1 transition-colors">
                                    {{ $client->name }}
                                </p>

                                {{-- Delete Button (Muncul saat Hover) --}}
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Hapus logo {{ $client->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white text-red-500 p-1.5 rounded-lg shadow-md hover:bg-red-500 hover:text-white transition-colors" title="Hapus Logo">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center h-64 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="image-off" class="w-10 h-10 text-gray-300"></i>
                            </div>
                            <h4 class="text-gray-500 font-bold text-lg">Belum ada logo</h4>
                            <p class="text-gray-400 text-sm max-w-xs mt-1">Silakan upload logo mitra di form sebelah kiri untuk menampilkannya di halaman depan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script untuk Preview Image --}}
<script>
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('preview');
        var container = document.getElementById('previewContainer');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden'); // Munculkan container preview
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection