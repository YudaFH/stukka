<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Stukka Events') }}</title>

        <!-- Favicon Logo Stukka -->
        <link rel="icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/LOGO_KK.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased text-gray-900">
        {{-- UBAH DIV INI: Tambahkan 'flex flex-col' --}}
        <div class="min-h-screen bg-gray-50 flex flex-col">
            
            @include('layouts.navigation')

            {{-- UBAH MAIN: Tambahkan 'flex-grow pb-20 md:pb-0' --}}
            <main class="flex-grow pb-20 md:pb-0">
                @yield('content')
            </main>
            
<footer class="bg-[#001D5E] text-blue-200 py-16 border-t border-blue-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                 <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6 cursor-pointer">
                   <img src="{{ asset('images/Stukka1.png') }}" 
                     alt="Stukka Events" 
                     class="h-12 w-auto object-contain">
               </a>
               <p class="text-sm leading-relaxed text-blue-300">
                PT. Stukka Digital Creative
               </p>
                <p class="text-sm leading-relaxed text-blue-300">
                Jl. Z No.16
               </p>
                <p class="text-sm leading-relaxed text-blue-300">
                Jakarta, Indonesia
               </p>
                <p class="text-sm leading-relaxed text-blue-300">
                +62 878-7810-2822
               </p>
                <p class="text-sm leading-relaxed text-blue-300">
                (WhatsApp)
               </p>
            </div>
            
            <div>
              <h4 class="text-white font-bold mb-6">Services</h4>
              <ul class="space-y-3 text-sm">
                <li><a href="{{ route('services') }}" class="hover:text-[#F7941D] transition-colors">Wedding Planning</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-[#F7941D] transition-colors">Corporate Events</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-[#F7941D] transition-colors">Birthday Party</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-[#F7941D] transition-colors">Music Festival</a></li>
              </ul>
            </div>

            <div>
              <h4 class="text-white font-bold mb-6">Company</h4>
              <ul class="space-y-3 text-sm">
                <li><a href="{{ route('portfolio') }}" class="hover:text-[#F7941D] transition-colors">About Us</a></li>
                <li><a href="{{ route('portfolio') }}" class="hover:text-[#F7941D] transition-colors">Our Team</a></li>
                <li><a href="{{ route('portfolio') }}" class="hover:text-[#F7941D] transition-colors">Careers</a></li>
                <li><a href="{{ route('portfolio') }}" class="hover:text-[#F7941D] transition-colors">Contact</a></li>
              </ul>
            </div>

            <div>
              <h4 class="text-white font-bold mb-6">Connect</h4>
              <ul class="space-y-3 text-sm">
                <li><a href="javascript:void(0)" onclick="notify.info('Mengarahkan ke akun Instagram resmi @stukkadigital', 'Media Sosial')" class="hover:text-[#F7941D] transition-colors">Instagram</a></li>
                <li><a href="javascript:void(0)" onclick="notify.info('Mengarahkan ke akun TikTok resmi Stukka Events', 'Media Sosial')" class="hover:text-[#F7941D] transition-colors">TikTok</a></li>
                <li><a href="javascript:void(0)" onclick="notify.info('Mengarahkan ke profil LinkedIn resmi Stukka Digital', 'Media Sosial')" class="hover:text-[#F7941D] transition-colors">LinkedIn</a></li>
                <li><a href="javascript:void(0)" onclick="notify.info('Membuka tautan WhatsApp Customer Care Stukka', 'Customer Care')" class="hover:text-[#F7941D] transition-colors">WhatsApp</a></li>
              </ul>
            </div>
        </div>

        <div class="border-t border-blue-900 mt-12 pt-8 text-center text-sm text-blue-400">
          &copy; {{ date('Y') }} Stukka Events. All rights reserved. Dibuat oleh Marco.
        </div>
      </div>
</footer>

            {{-- Mobile Bottom Bar untuk Website Umum --}}
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-slate-200 z-50 px-3 py-1.5 shadow-[0_-4px_10px_rgba(0,0,0,0.06)]">
                <div class="grid {{ Auth::check() && in_array(Auth::user()->usertype, ['admin', 'karyawan']) ? 'grid-cols-4' : 'grid-cols-3' }} items-center">
                    {{-- 1. Home --}}
                    <a href="{{ route('home') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('home') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('home') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="home" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Home</span>
                    </a>

                    {{-- 2. Services --}}
                    <a href="{{ route('services') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('services') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('services') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Services</span>
                    </a>

                    {{-- 3. Portofolio --}}
                    <a href="{{ route('portfolio') }}" 
                       class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('portfolio*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                        <div class="p-1 rounded-xl {{ request()->routeIs('portfolio*') ? 'bg-[#001D5E] text-white' : '' }}">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Portofolio</span>
                    </a>

                    {{-- 4. Akun / Dashboard (Khusus Admin / Karyawan jika login) --}}
                    @auth
                        @if(Auth::user()->usertype === 'karyawan')
                            <a href="{{ route('karyawan.dashboard') }}" 
                               class="flex flex-col items-center justify-center py-1 transition-colors text-[#F7941D] font-black">
                                <div class="p-1 rounded-xl bg-[#F7941D] text-white">
                                    <i data-lucide="scan-face" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] mt-0.5">Karyawan</span>
                            </a>
                        @elseif(Auth::user()->usertype === 'admin')
                            <a href="{{ route('admin.index') }}" 
                               class="flex flex-col items-center justify-center py-1 transition-colors text-[#001D5E] font-black">
                                <div class="p-1 rounded-xl bg-[#001D5E] text-white">
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] mt-0.5">Admin</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>

        <x-toast-notification />

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>