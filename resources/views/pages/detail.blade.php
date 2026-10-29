@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-20">

    {{-- HERO SECTION --}}
    <div class="relative w-full h-[450px] md:h-[550px] bg-gray-900 group flex items-center">
        <img src="{{ $event->image }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale group-hover:grayscale-0 transition-all duration-700">
        <div class="absolute inset-0 bg-[#001D5E]/80"></div>

        <div class="absolute top-24 left-4 md:left-8 z-30">
            <a href="{{ route('portfolio') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-sm hover:bg-white/20 hover:scale-105 transition-all duration-300 shadow-lg">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Portofolio</span>
            </a>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-10">
            <span class="bg-[#F7941D] text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider mb-4 inline-block shadow-md">
                {{ $event->status == 'upcoming' ? 'Project Persiapan' : ($event->status == 'ongoing' ? 'Sedang Berlangsung' : 'Project Selesai') }}
            </span>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6 drop-shadow-lg max-w-4xl">
                {{ $event->title }}
            </h1>
            <div class="flex flex-col md:flex-row gap-4 md:gap-8 text-white/90 text-sm md:text-base font-medium">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-[#F7941D]"></i> {{ $event->date }}
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-[#F7941D]"></i> {{ $event->location }}
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 md:-mt-32 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (Deskripsi) --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-[#001D5E] mb-4 flex items-center gap-2 border-b pb-4">
                        <i data-lucide="file-text" class="w-5 h-5 text-[#F7941D]"></i> Detail Project
                    </h3>
                    <div class="prose text-gray-600 leading-relaxed text-justify">
                        <p class="mb-4">
                            Stukka Events dengan bangga mempersembahkan <strong>{{ $event->title }}</strong>. Project ini merupakan hasil kolaborasi strategis untuk menghadirkan pengalaman event yang tak terlupakan di {{ $event->location }}.
                        </p>
                        <p>
                            Dalam project ini, tim Stukka bertanggung jawab penuh atas manajemen acara, produksi kreatif, dan operasional lapangan untuk memastikan tujuan klien tercapai dengan sempurna.
                        </p>
                        
                        {{-- Kolom Scope of Work --}}
                        <div class="bg-white p-5 rounded-xl mt-6 border-2 border-slate-300 shadow-sm">
                            <h4 class="font-bold text-[#001D5E] mb-3 flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-[#F7941D]"></i> Lingkup Pekerjaan (Scope of Work):
                            </h4>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#001D5E] rounded-full"></div> Event Conceptualization</li>
                                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#001D5E] rounded-full"></div> Venue Management</li>
                                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#001D5E] rounded-full"></div> Show Management</li>
                                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#001D5E] rounded-full"></div> Production & Logistics</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Gallery / Documentation Placeholder --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-[#001D5E] mb-4 flex items-center gap-2">
                        <i data-lucide="image" class="w-5 h-5 text-[#F7941D]"></i> Dokumentasi Lapangan
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        
                        {{-- Foto 1 --}}
                        @if($event->doc1)
                            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
                                <img src="{{ asset($event->doc1) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            </div>
                        @endif

                        {{-- Foto 2 --}}
                        @if($event->doc2)
                            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
                                <img src="{{ asset($event->doc2) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            </div>
                        @endif

                        {{-- Foto 3 --}}
                        @if($event->doc3)
                            <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer relative">
                                <img src="{{ asset($event->doc3) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            </div>
                        @endif

                        {{-- Tampilkan pesan jika tidak ada foto --}}
                        @if(!$event->doc1 && !$event->doc2 && !$event->doc3)
                            <p class="text-gray-400 text-sm col-span-3 italic">Dokumentasi belum tersedia.</p>
                        @endif

                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Informasi Klien - PENGGANTI HARGA) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">
                    
                    {{-- Card Data Project --}}
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                        <div class="bg-[#001D5E] p-4 text-center">
                            <p class="text-white text-sm font-bold uppercase tracking-widest">Data Project</p>
                        </div>
                        <div class="p-6">
                            
                            {{-- Info Klien --}}
                            <div class="mb-6">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Kolaborasi Dengan</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="building-2" class="w-6 h-6 text-[#001D5E]"></i>
                                    </div>
                                    <div>
                                        {{-- Karena belum ada data 'client_name', kita hardcode/simulasi dulu --}}
                                        <h4 class="font-black text-[#001D5E] text-lg leading-tight">{{ $event->client_name }}</h4>
                                        <p class="text-xs text-gray-500">Official Client</p>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="border-gray-100 my-4">

                            {{-- Detail Lain --}}
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Lokasi</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $event->location }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Tanggal Pelaksanaan</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $event->date }}</p>
                                </div>
                            </div>

                            <div class="mt-8">
                                <a href="https://wa.me/628123456789" target="_blank"
                                   class="w-full flex items-center justify-center gap-2 bg-[#F7941D] hover:bg-orange-600 text-white py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-orange-200">
                                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                                    Diskusikan Project Ini
                                </a>
                                <p class="text-[10px] text-center text-gray-400 mt-2">Ingin membuat event serupa? Hubungi kami.</p>
                            </div>

                        </div>
                    </div>

                    {{-- Stukka Badge --}}
                    <div class="bg-[#001D5E]/5 border border-[#001D5E]/10 rounded-xl p-4 flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#001D5E] rounded-full flex items-center justify-center text-white font-black text-lg shrink-0">
                            S
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Executed By</p>
                            <p class="font-bold text-[#001D5E]">Stukka Digital EO</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection