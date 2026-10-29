@extends('layouts.app')

@section('content')
    <div class="min-h-screen pt-50 flex bg-white">
        {{-- Sisi Kiri (Desktop Only) --}}
        <div class="hidden lg:flex w-1/2 bg-[#001D5E] items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-900/40 z-10"></div>
             <img 
                src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                alt="Party Background"
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale hover:grayscale-0 transition-all duration-700"
                onerror="this.onerror=null;this.src='https://placehold.co/1000x800/001D5E/F7941D?text=Client+Dashboard';"
            />
            <div class="relative z-20 px-16 text-white">
                <div class="mb-6 text-[#F7941D]">
                    <i data-lucide="star" class="w-12 h-12 fill-current"></i>
                </div>
                <h2 class="text-4xl font-bold leading-tight mb-6">
                    "Event tahunan perusahaan kami berjalan sangat lancar berkat tim Stukka."
                </h2>
                <div class="flex items-center gap-4">
                     <img 
                        src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" 
                        class="w-12 h-12 rounded-full border-2 border-[#F7941D] object-cover" 
                        alt="Client" 
                        onerror="this.onerror=null;this.src='https://placehold.co/100x100/F7941D/001D5E?text=BS';"
                    />
                     <div>
                         <p class="font-bold text-lg">Budi Santoso</p>
                         <p class="text-blue-300 text-sm">HR Manager, Tokopedia</p>
                     </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan (Form Login) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-[#001D5E]">Manage Your Event</h2>
                    <p class="text-gray-500 mt-2">Masuk untuk melihat status booking atau tiket Anda.</p>
                </div>

                <form class="space-y-6" action="/login" method="POST">
                    @csrf
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
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E] transition-colors shadow-sm"
                                placeholder="client@company.com"
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
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E] transition-colors shadow-sm"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                        <div class="flex justify-end mt-2">
                            <a href="#" class="text-sm font-medium text-[#001D5E] hover:text-[#F7941D]">Lupa Password?</a>
                        </div>
                    </div>

                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    <input type="hidden" name="date" value="{{ request('date') }}">


                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-lg text-sm font-bold text-white bg-[#001D5E] hover:bg-blue-900 transition-all hover:scale-[1.02] transform">
                        Masuk Dashboard
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-slate-500 font-medium">
                    Portal Internal Stukka Events & Digital • Akses Khusus Admin & Karyawan
                </p>
            </div>
        </div>
    </div>
@endsection