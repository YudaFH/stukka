@extends('layouts.app')

@section('content')
    <div class="min-h-screen pt-32 pb-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-[#001D5E] mb-8">
                Dashboard Klien @auth {{ Auth::user()->name }} @endauth
            </h1>
            
            <div class="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-[#F7941D]">
                <p class="text-xl text-gray-700 mb-6">
                    Selamat datang di halaman manajemen event Anda.
                </p>
                
                @auth
                    <p class="text-lg font-medium text-gray-600 mb-8">
                        Anda telah berhasil masuk sebagai: <span class="text-[#001D5E] font-bold">{{ Auth::user()->email }}</span>
                    </p>

                    <h2 class="text-2xl font-bold text-[#001D5E] mb-4">Status Booking Terbaru</h2>
                    <div class="p-4 bg-white rounded-xl flex items-center justify-between shadow-sm border-2 border-slate-300">
                        <div>
                            <p class="font-bold text-lg text-[#001D5E]">Neon Music Festival 2024</p>
                            <p class="text-sm text-gray-500">Tanggal: 24 Okt 2024 | Lokasi: GBK Senayan</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-lg">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i> Dikonfirmasi
                        </span>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-full font-bold hover:bg-red-700 transition-colors flex items-center gap-2">
                                <i data-lucide="log-out" class="w-5 h-5"></i> Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-lg text-red-500">Anda belum login. Silakan <a href="{{ route('login') }}" class="text-[#F7941D] font-bold hover:underline">Masuk</a>.</p>
                @endauth
            </div>
        </div>
    </div>
@endsection