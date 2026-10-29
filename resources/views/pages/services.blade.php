@extends('layouts.app')

@section('content')

{{-- CSS Langsung dipanggil disini agar pasti termuat --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Custom Style Flatpickr agar sesuai tema Stukka */
    .flatpickr-calendar {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: none;
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #001D5E !important;
        border-color: #001D5E !important;
    }
    .flatpickr-day.today {
        border-color: #F7941D !important;
    }
    .flatpickr-day.today:hover {
        background: #F7941D !important;
        color: white !important;
    }
    /* Inputan agar terlihat bisa diklik */
    #datepicker {
        cursor: pointer !important;
        background-color: #ffffff !important;
    }
</style>

<div class="min-h-screen bg-gray-50">
    
    {{-- 1. HERO SECTION --}}
    <div class="relative w-full bg-[#001D5E] pt-32 pb-24 px-4 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10" 
             style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSc2MCcgaGVpZ2h0PSc2MCc+PGcgZmlsbD0nI2ZmZic+PHBhdGggZD0nTTMwIDMwTDAgMGg2MHpNLTMwIDMwTDAgMGg2MHpNLTkwIDMwTDAgMGg2MHpNJy8+PC9nPjwvc3ZnPg==');">
        </div>
        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-2 block">Our Services</span>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
                Wujudkan Event Impian Anda <br> Bersama Stukka Digital
            </h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto">
                Dari konser musik berskala besar hingga corporate gathering yang elegan. Kami siap mengeksekusi visi Anda menjadi kenyataan.
            </p>
        </div>
    </div>

    {{-- 2. DAFTAR LAYANAN (GRID CUSTOM 6 KOLOM) --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 mb-20">
    {{-- 
        PERUBAHAN GRID:
        - lg:grid-cols-6 : Membagi layar desktop menjadi 6 kolom virtual.
        - md:grid-cols-2 : Tablet tetap 2 kolom standar.
        - grid-cols-1    : Mobile tetap 1 kolom (stack).
    --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
        
        {{-- 1. Brand/Event Management (Top Left) --}}
        {{-- lg:col-span-2: Memakan 2 kolom (posisi 1-2) --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-lg border-b-4 border-[#F7941D] hover:-translate-y-2 transition-transform duration-300">
            <div class="w-14 h-14 bg-[#F7941D] rounded-xl flex items-center justify-center text-white mb-6 shadow-md">
                <i data-lucide="calendar-check" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-[#001D5E] mb-3">Brand/Event Management</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Pengorganisasian Acara Digital: Menggabungkan konsep tradisional dengan teknologi digital inovatif. Mulai dari konferensi virtual hingga live streaming.
            </p>
        </div>

        {{-- 2. Visual & Creative Management (Top Center) --}}
        {{-- lg:col-span-2: Memakan 2 kolom (otomatis mengisi posisi 3-4) --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-lg border-b-4 border-[#001D5E] hover:-translate-y-2 transition-transform duration-300">
            <div class="w-14 h-14 bg-[#001D5E] rounded-xl flex items-center justify-center text-white mb-6 shadow-md">
                <i data-lucide="palette" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-[#001D5E] mb-3">Visual & Creative</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Desain kreatif, branding perusahaan, dan produksi multimedia (video promosi, animasi, grafis 3D) untuk identitas visual yang kuat.
            </p>
        </div>

        {{-- 3. Application Digital Management (Top Right) --}}
        {{-- lg:col-span-2: Memakan 2 kolom (otomatis mengisi posisi 5-6) --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-lg border-b-4 border-emerald-600 hover:-translate-y-2 transition-transform duration-300">
            <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center text-white mb-6 shadow-md">
                <i data-lucide="smartphone" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-[#001D5E] mb-3">App Digital Management</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Pengembangan aplikasi khusus, platform registrasi, dan alat interaktif untuk meningkatkan partisipasi peserta.
            </p>
        </div>

        {{-- 4. Production Management (Bottom Left-Center) --}}
        {{-- 
             lg:col-start-2 : Memaksa mulai di garis kolom ke-2.
             lg:col-span-2  : Memakan 2 kolom (jadi mengisi kolom 2 & 3).
        --}}
        <div class="lg:col-start-2 lg:col-span-2 bg-white p-8 rounded-2xl shadow-lg border-b-4 border-purple-600 hover:-translate-y-2 transition-transform duration-300">
            <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-6 shadow-md">
                <i data-lucide="settings" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-[#001D5E] mb-3">Production Management</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Manajemen total dari penjadwalan hingga operasional hari-H. Menyediakan LED Videotron, Broadcasting, dan Sound System.
            </p>
        </div>

        {{-- 5. Sponsorship Management (Bottom Right-Center) --}}
        {{-- 
             lg:col-start-4 : Memaksa mulai di garis kolom ke-4.
             lg:col-span-2  : Memakan 2 kolom (jadi mengisi kolom 4 & 5).
        --}}
        <div class="lg:col-start-4 lg:col-span-2 bg-white p-8 rounded-2xl shadow-lg border-b-4 border-red-600 hover:-translate-y-2 transition-transform duration-300">
            <div class="w-14 h-14 bg-red-600 rounded-xl flex items-center justify-center text-white mb-6 shadow-md">
                <i data-lucide="handshake" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-[#001D5E] mb-3">Sponsorship Management</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Strategi sponsorship, penyusunan proposal, negosiasi kontrak, hingga manajemen hubungan jangka panjang dengan sponsor.
            </p>
        </div>

    </div>
</div>

    {{-- 3. CEK KETERSEDIAAN JADWAL --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20" id="booking-section">
        
        <div class="text-center mb-10">
            <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-2 block">Booking System</span>
            <h2 class="text-3xl font-black text-[#001D5E]">Cek Ketersediaan Tanggal</h2>
            <p class="text-gray-500 mt-2">Pastikan tanggal acara Anda tersedia sebelum melakukan pemesanan.</p>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 md:p-12 relative">
            
            {{-- Form Cek Tanggal --}}
            <form action="{{ route('booking.create') }}" method="GET" id="checkDateForm" class="relative z-10">
                
                @if(session('error'))
                    <div class="bg-white border-2 border-red-600 text-red-700 p-4 rounded-xl mb-6 text-center font-bold text-sm shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-8 text-center">
                    <label class="block text-gray-700 font-bold mb-4 text-lg">Pilih Tanggal Rencana Acara</label>
                    
                    {{-- Input Flatpickr (Diberi bg-white dan z-index tinggi agar tidak tertutup) --}}
                    <div class="relative max-w-sm mx-auto z-20">
                        <input type="text" id="datepicker" name="date" 
                            class="w-full text-center text-xl font-bold p-5 rounded-2xl border-2 border-gray-200 focus:border-[#001D5E] focus:ring-0 text-[#001D5E] shadow-sm hover:border-gray-300 transition-colors bg-white relative z-30" 
                            placeholder="Klik untuk pilih tanggal..." 
                            required 
                            readonly="readonly"> {{-- Readonly agar tidak diketik manual, tapi diklik --}}
                        
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 z-40">
                            <i data-lucide="calendar-days" class="w-6 h-6"></i>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-4 flex justify-center items-center gap-2">
                        <span class="w-3 h-3 bg-red-600 rounded-full"></span> Tanggal abu-abu sudah penuh (Booked).
                    </p>
                </div>

                <div class="flex justify-center relative z-10">
                    @auth
                        {{-- Saat sudah login: submit untuk lanjut isi form booking --}}
                        <button
                            type="submit"
                            class="w-full md:w-auto px-10 py-4 bg-[#001D5E] text-white font-bold rounded-xl hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 group">
                            Lanjut Isi Formulir Booking
                            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    @else
                        {{-- Saat belum login: bukan <a>, tapi button yang akan redirect via JS membawa tanggal --}}
                        <button
                            type="button"
                            id="loginBookingBtn"
                            class="w-full md:w-auto px-10 py-4 bg-gray-200 text-gray-500 font-bold rounded-xl hover:bg-gray-300 transition-all flex items-center justify-center gap-2 group">
                            Masuk untuk mengisi detail booking
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </button>

                        {{-- optional: pesan error kecil di bawah tombol --}}
                        <p id="bookingDateError" class="hidden mt-3 text-sm text-red-600 text-center">
                            Silakan pilih tanggal acara terlebih dahulu.
                        </p>
                    @endauth
                </div>

            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT FLATPICKR --}}
{{-- PENTING: Ditaruh di sini (bukan di @push) agar pasti tereksekusi --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1) Data booked dates dari controller (aman)
    const bookedDates = @json($bookedDates ?? []);
    console.log("Booked Dates:", bookedDates);

    // 2) Ambil date dari query string (misal setelah login balik ke /services?date=2026-02-06)
    const urlParams = new URLSearchParams(window.location.search);
    const prefilledDate = urlParams.get('date'); // format Y-m-d

    // 3) Init flatpickr
    const fp = flatpickr("#datepicker", {
        minDate: "today",
        dateFormat: "Y-m-d",   // value input (untuk backend)
        altInput: true,
        altFormat: "j F Y",    // tampilan user
        disable: bookedDates,
        allowInput: true,
        clickOpens: true,
        locale: { firstDayOfWeek: 1 },
        defaultDate: prefilledDate || null, // auto isi jika ada date dari query
        onReady: function(selectedDates, dateStr, instance) {
            // Kalau date dari query ternyata termasuk bookedDates, kasih sinyal (opsional)
            if (prefilledDate && bookedDates.includes(prefilledDate)) {
                console.warn("Prefilled date is booked:", prefilledDate);
            }
        }
    });

    // 4) Tombol login (guest) -> redirect bawa date
    const loginBtn = document.getElementById('loginBookingBtn');
    if (loginBtn) {
        loginBtn.addEventListener('click', function () {
            const dateValue = document.getElementById('datepicker')?.value?.trim(); // Y-m-d
            const errorEl = document.getElementById('bookingDateError');

            if (!dateValue) {
                if (errorEl) errorEl.classList.remove('hidden');
                return;
            }
            if (errorEl) errorEl.classList.add('hidden');

            // Redirect kembali ke halaman ini setelah login
            const redirectPath = '/booking/form'; // misal /services
            const params = new URLSearchParams({
                redirect: redirectPath,
                date: dateValue
            });

            window.location.href = `/login?${params.toString()}`;
        });
    }
});
</script>


@endsection