@extends('layouts.admin')

@section('title', 'Kelola Booking Masuk')

@section('content')
<div class="space-y-6">

    {{-- 1. HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#001D5E]">Kelola Booking Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pantau permintaan booking event dari klien, verifikasi jadwal, dan tanggapi pesanan.
            </p>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-white border-2 border-emerald-600 text-emerald-950 text-sm font-bold flex items-center gap-2 shadow-sm">
            <span class="p-1 rounded bg-emerald-600 text-white flex items-center justify-center">
                <i data-lucide="check" class="w-4 h-4"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 2. STATS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Booking</p>
            <p class="text-3xl font-black text-[#001D5E] mt-1">{{ $totalBookings }} <span class="text-xs font-semibold text-gray-400">Permintaan</span></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menunggu Konfirmasi</p>
            <p class="text-3xl font-black text-[#F7941D] mt-1">{{ $pendingBookings }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Disetujui</p>
            <p class="text-3xl font-black text-emerald-600 mt-1">{{ $approvedBookings }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-2 border-slate-300">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ditolak</p>
            <p class="text-3xl font-black text-red-600 mt-1">{{ $rejectedBookings }}</p>
        </div>
    </div>

    {{-- 3. FILTER BAR (Sebaris di Layar Besar, 2 Baris Simetris Seimbang di Layar Sedang/Tablet) --}}
    <div class="bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm w-full">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-3.5 items-end w-full">
            {{-- Pencarian: Full-width di layar sedang, 5 kolom di layar besar --}}
            <div class="sm:col-span-2 xl:col-span-5 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari Klien / Kontak / Event</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan nama klien, email, WhatsApp, atau jenis event..." 
                           style="min-height: 44px;"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                </div>
            </div>

            {{-- Status: 1 kolom (kiri 50%) di layar sedang, 3 kolom di layar besar --}}
            <div class="sm:col-span-1 xl:col-span-3 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status Pesanan</label>
                <select name="status" 
                        style="min-height: 44px;"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-300 focus:border-[#001D5E] focus:ring-0 text-xs sm:text-sm font-medium">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Tombol Aksi Filter: 1 kolom (kanan 50% mentok kanan) di layar sedang, 4 kolom di layar besar --}}
            <div class="sm:col-span-1 xl:col-span-4 w-full flex items-center gap-2">
                <button type="submit" 
                        style="min-height: 44px;"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm transition-all border-2 border-[#001138] cursor-pointer whitespace-nowrap shadow-xs">
                    <i data-lucide="filter" class="w-4 h-4 text-[#F7941D]"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.bookings.index') }}" 
                   style="min-height: 44px;"
                   class="inline-flex items-center justify-center py-2.5 px-4 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer whitespace-nowrap shadow-xs">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- 4. TABEL DATA --}}
    <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden">
        @if($bookings->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 border-2 border-slate-300 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <i data-lucide="calendar-x" class="w-8 h-8"></i>
                </div>
                <p class="font-bold text-slate-700 text-base">Tidak Ada Data Booking</p>
                <p class="text-xs text-slate-500 mt-1">Belum ada data pemesanan yang masuk atau tidak cocok dengan filter pencarian.</p>
            </div>
        @else
            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-slate-200">
                @foreach($bookings as $booking)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-bold text-[#001D5E] text-base">{{ $booking->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->email }} • {{ $booking->whatsapp_number }}</p>
                            </div>
                            @if($booking->status == 'pending')
                                <span class="bg-[#F7941D] text-white px-2.5 py-1 rounded-md text-xs font-bold">Menunggu</span>
                            @elseif($booking->status == 'approved')
                                <span class="bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-bold">Disetujui</span>
                            @else
                                <span class="bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-bold">Ditolak</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <div>
                                <span class="text-gray-400 block text-[10px] uppercase font-bold">Jenis Acara</span>
                                <span class="font-semibold text-slate-800">{{ $booking->event_type }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[10px] uppercase font-bold">Tanggal Rencana</span>
                                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</span>
                            </div>
                        </div>

                        <button onclick="openModal(this)" 
                            data-id="{{ $booking->id }}"
                            data-name="{{ $booking->name }}"
                            data-wa="{{ $booking->whatsapp_number }}"
                            data-email="{{ $booking->email }}"
                            data-event="{{ $booking->event_type }}"
                            data-date="{{ $booking->event_date }}"
                            data-guest="{{ $booking->guest_estimate }}"
                            data-budget="{{ $booking->budget_estimate }}"
                            data-notes="{{ $booking->notes }}"
                            data-admin-notes="{{ $booking->admin_notes }}"
                            data-status="{{ $booking->status }}"
                            class="w-full bg-[#001D5E] hover:bg-blue-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border-2 border-[#001138]">
                            <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail & Aksi
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs font-black border-b-2 border-slate-200">
                        <tr>
                            <th class="p-4">Klien</th>
                            <th class="p-4">Event</th>
                            <th class="p-4">Tanggal Rencana</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50 transition-colors">
                            {{-- Kolom Klien --}}
                            <td class="p-4">
                                <p class="font-bold text-[#001D5E] text-sm">{{ $booking->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $booking->email }}</p>
                                <p class="text-xs text-gray-500 font-medium">{{ $booking->whatsapp_number }}</p>
                            </td>

                            {{-- Kolom Event --}}
                            <td class="p-4">
                                <span class="bg-[#001D5E] text-white py-1 px-2.5 rounded-md text-xs font-bold inline-block">
                                    {{ $booking->event_type }}
                                </span>
                            </td>

                            {{-- Kolom Tanggal --}}
                            <td class="p-4 text-sm font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                            </td>

                            {{-- Kolom Status --}}
                            <td class="p-4">
                                @if($booking->status == 'pending')
                                    <span class="bg-[#F7941D] text-white px-2.5 py-1 rounded-md text-xs font-bold inline-block">Menunggu</span>
                                @elseif($booking->status == 'approved')
                                    <span class="bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-bold inline-block">Disetujui</span>
                                @else
                                    <span class="bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-bold inline-block">Ditolak</span>
                                @endif
                            </td>

                            {{-- Kolom Aksi (Tombol Detail) --}}
                            <td class="p-4 text-center">
                                <button onclick="openModal(this)" 
                                    data-id="{{ $booking->id }}"
                                    data-name="{{ $booking->name }}"
                                    data-wa="{{ $booking->whatsapp_number }}"
                                    data-email="{{ $booking->email }}"
                                    data-event="{{ $booking->event_type }}"
                                    data-date="{{ $booking->event_date }}"
                                    data-guest="{{ $booking->guest_estimate }}"
                                    data-budget="{{ $booking->budget_estimate }}"
                                    data-notes="{{ $booking->notes }}"
                                    data-admin-notes="{{ $booking->admin_notes }}"
                                    data-status="{{ $booking->status }}"
                                    class="bg-[#001D5E] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-900 transition flex items-center gap-1.5 mx-auto border-2 border-[#001138]">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Detail & Aksi
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL POP-UP (Hidden by default) --}}
{{-- ========================================== --}}
<div id="bookingModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    {{-- Overlay Gelap (Backdrop) --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                
                {{-- Header Modal --}}
                <div class="bg-[#001D5E] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white" id="modal-title">Detail Pesanan Booking</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="px-6 py-6">
                    
                    {{-- Grid Informasi --}}
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Nama Klien</label>
                            <p id="modal_name" class="font-bold text-gray-800 text-lg">-</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Kontak</label>
                            <p id="modal_wa" class="text-sm text-gray-600 font-medium">-</p>
                            <p id="modal_email" class="text-sm text-gray-500">-</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Jenis Acara</label>
                            <span id="modal_event" class="bg-[#001D5E] text-white px-2.5 py-1 rounded-md text-xs font-bold inline-block">-</span>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Tanggal Acara</label>
                            <p id="modal_date" class="font-bold text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Estimasi Tamu</label>
                            <p id="modal_guest" class="text-gray-700">- Orang</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Estimasi Budget</label>
                            <p id="modal_budget" class="text-gray-700">-</p>
                        </div>
                    </div>

                    {{-- Full Notes (Ini yang dulu kepotong) --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                        <label class="text-xs text-gray-400 uppercase font-bold block mb-2 flex items-center gap-1">
                            <i data-lucide="message-square" class="w-3 h-3"></i> Catatan Klien
                        </label>
                        <p id="modal_notes" class="text-gray-600 italic text-sm leading-relaxed">
                            "Tidak ada catatan."
                        </p>
                    </div>

                    {{-- FORM PROSES (Approve/Reject + Balasan) --}}
                    <form id="updateForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        
                        {{-- Input Balasan Admin --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Balasan / Catatan Admin (Opsional)</label>
                            <textarea name="admin_notes" id="modal_admin_notes" rows="3" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#001D5E] focus:ring focus:ring-blue-200 transition"
                                placeholder="Tulis alasan penolakan atau info tambahan untuk klien..."></textarea>
                        </div>

                        {{-- Konversi ke Jadwal Event Operasional --}}
                        <div class="mb-4 pt-3 border-t border-gray-100">
                            <a id="convertToScheduleBtn" href="#"
                               class="w-full px-4 py-3 bg-[#F7941D] hover:bg-orange-600 text-white font-black rounded-xl transition shadow-md flex justify-center items-center gap-2 text-sm">
                                <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                                <span>Jadikan Jadwal Event Operasional</span>
                            </a>
                            <p class="text-[11px] text-slate-500 text-center mt-1.5">
                                Konversi ke Jadwal Event untuk menugaskan kru staf lapangan & mencatat biaya produksi/keuangan.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-2">
                            {{-- Tombol Reject --}}
                            <button type="submit" name="status" value="rejected" 
                                class="flex-1 px-4 py-3 bg-white border-2 border-red-600 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition flex justify-center items-center gap-2">
                                <i data-lucide="x-circle" class="w-5 h-5"></i> Tolak Booking
                            </button>
                            
                            {{-- Tombol Approve --}}
                            <button type="submit" name="status" value="approved" 
                                class="flex-1 px-4 py-3 bg-[#001D5E] text-white font-bold rounded-xl hover:bg-blue-900 transition shadow-lg flex justify-center items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i> Setujui (Approve)
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT UNTUK MODAL --}}
<script>
    function openModal(button) {
        // 1. Ambil data dari tombol yang diklik
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const wa = button.getAttribute('data-wa');
        const email = button.getAttribute('data-email');
        const event = button.getAttribute('data-event');
        const date = button.getAttribute('data-date');
        const guest = button.getAttribute('data-guest');
        const budget = button.getAttribute('data-budget');
        const notes = button.getAttribute('data-notes');
        const adminNotes = button.getAttribute('data-admin-notes');
        
        // 2. Isi data ke dalam Modal HTML
        document.getElementById('modal_name').textContent = name;
        document.getElementById('modal_wa').textContent = wa;
        document.getElementById('modal_email').textContent = email;
        document.getElementById('modal_event').textContent = event;
        document.getElementById('modal_date').textContent = date;
        document.getElementById('modal_guest').textContent = guest;
        document.getElementById('modal_budget').textContent = budget;
        document.getElementById('modal_notes').textContent = notes ? notes : "Tidak ada catatan.";
        document.getElementById('modal_admin_notes').value = adminNotes ? adminNotes : "";

        // 3. Update URL Form Action & Convert Link
        const form = document.getElementById('updateForm');
        form.action = "/admin/bookings/" + id; 
        document.getElementById('convertToScheduleBtn').href = "/admin/scheduled-events/convert/" + id;

        // 4. Tampilkan Modal
        document.getElementById('bookingModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }
</script>

@endsection