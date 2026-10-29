@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">

            {{-- Header: framing + reassurance --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-[#001D5E]">Ajukan Permintaan Event</h2>
                <p class="mt-2 text-gray-600">
                    Isi detail berikut agar tim kami memahami kebutuhan acara Anda.
                    <span class="font-semibold">Estimasi 2–3 menit.</span>
                </p>
            </div>

            {{-- Ringkasan tanggal + next step --}}
            <div class="mb-8 bg-white p-5 rounded-2xl border-2 border-slate-300 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal pilihan Anda</p>
                        <p class="text-2xl font-black text-[#F7941D]">
                            {{ date('d F Y', strtotime($date)) }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600">
                            Setelah dikirim, tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi.
                            <span class="font-semibold">Belum ada biaya pada tahap ini.</span>
                        </p>
                    </div>

                    {{-- Opsional: tombol ganti tanggal (kalau kamu punya route balik pilih tanggal) --}}
                    <a href="{{ url('/services') }}"
                       class="inline-flex justify-center items-center px-5 py-3 rounded-xl bg-white border-2 border-slate-300 text-[#001D5E] font-bold hover:bg-slate-100 transition">
                        Ganti Tanggal
                    </a>
                </div>

                <input type="hidden" name="event_date" form="bookingForm" value="{{ $date }}">
            </div>

            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Data Kontak --}}
                <div>
                    <p class="text-sm font-extrabold text-gray-700 mb-3">Data Kontak</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                                value="{{ Auth::user()->name }}"
                                required>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-2">No. WhatsApp</label>
                            <input
                                type="tel"
                                name="whatsapp_number"
                                class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                                placeholder="Contoh: 0812xxxxxxx"
                                inputmode="numeric"
                                pattern="^[0-9]{9,15}$"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Gunakan angka saja (9–15 digit).</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block font-bold text-gray-700 mb-2">Alamat Email</label>
                        <input
                            type="email"
                            name="email"
                            class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                            value="{{ Auth::user()->email }}"
                            required>
                    </div>
                </div>

                {{-- Detail Event --}}
                <div>
                    <p class="text-sm font-extrabold text-gray-700 mb-3">Detail Event</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-gray-700 font-bold mb-2">Jenis Layanan / Event</label>
                            <div class="relative">
                                <select
                                    name="event_type"
                                    class="no-native-arrow w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E] block p-4 pr-12"
                                    required>
                                    <option value="" disabled selected>-- Pilih Layanan Stukka --</option>
                                    <option value="Brand/Event Management">Brand & Event Management</option>
                                    <option value="Visual & Creative">Visual & Creative Management</option>
                                    <option value="App Digital Management">Application Digital Management</option>
                                    <option value="Production Management">Production Management</option>
                                    <option value="Sponsorship Management">Sponsorship Management</option>
                                </select>

                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block font-bold text-gray-700 mb-2">Estimasi Tamu</label>
                            <input
                                type="text"
                                name="guest_estimate"
                                class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                                placeholder="Contoh: 500 pax">
                            <p class="mt-1 text-xs text-gray-500">Boleh kisaran (mis. 200–300).</p>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block font-bold text-gray-700 mb-2">Estimasi Budget</label>
                            <input
                                type="text"
                                name="budget_estimate"
                                class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                                placeholder="Contoh: 100 juta">
                            <p class="mt-1 text-xs text-gray-500">Boleh kisaran. Ini membantu kami menyesuaikan solusi.</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block font-bold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea
                            name="notes"
                            rows="4"
                            class="w-full border-gray-300 rounded-xl focus:ring-[#001D5E] focus:border-[#001D5E]"
                            placeholder="Contoh: konsep acara, lokasi, jam, kebutuhan panggung/sound, dll."></textarea>
                    </div>
                </div>

                {{-- CTA + reassurance --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full bg-[#001D5E] text-white font-bold py-4 rounded-xl hover:bg-blue-900 transition-all shadow-lg flex items-center justify-center gap-2">
                        Ajukan Permintaan Event
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </button>

                    <p class="mt-3 text-center text-sm text-gray-600">
                        Dengan mengirim formulir ini, Anda setuju untuk dihubungi melalui WhatsApp/email untuk konfirmasi.
                        <span class="font-semibold">Tidak ada biaya di tahap ini.</span>
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
