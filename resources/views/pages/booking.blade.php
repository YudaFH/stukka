@extends('layouts.app')

@section('content')
    {{-- Container utama: Hapus 'pt-20' agar konten full ke atas --}}
    <div class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
           {{-- Left Side Info: Tambahkan 'pt-20' di dalam sidebar agar kontennya turun, tetapi warna birunya full ke atas --}}
            <div class="hidden lg:block lg:w-1/3 bg-[#001D5E] p-12 text-white shadow-2xl pt-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-200 hover:text-white mb-12 transform hover:translate-x-1 transition-transform">
                     <i data-lucide="arrow-right" class="w-4 h-4 rotate-180"></i> Kembali ke Home
                </a>

                <h2 class="text-3xl font-bold mb-6">Wujudkan Event Impian Anda Bersama Kami</h2>
                <p class="text-blue-200 mb-10 leading-relaxed">Isi formulir di samping untuk mendapatkan penawaran terbaik dan konsultasi gratis dengan tim ahli kami.</p>

                <div class="space-y-8">
                     <div class="flex items-start gap-4">
                         <div class="w-10 h-10 rounded-full bg-[#F7941D]/20 flex items-center justify-center flex-shrink-0 shadow-md">
                             <i data-lucide="check-circle" class="w-5 h-5 text-[#F7941D]"></i>
                         </div>
                         <div>
                             <h4 class="font-bold">Konsultasi Gratis</h4>
                             <p class="text-sm text-blue-300 mt-1">Diskusi konsep acara tanpa biaya sepeserpun.</p>
                         </div>
                     </div>
                     <div class="flex items-start gap-4">
                         <div class="w-10 h-10 rounded-full bg-[#F7941D]/20 flex items-center justify-center flex-shrink-0 shadow-md">
                             <i data-lucide="check-circle" class="w-5 h-5 text-[#F7941D]"></i>
                         </div>
                         <div>
                             <h4 class="font-bold">Budget Fleksibel</h4>
                             <p class="text-sm text-blue-300 mt-1">Kami menyesuaikan dengan budget yang Anda miliki.</p>
                         </div>
                     </div>
                     <div class="flex items-start gap-4">
                         <div class="w-10 h-10 rounded-full bg-[#F7941D]/20 flex items-center justify-center flex-shrink-0 shadow-md">
                             <i data-lucide="check-circle" class="w-5 h-5 text-[#F7941D]"></i>
                         </div>
                         <div>
                             <h4 class="font-bold">Full Support</h4>
                             <p class="text-sm text-blue-300 mt-1">Tim kami akan mendampingi dari A sampai Z.</p>
                         </div>
                     </div>
                </div>

                <div class="mt-20 border-t border-blue-800 pt-8">
                    <p class="text-sm text-blue-400">Butuh bantuan cepat?</p>
                    <p class="font-bold text-lg mt-1">+62 812 3456 7890</p>
                </div>
            </div>

            {{-- Right Side Form: Tambahkan 'pt-20' untuk memberi ruang dari header --}}
            <div class="w-full lg:w-2/3 p-8 lg:p-16 pt-20">
                 <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                     <div class="mb-8 border-b border-gray-100 pb-6">
                         <span class="text-[#F7941D] font-bold tracking-wider uppercase text-xs">Formulir Booking</span>
                         <h2 class="text-3xl font-extrabold text-[#001D5E] mt-2">Detail Rencana Event</h2>
                         <p class="text-gray-500 mt-2">Lengkapi data di bawah ini.</p>
                     </div>

                     <form class="space-y-8" action="{{ route('booking.store') }}" method="POST">
                         @csrf
                         {{-- PERSONAL INFO --}}
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                 <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Anda / Perusahaan</label>
                                 <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                         <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                                     </div>
                                     <input type="text" id="name" name="name" class="block w-full pl-10 pr-3 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] shadow-sm" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                                 </div>
                                 @error('name')
                                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                 @enderror
                             </div>
                             <div>
                                 <label for="whatsapp_number" class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                                 <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                         <i data-lucide="users" class="h-5 w-5 text-gray-400"></i>
                                     </div>
                                     <input type="tel" id="whatsapp_number" name="whatsapp_number" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="block w-full pl-10 pr-3 py-3 border @error('whatsapp_number') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] shadow-sm" placeholder="0812..." value="{{ old('whatsapp_number') }}" required>
                                 </div>
                                 @error('whatsapp_number')
                                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                 @enderror
                             </div>
                         </div>
                         
                         {{-- EVENT TYPE --}}
                         <div>
                             <label class="block text-sm font-bold text-gray-700 mb-4">Jenis Event</label>
                             <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                                @php
                                    $eventTypes = ['Wedding', 'Concert', 'Birthday', 'Corporate', 'Exhibition', 'Other'];
                                @endphp

                                 @foreach($eventTypes as $type)
                                     <label for="type_{{ strtolower($type) }}" class="cursor-pointer">
                                         <input type="radio" id="type_{{ strtolower($type) }}" name="event_type" value="{{ $type }}" class="peer sr-only" {{ old('event_type') == $type ? 'checked' : '' }} required>
                                         <div class="rounded-xl border-2 border-slate-300 p-3 text-center hover:bg-slate-100 peer-checked:border-[#F7941D] peer-checked:bg-[#F7941D] peer-checked:text-white transition-all shadow-sm">
                                             <span class="text-xs md:text-sm font-bold">{{ $type }}</span>
                                         </div>
                                     </label>
                                 @endforeach
                             </div>
                             @error('event_type')
                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>
                         
                         {{-- DATE & GUEST --}}
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                 <label for="event_date" class="block text-sm font-bold text-gray-700 mb-2">Rencana Tanggal</label>
                                 <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                         <i data-lucide="calendar" class="h-5 w-5 text-gray-400"></i>
                                     </div>
                                     <input type="date" id="event_date" name="event_date" class="block w-full pl-10 pr-3 py-3 border @error('event_date') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] shadow-sm" value="{{ old('event_date') }}">
                                 </div>
                                 @error('event_date')
                                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                 @enderror
                             </div>
                              <div>
                                 <label for="guest_estimate" class="block text-sm font-bold text-gray-700 mb-2">Estimasi Tamu (Pax)</label>
                                 <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                         <i data-lucide="users" class="h-5 w-5 text-gray-400"></i>
                                     </div>
                                     <select id="guest_estimate" name="guest_estimate" class="block w-full pl-10 pr-3 py-3 border @error('guest_estimate') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] bg-white shadow-sm" required>
                                         @php
                                             $guestOptions = ['< 50 Orang', '50 - 200 Orang', '200 - 500 Orang', '500 - 1000 Orang', '> 1000 Orang'];
                                         @endphp
                                         @foreach($guestOptions as $option)
                                             <option value="{{ $option }}" {{ old('guest_estimate') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 @error('guest_estimate')
                                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                 @enderror
                             </div>
                         </div>

                         {{-- BUDGET & NOTES --}}
                         <div>
                            <label for="budget_estimate" class="block text-sm font-bold text-gray-700 mb-2">Estimasi Budget</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="dollar-sign" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <select id="budget_estimate" name="budget_estimate" class="block w-full pl-10 pr-3 py-3 border @error('budget_estimate') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] bg-white shadow-sm" required>
                                    @php
                                        $budgetOptions = ['Belum ditentukan', 'IDR 10 Juta - 50 Juta', 'IDR 50 Juta - 100 Juta', 'IDR 100 Juta - 500 Juta', '> IDR 500 Juta'];
                                    @endphp
                                    @foreach($budgetOptions as $option)
                                        <option value="{{ $option }}" {{ old('budget_estimate') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('budget_estimate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                         </div>

                         <div>
                            <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan</label>
                            <div class="relative">
                                <textarea rows="4" id="notes" name="notes" class="block w-full p-4 border border-gray-300 rounded-xl focus:ring-[#F7941D] focus:border-[#F7941D] shadow-sm" placeholder="Ceritakan konsep impian Anda atau detail lainnya...">{{ old('notes') }}</textarea>
                            </div>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                         </div>

                         <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
                            <button type="submit" class="bg-[#001D5E] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-900 transition-all shadow-lg hover:shadow-xl flex items-center gap-2 transform hover:scale-105">
                                Kirim Permintaan <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </button>
                         </div>
                     </form>
                 </div>
            </div>
        </div>
@endsection