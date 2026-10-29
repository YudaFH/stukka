@extends('layouts.app')

@section('content')
    <div class="min-h-screen pt-50 flex bg-white">
         {{-- Sisi Kiri (Desktop Only) --}}
         <div class="hidden lg:flex w-1/2 bg-[#001D5E] items-center justify-center relative overflow-hidden">
             <div class="relative z-20 px-16 text-white text-center">
                 <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/10 shadow-2xl">
                     <img 
                         src="https://images.unsplash.com/photo-1517457371959-b7b51e51b638?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                         alt="Illustration"
                         class="w-full h-48 object-cover rounded-xl mb-6 opacity-80"
                         onerror="this.onerror=null;this.src='https://placehold.co/800x400/001D5E/F7941D?text=Party+Time';"
                     />
                     <h2 class="text-2xl font-bold mb-2">Let's Get The Party Started!</h2>
                     <p class="text-blue-200 text-sm">Bergabung dengan ribuan klien yang telah sukses menyelenggarakan acara impian mereka.</p>
                 </div>
             </div>
         </div>

        {{-- Sisi Kanan (Form Register) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <span class="text-[#F7941D] font-bold tracking-wider uppercase text-xs">Registrasi Klien</span>
                    <h2 class="text-3xl font-extrabold text-[#001D5E] mt-2">Daftar Akun Baru</h2>
                </div>

                <form class="space-y-5" action="/register" method="POST">
                    @csrf
                     <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap / Perusahaan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name"
                                name="name"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] transition-colors shadow-sm"
                                placeholder="PT Maju Mundur / John Doe"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] transition-colors shadow-sm"
                                placeholder="contact@event.com"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] transition-colors shadow-sm"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] transition-colors shadow-sm"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-lg text-sm font-bold text-white bg-[#F7941D] hover:bg-orange-600 transition-all mt-4 group hover:scale-[1.02] transform">
                        Daftar Gratis <i data-lucide="arrow-right" class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="/login" class="font-medium text-[#F7941D] hover:text-orange-600">
                        Masuk Sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection