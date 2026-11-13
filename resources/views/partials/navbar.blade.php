<nav x-data="{ open: false }" class="bg-[#001D5E] text-white sticky top-0 w-full z-50 border-b border-blue-900 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            {{-- 1. LOGO --}}
            <a href="{{ auth()->check() ? (auth()->user()->usertype === 'admin' ? route('admin.index') : route('karyawan.dashboard')) : route('login') }}" class="flex items-center gap-2 cursor-pointer" title="Login / Dashboard">
                <img src="{{ asset('images/LOGO_KK.png') }}" 
                     alt="Stukka Events" 
                     class="h-12 w-auto object-contain">
            </a>

            {{-- 2. DESKTOP MENU (Hanya muncul di layar besar) --}}
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="{{ route('home') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                       {{ request()->routeIs('home') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                       Home
                    </a>

                    <a href="{{ route('portfolio') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                       {{ request()->routeIs('portfolio*') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                       Portofolio
                    </a>

                    <a href="{{ route('services') }}" 
                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors 
                    {{ request()->routeIs('services') ? 'text-white border-b-2 border-[#F7941D]' : 'text-blue-200 hover:text-[#F7941D]' }}">
                    Services
                    </a>
                </div>
            </div>

            {{-- 3. AUTH BUTTONS (Desktop) --}}
            <div class="hidden md:flex gap-4">
                <a href="{{ route('login') }}" class="text-blue-200 hover:text-white font-medium px-4 py-2 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-[#F7941D] hover:bg-orange-600 text-white px-6 py-2 rounded-full font-medium transition-colors shadow-lg shadow-orange-500/30 transform hover:scale-105">
                    Daftar
                </a>
            </div>

            {{-- 4. MOBILE MENU BUTTON (Hamburger) --}}
            <div class="-mr-2 flex md:hidden">
                {{-- 
                    PERBAIKAN DISINI:
                    - Menggunakan @click="open = !open" untuk switch status.
                    - Ikon berubah otomatis (Garis Tiga <-> Tanda Silang/X).
                --}}
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-900 focus:outline-none transition-colors">
                    
                    {{-- Ikon Garis Tiga (Muncul saat open == false) --}}
                    <svg :class="{'hidden': open, 'block': ! open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    
                    {{-- Ikon Silang (Muncul saat open == true) --}}
                    <svg :class="{'hidden': ! open, 'block': open }" class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 5. ISI MENU MOBILE (Bagian yang Hilang Sebelumnya) --}}
    {{-- 
        Bagian ini hanya muncul jika 'open' bernilai true.
        Kita gunakan x-show dan transition agar animasinya mulus.
    --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-[#001540] border-t border-blue-900">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            
            {{-- Link Menu Mobile --}}
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-900 {{ request()->routeIs('home') ? 'bg-blue-900 text-[#F7941D]' : '' }}">
                Home
            </a>

            <a href="{{ route('portfolio') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900 {{ request()->routeIs('portfolio*') ? 'bg-blue-900 text-[#F7941D]' : '' }}">
                Portofolio
            </a>

            <a href="{{ route('services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-900 {{ request()->routeIs('services') ? 'bg-blue-900 text-[#F7941D]' : '' }}">
                Services
            </a>

            {{-- Auth Mobile --}}
            <div class="mt-4 border-t border-blue-800 pt-4 pb-2">
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-blue-200 hover:text-white mb-2">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-[#F7941D] text-white rounded-lg font-bold shadow-lg">
                    Daftar Sekarang
                </a>
            </div>

        </div>
    </div>
</nav>