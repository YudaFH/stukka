<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal Karyawan') - Stukka Digital</title>

    <!-- Favicon Logo Stukka -->
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/LOGO_KK.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #001540; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #F7941D; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        {{-- ======================== --}}
        {{-- 1. SIDEBAR KARYAWAN      --}}
        {{-- ======================== --}}
        <aside class="w-72 bg-[#001D5E] text-white flex-shrink-0 hidden md:flex flex-col shadow-2xl z-20">
            
            {{-- Logo Header --}}
            <div class="h-20 flex items-center px-5 border-b border-blue-900 bg-[#001542]">
                <a href="{{ route('karyawan.dashboard') }}" class="w-full flex items-center justify-between font-bold text-xl tracking-tight hover:opacity-90 transition-opacity">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/LOGO_KK.png') }}" alt="Stukka Logo" class="h-9 w-auto object-contain">
                        <span class="text-white font-bold tracking-tight">STUKKA</span>
                    </div>
                    <span class="text-[10px] font-black px-2.5 py-1 rounded-md bg-blue-900 text-orange-300 border border-blue-800 uppercase tracking-wider shadow-xs">
                        STAFF
                    </span>
                </a>
            </div>

            {{-- Navigation Menu --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-1.5">
                
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2">Main</p>
                
                {{-- Dashboard --}}
                <a href="{{ route('karyawan.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.dashboard') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Event & Kru</p>

                {{-- Jadwal Event & Kru --}}
                <a href="{{ route('karyawan.events.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.events.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Jadwal Event & Kru</span>
                </a>

                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Presensi & Kehadiran</p>

                {{-- Scan Absensi --}}
                <a href="{{ route('karyawan.attendance.scan') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.attendance.scan') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="scan-face" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Presensi Wajah</span>
                </a>

                {{-- Riwayat Absensi --}}
                <a href="{{ route('karyawan.attendance.history') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.attendance.history') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Riwayat Absensi</span>
                </a>

                {{-- Menu Rekam Wajah: HANYA jika karyawan BELUM merekam wajah --}}
                @if(!Auth::user()->hasRegisteredFace())
                    <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Biometrik Wajah</p>

                    <a href="{{ route('karyawan.face.register') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.face.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                        <span class="font-medium text-sm">Rekam Wajah</span>
                        <span class="ml-auto w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                    </a>
                @endif

                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Lainnya</p>

                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-blue-200 hover:bg-blue-800 hover:text-white text-sm">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span>Lihat Website</span>
                </a>

            </nav>

            {{-- Footer Profil Karyawan --}}
            <div class="p-4 border-t border-blue-900 bg-[#001542]">
                <div class="flex items-center gap-3">
                    @if(Auth::user()->face_photo)
                        <img src="{{ asset('storage/' . Auth::user()->face_photo) }}" alt="Avatar" class="w-10 h-10 rounded-xl object-cover border-2 border-[#F7941D] shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-blue-700 flex items-center justify-center font-bold text-white border border-blue-500 shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[#F7941D] font-medium truncate">{{ Auth::user()->position ?? 'Staff / Crew' }}</p>
                    </div>
                    
                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-300 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-blue-900/50" title="Logout">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ======================== --}}
        {{-- 2. MAIN CONTENT AREA     --}}
        {{-- ======================== --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            {{-- Top Mobile Navbar --}}
            <header class="md:hidden bg-[#001D5E] text-white px-4 py-3 flex items-center justify-between border-b-2 border-[#001138] sticky top-0 z-30">
                <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-2.5 font-bold text-base">
                    <img src="{{ asset('images/LOGO_KK.png') }}" alt="Stukka Logo" class="h-7 w-auto object-contain">
                    <span class="text-white flex items-center">STUKKA <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md bg-blue-900 text-orange-300 ml-2 -translate-y-1 uppercase tracking-wider shadow-xs">STAFF</span></span>
                </a>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-white leading-tight truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-orange-300 leading-tight truncate max-w-[120px]">{{ Auth::user()->position ?? 'Karyawan' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-blue-200 hover:text-red-400 hover:bg-blue-900 transition-colors" title="Logout">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Main Body --}}
            <main class="flex-1 p-4 md:p-8 pb-28 md:pb-8">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border-2 border-emerald-600 bg-white p-4 flex items-center gap-3 text-emerald-950 shadow-sm">
                        <span class="p-1 rounded bg-emerald-600 text-white flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </span>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-2xl border-2 border-red-600 bg-white p-4 flex items-center gap-3 text-red-950 shadow-sm">
                        <span class="p-1 rounded bg-red-600 text-white flex items-center justify-center">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </span>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Mobile Bottom Navigation Bar --}}
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-slate-200 z-50 px-2 py-1.5 shadow-[0_-4px_10px_rgba(0,0,0,0.06)]">
                <div class="grid {{ !Auth::user()->hasRegisteredFace() ? 'grid-cols-5' : 'grid-cols-4' }} items-center">
                    {{-- 1. Dashboard --}}
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.dashboard') ? 'text-[#001D5E] font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('karyawan.dashboard') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Home</span>
                    </a>

                    {{-- 2. Presensi Wajah (Elevated Action) --}}
                    <a href="{{ route('karyawan.attendance.scan') }}" 
                       class="flex flex-col items-center justify-center -mt-5 group">
                        <div class="w-12 h-12 rounded-full bg-[#F7941D] text-white flex items-center justify-center border-4 border-white shadow-md group-hover:bg-orange-600 transition-colors">
                            <i data-lucide="scan-face" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] mt-0.5 font-bold {{ request()->routeIs('karyawan.attendance.scan') ? 'text-[#001D5E]' : 'text-slate-600' }}">Presensi</span>
                    </a>

                    {{-- 3. Jadwal Event --}}
                    <a href="{{ route('karyawan.events.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.events.*') ? 'text-[#001D5E] font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('karyawan.events.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Jadwal</span>
                    </a>

                    {{-- 4. Riwayat Absensi --}}
                    <a href="{{ route('karyawan.attendance.history') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.attendance.history') ? 'text-[#001D5E] font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('karyawan.attendance.history') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="calendar-days" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Riwayat</span>
                    </a>

                    {{-- 5. Rekam Wajah (HANYA jika belum pernah rekam wajah) --}}
                    @if(!Auth::user()->hasRegisteredFace())
                        <a href="{{ route('karyawan.face.register') }}" 
                           class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.face.*') ? 'text-[#001D5E] font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                            <div class="p-1 rounded-xl relative {{ request()->routeIs('karyawan.face.*') ? 'bg-[#001D5E] text-white' : '' }}">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            </div>
                            <span class="text-[10px] mt-0.5 text-red-600 font-semibold">Rekam</span>
                        </a>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <x-toast-notification />

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
