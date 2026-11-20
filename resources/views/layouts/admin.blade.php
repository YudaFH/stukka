<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - Stukka Events</title>

    <!-- Favicon Logo Stukka -->
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/LOGO_KK.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <style>
        /* Custom Scrollbar untuk Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #001540; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #F7941D; border-radius: 10px; }

        /* Flatpickr Stukka Styling */
        .flatpickr-calendar {
            font-family: inherit;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 29, 94, 0.15), 0 8px 10px -6px rgba(0, 29, 94, 0.1);
            border: 2px solid #e2e8f0;
            overflow: hidden;
        }
        .flatpickr-months {
            background: #001D5E;
            color: #fff;
            padding: 4px 0;
        }
        .flatpickr-months .flatpickr-month {
            color: #fff;
            fill: #fff;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700;
        }
        .flatpickr-weekdays {
            background: #001542;
        }
        span.flatpickr-weekday {
            color: #F7941D !important;
            font-weight: 700;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange {
            background: #001D5E !important;
            border-color: #001D5E !important;
            color: #fff !important;
            font-weight: 700;
        }
        .flatpickr-day.today {
            border-color: #F7941D;
        }
        .flatpickr-day.inRange {
            background: #e0e7ff !important;
            box-shadow: -5px 0 0 #e0e7ff, 5px 0 0 #e0e7ff;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        {{-- ======================== --}}
        {{-- 1. SIDEBAR (MENU KIRI)   --}}
        {{-- ======================== --}}
        <aside class="w-64 bg-[#001D5E] text-white flex-shrink-0 hidden md:flex flex-col shadow-2xl z-20">
            
            {{-- Logo Area --}}
            <div class="h-20 flex items-center justify-center px-4 border-b border-blue-900 bg-[#001542]">
                <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-3 font-black text-xl tracking-tight hover:opacity-90 transition-opacity">
                    <img src="{{ asset('images/LOGO_KK.png') }}" alt="Stukka Logo" class="h-9 w-auto object-contain">
                    <span class="text-white font-black tracking-tight">STUKKA</span>
                </a>
            </div>

            {{-- Menu Items --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-2">
                
                {{-- Dashboard (Untuk Semua Role) --}}
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-2">Main</p>
                <a href="{{ Auth::user()->hasAdminAccess() ? route('admin.index') : route('karyawan.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ (request()->routeIs('admin.index') || request()->routeIs('karyawan.dashboard')) ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                {{-- Content Management --}}
                @if(Auth::user()->canManageContent())
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Content Management</p>
                
                <a href="{{ route('admin.events.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.events.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    <span class="font-medium">Portofolio Project</span>
                </a>

                <a href="{{ route('admin.clients.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.clients.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                    <span class="font-medium">Client Trust (Logo)</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.testimonials.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="message-square-quote" class="w-5 h-5"></i>
                    <span class="font-medium">Testimoni Client</span>
                </a>
                @endif

                {{-- Event Operasional & Finansial --}}
                @if(Auth::user()->canManageEventFinance())
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Event & Finansial</p>

                <a href="{{ route('admin.scheduled-events.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.scheduled-events.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span class="font-medium">Jadwal Event & Kru</span>
                </a>

                <a href="{{ route('admin.finance.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.finance.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="wallet-cards" class="w-5 h-5"></i>
                    <span class="font-medium">Keuangan Event</span>
                </a>
                @endif

                {{-- Event & Kru (Khusus Karyawan Lapangan non-keuangan) --}}
                @if(!Auth::user()->canManageEventFinance())
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Event & Kru</p>

                <a href="{{ route('karyawan.events.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.events.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span class="font-medium">Jadwal Event & Kru</span>
                </a>
                @endif

                {{-- Presensi Mandiri (Hanya untuk Staff Karyawan / Non-Full Access Admin) --}}
                @if(!Auth::user()->isFullAccess())
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Presensi Mandiri</p>

                <a href="{{ route('karyawan.attendance.scan') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.attendance.scan') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="scan-face" class="w-5 h-5 text-[#F7941D] group-hover:text-white"></i>
                    <span class="font-medium">Presensi Wajah</span>
                </a>

                <a href="{{ route('karyawan.attendance.history') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('karyawan.attendance.history') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="history" class="w-5 h-5"></i>
                    <span class="font-medium">Riwayat Absensi</span>
                </a>

                @if(!Auth::user()->hasRegisteredFace())
                <a href="{{ route('karyawan.face.register') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-orange-500/20 text-orange-200 hover:bg-[#F7941D] hover:text-white transition-all border border-orange-400/30">
                    <span class="flex items-center gap-2.5 text-xs font-bold">
                        <i data-lucide="camera" class="w-4 h-4 text-[#F7941D]"></i>
                        Rekam Master Wajah
                    </span>
                    <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                </a>
                @endif
                @endif

                {{-- HR & Kelola Karyawan & Absensi (Khusus Super Admin Full Access) --}}
                @if(Auth::user()->isFullAccess())
                <p class="px-3 text-xs font-bold text-blue-300 uppercase tracking-wider mb-2 mt-6">Karyawan & Absensi</p>

                <a href="{{ route('admin.employees.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.employees.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium">Kelola Karyawan</span>
                </a>

                <a href="{{ route('admin.attendances.index') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.attendances.index') || request()->routeIs('admin.attendances.export.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span class="font-medium">Rekap Absensi</span>
                </a>

                <a href="{{ route('admin.attendances.settings.view') }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.attendances.settings.*') ? 'bg-[#F7941D] text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-blue-800 hover:text-white' }}">
                    <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                    <span class="font-medium">Aturan Presensi</span>
                </a>
                @endif

            </nav>

            {{-- Footer Sidebar --}}
            <div class="p-4 border-t border-blue-900 bg-[#001542]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center font-bold text-white shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[#F7941D] font-bold truncate">{{ Auth::user()->role_name }}</p>
                    </div>
                    
                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors cursor-pointer" title="Logout">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ======================== --}}
        {{-- 2. MAIN CONTENT (KANAN)  --}}
        {{-- ======================== --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50" x-data="{ moreOpen: false }">
            
            {{-- Top Header (Desktop & Mobile) --}}
            <header class="h-16 md:h-20 bg-white border-b-2 border-slate-200 flex items-center justify-between px-4 md:px-8 z-10">
                <div class="flex items-center gap-3">
                    <div class="md:hidden flex items-center gap-2">
                        <img src="{{ asset('images/LOGO_KK.png') }}" alt="Stukka Logo" class="h-7 w-auto object-contain">
                    </div>
                    <h2 class="text-base md:text-xl font-black text-[#001D5E] tracking-tight truncate">
                        @yield('title', 'Admin Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-2 md:gap-3">
                    @if(!Auth::user()->isFullAccess())
                    <a href="{{ route('karyawan.attendance.scan') }}" 
                       class="text-xs md:text-sm font-bold text-white flex items-center gap-1.5 bg-[#F7941D] hover:bg-orange-600 px-3 md:px-4 py-1.5 md:py-2 rounded-xl transition-all shadow-sm" 
                       title="Buka Kamera Presensi Wajah">
                        <i data-lucide="scan-face" class="w-3.5 h-3.5 md:w-4 md:h-4 text-white"></i>
                        <span class="hidden sm:inline">Presensi Wajah</span>
                        <span class="sm:hidden text-xs">Absen</span>
                    </a>
                    @endif

                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="text-xs md:text-sm font-bold text-blue-900 hover:text-white flex items-center gap-1.5 bg-blue-50 hover:bg-[#001D5E] px-3 md:px-4 py-1.5 md:py-2 rounded-xl transition-colors border border-blue-200" 
                       title="Buka Portal Presensi Karyawan">
                        <i data-lucide="scan-face" class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#F7941D]"></i>
                        <span class="hidden sm:inline">Portal Presensi</span>
                    </a>

                    <a href="{{ route('home') }}" target="_blank" class="text-xs md:text-sm font-bold text-gray-600 hover:text-[#001D5E] flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 px-3 md:px-4 py-1.5 md:py-2 rounded-xl transition-colors border border-gray-200">
                        <i data-lucide="globe" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                        <span class="hidden sm:inline">Website</span>
                    </a>

                    <div class="md:hidden">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-slate-100 transition-colors" title="Logout">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Scrollable Content --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 pb-28 md:pb-8">
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
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-slate-200 z-40 px-2 py-1.5 shadow-[0_-4px_10px_rgba(0,0,0,0.06)]">
                <div class="grid grid-cols-5 items-center">
                    {{-- 1. Dashboard / Home --}}
                    <a href="{{ Auth::user()->hasAdminAccess() ? route('admin.index') : route('karyawan.dashboard') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ (request()->routeIs('admin.index') || request()->routeIs('karyawan.dashboard')) ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ (request()->routeIs('admin.index') || request()->routeIs('karyawan.dashboard')) ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5">Home</span>
                    </a>

                    {{-- 2. Modul Operasional Sesuai Role --}}
                    @if(Auth::user()->isFullAccess())
                    <a href="{{ route('admin.employees.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.employees.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.employees.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5">Karyawan</span>
                    </a>
                    @elseif(Auth::user()->canManageEventFinance())
                    <a href="{{ route('admin.scheduled-events.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.scheduled-events.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.scheduled-events.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Event</span>
                    </a>
                    @elseif(Auth::user()->canManageContent())
                    <a href="{{ route('admin.events.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.events.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.events.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Portofolio</span>
                    </a>
                    @else
                    <a href="{{ route('karyawan.events.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.events.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('karyawan.events.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Event</span>
                    </a>
                    @endif

                    {{-- 3. Presensi Center Button --}}
                    <a href="{{ Auth::user()->isFullAccess() ? route('karyawan.dashboard') : route('karyawan.attendance.scan') }}" 
                       class="flex flex-col items-center justify-center -mt-5 group" title="Buka Presensi">
                        <div class="w-12 h-12 rounded-full bg-[#F7941D] text-white flex items-center justify-center border-4 border-white shadow-md group-hover:bg-orange-600 transition-colors">
                            <i data-lucide="scan-face" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold text-slate-700">{{ Auth::user()->isFullAccess() ? 'Presensi' : 'Absen' }}</span>
                    </a>

                    {{-- 4. Modul Tambahan Sesuai Role --}}
                    @if(Auth::user()->isFullAccess())
                    <a href="{{ route('admin.attendances.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.attendances.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.attendances.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Rekap</span>
                    </a>
                    @elseif(Auth::user()->canManageEventFinance())
                    <a href="{{ route('admin.finance.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.finance.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.finance.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="wallet-cards" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Keuangan</span>
                    </a>
                    @elseif(Auth::user()->canManageContent())
                    <a href="{{ route('admin.clients.index') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('admin.clients.*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('admin.clients.*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Client</span>
                    </a>
                    @else
                    <a href="{{ route('karyawan.attendance.history') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('karyawan.attendance.history') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('karyawan.attendance.history') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="history" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold">Riwayat</span>
                    </a>
                    @endif

                    {{-- 5. Menu CMS Lainnya (Drawer Toggle) --}}
                    <button type="button" @click="moreOpen = !moreOpen"
                       class="flex flex-col items-center justify-center py-1 text-slate-500 hover:text-slate-800 transition-colors cursor-pointer">
                        <div class="p-1 rounded-xl" :class="moreOpen ? 'bg-[#F7941D] text-white' : ''">
                            <i data-lucide="grid" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] mt-0.5 font-bold" :class="moreOpen ? 'text-[#F7941D]' : ''">Menu</span>
                    </button>
                </div>
            </nav>

            {{-- Bottom Sheet Modal (Menu CMS Lengkap untuk Mobile) --}}
            <div x-show="moreOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="md:hidden fixed inset-0 bg-black/50 z-50 flex flex-col justify-end"
                 @click.self="moreOpen = false"
                 style="display: none;">
                
                <div class="bg-white rounded-t-3xl border-t-2 border-slate-300 p-6 pb-24 shadow-2xl max-h-[80vh] overflow-y-auto">
                    <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-5"></div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-black text-[#001D5E]">Semua Menu Admin</h3>
                        <button type="button" @click="moreOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-6">
                        @if(Auth::user()->canManageEventFinance())
                        {{-- Jadwal Event --}}
                        <a href="{{ route('admin.scheduled-events.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Jadwal Event</span>
                        </a>

                        {{-- Keuangan Event --}}
                        <a href="{{ route('admin.finance.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="wallet-cards" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Keuangan Event</span>
                        </a>
                        @endif

                        @if(Auth::user()->canManageContent())
                        {{-- Portofolio --}}
                        <a href="{{ route('admin.events.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="briefcase" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Portofolio</span>
                        </a>

                        {{-- Client Trust --}}
                        <a href="{{ route('admin.clients.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Logo Client</span>
                        </a>

                        {{-- Testimoni --}}
                        <a href="{{ route('admin.testimonials.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-[#F7941D] flex items-center justify-center mb-1.5 shadow-xs">
                                <i data-lucide="message-square-quote" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Testimoni</span>
                        </a>
                        @endif

                        @if(Auth::user()->canManageEmployees())
                        {{-- Manajemen Karyawan --}}
                        <a href="{{ route('admin.employees.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Karyawan</span>
                        </a>
                        @endif

                        @if(Auth::user()->canManageAttendance())
                        {{-- Rekap Absensi --}}
                        <a href="{{ route('admin.attendances.index') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="scan-face" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Rekap Absensi</span>
                        </a>

                        {{-- Aturan Presensi --}}
                        <a href="{{ route('admin.attendances.settings.view') }}" 
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#F7941D] text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Aturan Jam</span>
                        </a>
                        @endif

                        {{-- Lihat Website --}}
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-[#F7941D] text-center transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 text-white flex items-center justify-center mb-1.5">
                                <i data-lucide="globe" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Website</span>
                        </a>

                        {{-- Keluar --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" 
                               class="w-full flex flex-col items-center p-3 rounded-2xl border-2 border-slate-300 bg-white hover:border-red-600 text-center transition-colors cursor-pointer">
                                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center mb-1.5">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                </div>
                                <span class="text-xs font-bold text-red-600">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        
        </div>

    </div>

    <x-toast-notification />

    <script>
        lucide.createIcons();
    </script>

    @stack('scripts')
</body>
</html>