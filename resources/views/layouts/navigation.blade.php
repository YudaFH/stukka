<nav x-data="{ open: false }" class="bg-[#001D5E] text-white sticky top-0 w-full z-50 border-b border-blue-900 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            {{-- 1. LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer">
                <img src="{{ asset('images/LOGO_KK.png') }}" 
                     alt="Stukka Events" 
                     class="h-12 w-auto object-contain">
            </a>

            {{-- 2. DESKTOP MENU (Tampil di Layar Besar) --}}
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    
                    <a href="{{ route('home') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                       {{ request()->routeIs('home') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                       Home
                    </a>

                    <a href="{{ route('services') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                       {{ request()->routeIs('services') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                       Services
                    </a>

                    <a href="{{ route('portfolio') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                       {{ request()->routeIs('portfolio*') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                       Portofolio
                    </a>

                </div>
            </div>

            {{-- 3. MENU KANAN DESKTOP (Auth Logic) --}}
            {{-- 3. MENU KANAN DESKTOP (Auth Logic) --}}
            <div class="hidden md:flex gap-4 items-center">
                @auth
                    {{-- Quick Action Button per Role --}}
                    @if(Auth::user()->usertype === 'karyawan')
                        <a href="{{ route('karyawan.dashboard') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-[#F7941D] hover:bg-orange-600 text-white shadow-sm border-2 border-orange-600 transition-all">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                            <span>Dashboard Karyawan</span>
                        </a>
                    @elseif(Auth::user()->usertype === 'admin')
                        <a href="{{ route('admin.index') }}" 
                           class="water-fill-btn group relative inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-sm border-2 border-blue-500 transition-all overflow-hidden">
                            {{-- Water Waves Layer --}}
                            <span class="water-layer" aria-hidden="true">
                                <span class="water-liquid-back"></span>
                                <span class="water-liquid"></span>
                                <span class="water-bubbles">
                                    <span class="bubble b1"></span>
                                    <span class="bubble b2"></span>
                                </span>
                            </span>
                            <span class="relative z-10 inline-flex items-center gap-1.5">
                                <span>Panel Admin</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1"></i>
                            </span>
                        </a>
                    @endif

                    {{-- Dropdown User sudah Login --}}
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-white font-medium hover:text-[#F7941D] transition-colors focus:outline-none py-1.5">
                            <span>Halo, {{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>
                        
                        {{-- Dropdown Content --}}
                        <div class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl py-2 text-gray-800 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right z-50 border-2 border-slate-200">
                            
                            <div class="px-4 py-2.5">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-extrabold text-[#001D5E] truncate">{{ Auth::user()->name }}</p>
                                     @if(Auth::user()->usertype === 'karyawan')
                                        <span class="px-2 py-0.5 text-[10px] font-black bg-[#F7941D] text-white rounded uppercase">Karyawan</span>
                                    @elseif(Auth::user()->usertype === 'admin')
                                        <span class="px-2 py-0.5 text-[10px] font-black bg-[#001D5E] text-white rounded uppercase">Admin</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-black bg-slate-700 text-white rounded uppercase">Member</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="my-1 border-t-2 border-slate-100"></div>

                            {{-- Menu Dashboard Berdasarkan Role --}}
                            @if(Auth::user()->usertype === 'admin')
                                <a href="{{ route('admin.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 hover:text-[#001D5E] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#F7941D]"></i>
                                        <span class="font-bold">Dashboard Admin</span>
                                    </div>
                                </a>
                                <a href="{{ route('admin.attendances.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 hover:text-[#001D5E] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar-check" class="w-4 h-4 text-[#F7941D]"></i>
                                        <span class="font-medium">Rekap Presensi</span>
                                    </div>
                                </a>
                            @elseif(Auth::user()->usertype === 'karyawan')
                                <a href="{{ route('karyawan.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 hover:text-[#001D5E] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#F7941D]"></i>
                                        <span class="font-bold">Dashboard Karyawan</span>
                                    </div>
                                </a>
                                <a href="{{ route('karyawan.attendance.scan') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 hover:text-[#001D5E] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="scan-face" class="w-4 h-4 text-[#F7941D]"></i>
                                        <span class="font-medium">Presensi Wajah</span>
                                    </div>
                                </a>
                                <a href="{{ route('karyawan.attendance.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 hover:text-[#001D5E] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar-days" class="w-4 h-4 text-[#F7941D]"></i>
                                        <span class="font-medium">Riwayat Absensi</span>
                                    </div>
                                </a>
                            @endif

                            <div class="my-1 border-t-2 border-slate-100"></div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-600 hover:text-white font-bold transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- 4. TOMBOL MOBILE (Hamburger) --}}
            <div class="-mr-2 flex md:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-900 focus:outline-none transition-colors">
                    {{-- Ikon Garis Tiga (Buka) --}}
                    <svg :class="{'hidden': open, 'block': ! open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    {{-- Ikon Silang (Tutup) --}}
                    <svg :class="{'hidden': ! open, 'block': open }" class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 5. ISI MENU MOBILE (Tampil Saat Tombol Diklik) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-[#001540] border-t border-blue-900">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            
            {{-- Mobile Links --}}
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-900 {{ request()->routeIs('home') ? 'bg-blue-900 text-[#F7941D]' : '' }}">Home</a>
            <a href="{{ route('services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900 {{ request()->routeIs('services') ? 'bg-blue-900 text-[#F7941D]' : '' }}">Services</a>
            <a href="{{ route('portfolio') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900 {{ request()->routeIs('portfolio*') ? 'bg-blue-900 text-[#F7941D]' : '' }}">Portofolio</a>

            {{-- Mobile Auth Section --}}
            <div class="mt-4 border-t border-blue-800 pt-4 pb-2 px-2">
                @auth
                    <div class="px-3 mb-3">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-base text-white">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->usertype === 'karyawan')
                                <span class="px-2 py-0.5 text-[10px] font-black bg-orange-500 text-white rounded">KARYAWAN</span>
                            @elseif(Auth::user()->usertype === 'admin')
                                <span class="px-2 py-0.5 text-[10px] font-black bg-blue-500 text-white rounded">ADMIN</span>
                            @endif
                        </div>
                        <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                    
                    @if(Auth::user()->usertype === 'admin')
                        <a href="{{ route('admin.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900">Dashboard Admin</a>
                        <a href="{{ route('admin.attendances.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900">Rekap Presensi</a>
                    @elseif(Auth::user()->usertype === 'karyawan')
                        <a href="{{ route('karyawan.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-bold text-[#F7941D] hover:text-white hover:bg-blue-900">Dashboard Karyawan</a>
                        <a href="{{ route('karyawan.attendance.scan') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900">Presensi Wajah</a>
                        <a href="{{ route('karyawan.attendance.history') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900">Riwayat Absensi</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-400 hover:text-red-300 hover:bg-blue-900">Keluar</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>