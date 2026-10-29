<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Stukka Events') }}</title>

    <!-- Favicon Logo Stukka -->
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/LOGO_KK.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">
<div class="min-h-screen flex flex-col">

    {{-- Topbar Dashboard --}}
    <header class="bg-white/80 backdrop-blur border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                {{-- Brand --}}
                <a href="{{ url('/my-dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/Stukka1.png') }}"
                         alt="Stukka Events"
                         class="h-9 w-auto object-contain">
                    <div class="hidden sm:block">
                        <p class="font-black text-[#001D5E] leading-none">Dashboard</p>
                        <p class="text-xs text-gray-500">Kelola permintaan event Anda</p>
                    </div>
                </a>

                {{-- Nav --}}
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ url('/my-dashboard') }}"
                       class="px-4 py-2 rounded-xl font-bold text-sm {{ request()->is('my-dashboard*') ? 'bg-[#001D5E] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Riwayat
                    </a>

                    <a href="{{ route('services') }}"
                       class="px-4 py-2 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100">
                        Booking Baru
                    </a>

                    {{-- Link balik ke website (opsional) --}}
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100">
                        Ke Website
                    </a>
                </nav>

                {{-- User Menu --}}
                <div class="flex items-center gap-3">

                    {{-- Quick WhatsApp (opsional, ganti nomor) --}}
                    <a href="https://wa.me/6285813505686"
                       target="_blank"
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 font-bold text-sm hover:bg-gray-100">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Chat Admin
                    </a>

                    {{-- Dropdown akun (simple) --}}
                    <div class="relative">
                        <details class="group">
                            <summary class="list-none cursor-pointer">
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 border border-transparent group-open:border-gray-200">
                                    <div class="h-9 w-9 rounded-full bg-[#001D5E] flex items-center justify-center font-black text-white">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="hidden sm:block leading-tight">
                                        <p class="font-extrabold text-sm text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                                </div>
                            </summary>

                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 p-2">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 text-sm font-bold text-gray-700">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                    Profil
                                </a>

                                <div class="my-1 border-t border-gray-100"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-red-600 hover:text-white text-sm font-bold text-red-600 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>

                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-grow pb-24 md:pb-0">
        @yield('content')
    </main>

    {{-- Footer kecil khusus dashboard --}}
    <footer class="border-t border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p>&copy; {{ date('Y') }} Stukka Events. Dashboard.</p>
            <p class="text-gray-400">Butuh bantuan? Gunakan tombol “Chat Admin”.</p>
        </div>
    </footer>

    {{-- Mobile Bottom Navigation Bar (User) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-slate-200 z-50 px-3 py-1.5 shadow-[0_-4px_10px_rgba(0,0,0,0.06)]">
        <div class="grid grid-cols-4 items-center">
            {{-- 1. Riwayat Booking --}}
            <a href="{{ url('/my-dashboard') }}" 
               class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('my-dashboard*') ? 'text-[#001D5E] font-black' : 'text-slate-500 hover:text-slate-800' }}">
                <div class="p-1 rounded-xl {{ request()->is('my-dashboard*') ? 'bg-[#001D5E] text-white' : '' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] mt-0.5">Booking</span>
            </a>

            {{-- 2. Booking Baru (Elevated Action) --}}
            <a href="{{ route('services') }}" 
               class="flex flex-col items-center justify-center -mt-5 group">
                <div class="w-12 h-12 rounded-full bg-[#F7941D] text-white flex items-center justify-center border-4 border-white shadow-md group-hover:bg-orange-600 transition-colors">
                    <i data-lucide="plus" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] mt-0.5 font-bold text-slate-700">Order Baru</span>
            </a>

            {{-- 3. Chat WA Admin --}}
            <a href="https://wa.me/6285813505686" target="_blank"
               class="flex flex-col items-center justify-center py-1 transition-colors text-slate-500 hover:text-emerald-600">
                <div class="p-1 rounded-xl">
                    <i data-lucide="message-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-[10px] mt-0.5 font-bold text-emerald-700">Bantuan</span>
            </a>

            {{-- 4. Ke Website --}}
            <a href="{{ route('home') }}" 
               class="flex flex-col items-center justify-center py-1 transition-colors text-slate-500 hover:text-slate-800">
                <div class="p-1 rounded-xl">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] mt-0.5">Website</span>
            </a>
        </div>
    </nav>

</div>

<x-toast-notification />

<script>
    lucide.createIcons();
</script>
</body>
</html>
