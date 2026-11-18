<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Stukka Events & Digital</title>

    <!-- Favicon Logo Stukka -->
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LOGO_KK.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/LOGO_KK.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (App CSS + Fallback CDN) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .bg-mesh {
            background-color: #001542;
            background-image: 
                radial-gradient(at 0% 0%, rgba(247, 148, 29, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(0, 29, 94, 0.8) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(247, 148, 29, 0.12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(30, 58, 138, 0.6) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen text-slate-800 flex flex-col justify-between selection:bg-[#F7941D] selection:text-white">

    {{-- Top Navbar / Logo Brand --}}
    <header class="w-full py-6 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group transition-transform hover:scale-102">
                <img src="{{ asset('images/LOGO_KK.png') }}" alt="Stukka Logo" class="h-10 w-auto object-contain drop-shadow-md">
                <span class="text-white font-black text-xl sm:text-2xl tracking-tight">STUKKA</span>
            </a>

            <div class="flex items-center gap-3">
                @auth
                    @if(Auth::user()->hasAdminAccess())
                        <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-xs">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                            <span>Admin Panel</span>
                        </a>
                    @elseif(Auth::user()->isKaryawan())
                        <a href="{{ route('karyawan.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-xs">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                            <span>Portal Karyawan</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-xs">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-[#F7941D] hover:bg-orange-500 text-white text-xs font-bold shadow-md shadow-orange-500/20 transition-all">
                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                        <span>Masuk</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Error Card Container --}}
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-auto">
        <div class="w-full max-w-xl glass-card rounded-3xl p-6 sm:p-10 shadow-2xl border-2 border-white/40 text-center relative overflow-hidden">
            
            {{-- Accent Top Glow Line --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#001D5E] via-[#F7941D] to-[#001D5E]"></div>

            {{-- Big Code & Icon Badge --}}
            <div class="relative inline-flex items-center justify-center mb-6 mt-2">
                <span class="text-7xl sm:text-8xl font-black tracking-tighter text-slate-100 select-none">
                    @yield('code', '404')
                </span>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 sm:w-22 sm:h-22 rounded-2xl @yield('badge_bg', 'bg-blue-50') border-2 @yield('badge_border', 'border-blue-200') shadow-lg flex items-center justify-center transform hover:rotate-6 transition-transform">
                        <i data-lucide="@yield('icon', 'alert-triangle')" class="w-10 h-10 sm:w-11 sm:h-11 @yield('icon_color', 'text-[#001D5E]')"></i>
                    </div>
                </div>
            </div>

            {{-- Error Title & Message --}}
            <h1 class="text-2xl sm:text-3xl font-black text-[#001D5E] tracking-tight mb-3">
                @yield('headline', 'Terjadi Kesalahan')
            </h1>

            <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed max-w-md mx-auto mb-8">
                @yield('message', 'Halaman yang Anda tuju sedang tidak tersedia atau mengalami kendala.')
            </p>

            {{-- Optional Additional Context/Tip --}}
            @hasSection('tip')
                <div class="mb-8 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-600 font-medium text-left flex items-start gap-3 max-w-md mx-auto">
                    <i data-lucide="info" class="w-5 h-5 text-[#F7941D] shrink-0 mt-0.5"></i>
                    <div>@yield('tip')</div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#001D5E] hover:bg-[#F7941D] text-white font-bold text-sm shadow-md transition-all">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>Kembali ke Beranda</span>
                </a>

                <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ url('/') }}'" 
                        type="button"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm border border-slate-300 transition-all cursor-pointer">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>

            {{-- Quick Help / Contact --}}
            <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400 font-semibold flex items-center justify-center gap-2">
                <span>Butuh bantuan tim kami?</span>
                <a href="{{ url('/services') }}" class="text-[#001D5E] hover:text-[#F7941D] font-bold underline transition-colors">
                    Hubungi Stukka Creative
                </a>
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full py-5 px-4 text-center text-xs text-blue-200/80 font-medium">
        &copy; {{ date('Y') }} Stukka Events & Digital Creative. All rights reserved.
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
