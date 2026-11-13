@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- HEADER HALAMAN --}}
    <div class="relative w-full bg-[#001D5E] pt-32 pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" 
             style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSc2MCcgaGVpZ2h0PSc2MCc+PGcgZmlsbD0nI2ZmZic+PHBhdGggZD0nTTMwIDMwTDAgMGg2MHpNLTMwIDMwTDAgMGg2MHpNLTkwIDMwTDAgMGg2MHpNJy8+PC9nPjwvc3ZnPg==');">
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-4">PORTOFOLIO PROJECT</h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto italic">
                "Jejak karya dan kolaborasi Stukka Events bersama mitra korporat terpercaya."
            </p>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="relative z-20 -mt-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-4 md:p-6 border border-gray-100">
                <form action="{{ route('portfolio') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="flex bg-gray-100 p-1 rounded-xl w-full md:w-auto overflow-x-auto">
                            <button type="submit" name="status" value="" class="px-6 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 {{ !request('status') ? 'bg-white text-[#001D5E] shadow-sm' : 'text-gray-500 hover:text-[#001D5E] hover:bg-gray-50' }}">Semua Project</button>
                            <button type="submit" name="status" value="ongoing" class="px-6 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 {{ request('status') == 'ongoing' ? 'bg-white text-green-600 shadow-sm' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">Sedang Berjalan</button>
                            <button type="submit" name="status" value="finished" class="px-6 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200 {{ request('status') == 'finished' ? 'bg-white text-[#001D5E] shadow-sm' : 'text-gray-500 hover:text-[#001D5E] hover:bg-gray-50' }}">Selesai</button>
                        </div>
                        <div class="relative w-full md:w-96">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama project..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#F7941D] focus:outline-none transition-all placeholder-gray-400 text-gray-700">
                            <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#F7941D] transition-colors"><i data-lucide="search" class="w-5 h-5"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Project Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if(isset($events) && count($events) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 group flex flex-col h-full border border-gray-100 relative">
                        
                        {{-- Status Badge --}}
                        <div class="absolute top-4 left-4 z-10">
                            @if($event->status == 'upcoming')
                                <span class="bg-[#001D5E] text-white text-[10px] uppercase font-black px-3 py-1.5 rounded-lg shadow-md">Persiapan</span>
                            @elseif($event->status == 'ongoing')
                                <span class="bg-[#F7941D] text-white text-[10px] uppercase font-black px-3 py-1.5 rounded-lg shadow-md animate-pulse">Sedang Berlangsung</span>
                            @else
                                <span class="bg-slate-700 text-white text-[10px] uppercase font-black px-3 py-1.5 rounded-lg shadow-md">Selesai</span>
                            @endif
                        </div>

                        {{-- Image Link --}}
                        <a href="{{ route('portfolio.show', $event->id) }}" class="relative h-64 overflow-hidden bg-gray-200 block">
                            <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/5 transition-colors"></div>
                        </a>

                        {{-- Content --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-2 mb-3">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D]"></i>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $event->date }}</span>
                            </div>
                            
                            <h3 class="text-xl font-extrabold text-[#001D5E] mb-3 group-hover:text-[#F7941D] transition-colors line-clamp-2">
                                <a href="{{ route('portfolio.show', $event->id) }}">{{ $event->title }}</a>
                            </h3>

                            <div class="flex items-start gap-2 mb-6">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-1 flex-shrink-0"></i>
                                <span class="text-sm text-gray-500 font-medium line-clamp-1">{{ $event->location }}</span>
                            </div>

                            {{-- Footer Card (TANPA HARGA) --}}
                            <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                    <i data-lucide="briefcase" class="w-3 h-3"></i> Project Stukka
                                </span>
                                
                                <a href="{{ route('portfolio.show', $event->id) }}" class="text-[#001D5E] font-bold text-sm hover:text-[#F7941D] flex items-center gap-1 transition-colors">
                                    Lihat Detail <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-500 text-lg">Data project belum tersedia.</p>
                <a href="{{ route('portfolio') }}" class="mt-4 inline-block text-[#001D5E] font-bold hover:underline">Reset Filter</a>
            </div>
        @endif
    </div>
</div>
@endsection