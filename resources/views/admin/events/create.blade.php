@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6 gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.events.index') }}" 
               class="p-2 -ml-1 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-[#001D5E] hover:border-slate-400 transition-colors shadow-xs shrink-0" 
               title="Kembali ke Portofolio">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <h2 class="text-2xl sm:text-3xl font-black text-[#001D5E] truncate">Tambah Project Baru</h2>
        </div>
        <a href="{{ route('admin.events.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all whitespace-nowrap shrink-0 shadow-xs">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-slate-600"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Baris 1: Judul & Klien --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block mb-2 font-bold text-gray-700">Nama Event / Project</label>
                    <input type="text" name="title" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-[#001D5E] focus:ring focus:ring-blue-200 transition" placeholder="Contoh: Konser Kemerdekaan" required>
                </div>
                <div>
                    <label class="block mb-2 font-bold text-gray-700">Kolaborasi Dengan (Klien)</label>
                    <input type="text" name="client_name" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-[#001D5E] focus:ring focus:ring-blue-200 transition" placeholder="Contoh: PT. Pertamina, BUMN, dll" required>
                </div>
            </div>

            {{-- Baris 2: Lokasi, Tanggal, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block mb-2 font-bold text-gray-700">Lokasi Event</label>
                    <input type="text" name="location" class="w-full border-gray-300 rounded-xl shadow-sm" required>
                </div>
                <div x-data="eventDatePicker('{{ old('date', '') }}')" class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block font-bold text-gray-700">Tanggal Pelaksanaan *</label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-500 hover:text-[#001D5E]">
                            <input type="checkbox" x-model="isRange" @change="toggleMode()" class="rounded border-gray-300 text-[#001D5E] focus:ring-0 w-3.5 h-3.5">
                            <span>Rentang Hari?</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="text"
                               name="date"
                               x-ref="dateInput"
                               x-model="dateValue"
                               @click="openCalendar()"
                               placeholder="Pilih dari kalender (contoh: 10 Agustus 2024)"
                               required
                               class="w-full pr-11 border-gray-300 rounded-xl shadow-sm focus:border-[#001D5E] focus:ring focus:ring-blue-200 text-sm font-medium cursor-pointer">
                        <button type="button"
                                @click.stop="openCalendar()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-[#001D5E] transition-colors cursor-pointer"
                                title="Buka Kalender">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-1 text-xs">
                        <span class="text-slate-500" x-text="isRange ? '📅 Mode Rentang: Klik 2 tanggal (mulai & selesai) di kalender' : '📅 Klik kalender untuk memilih tanggal'"></span>
                        <button type="button" x-show="dateValue" @click="clearDate()" class="text-red-500 hover:underline cursor-pointer">Reset</button>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-bold text-gray-700">Status Project</label>
                    <select name="status" class="w-full border-gray-300 rounded-xl shadow-sm">
                        <option value="upcoming">Persiapan (Upcoming)</option>
                        <option value="ongoing">Sedang Berjalan</option>
                        <option value="finished">Selesai</option>
                    </select>
                </div>
            </div>

            {{-- Baris 3: Upload Gambar --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block mb-2 font-bold text-gray-700">Cover Utama (Poster/Banner)</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#001D5E] file:text-white hover:file:bg-blue-900 transition-colors" required>
                    <p class="text-xs text-gray-400 mt-1">Gambar yang muncul di halaman depan.</p>
                </div>
            <div class="mb-8">
                <label class="block mb-2 font-bold text-gray-700">Foto Dokumentasi Lapangan (Maks 3)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- Slot 1 --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition">
                        <i data-lucide="image" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <span class="text-xs font-bold text-gray-500 block mb-2">Foto 1</span>
                        <input type="file" name="doc1" class="w-full text-xs text-gray-500">
                    </div>

                    {{-- Slot 2 --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition">
                        <i data-lucide="image" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <span class="text-xs font-bold text-gray-500 block mb-2">Foto 2</span>
                        <input type="file" name="doc2" class="w-full text-xs text-gray-500">
                    </div>

                    {{-- Slot 3 --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition">
                        <i data-lucide="image" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <span class="text-xs font-bold text-gray-500 block mb-2">Foto 3</span>
                        <input type="file" name="doc3" class="w-full text-xs text-gray-500">
                    </div>

                </div>
            </div>
            </div>

            <div class="mb-6 p-4 bg-white border-2 border-slate-300 rounded-xl flex items-center gap-3 shadow-sm">
                <input type="checkbox" name="is_featured" value="1" id="feat" class="w-5 h-5 text-[#001D5E] rounded">
                <label for="feat" class="font-bold text-gray-700">Tampilkan di Halaman Depan (Featured)?</label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#001D5E] hover:bg-blue-900 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition-transform active:scale-95">
                    Simpan Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    function registerEventDatePicker() {
        if (typeof Alpine === 'undefined' || !Alpine.data) return;

        Alpine.data('eventDatePicker', (initialValue = '') => ({
            dateValue: initialValue || '',
            isRange: (initialValue && typeof initialValue === 'string' && initialValue.includes('-')) ? true : false,
            fp: null,
            indoMonths: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],

            init() {
                this.$nextTick(() => {
                    this.setupFlatpickr();
                });
            },

            setupFlatpickr() {
                if (this.fp) {
                    try { this.fp.destroy(); } catch (e) {}
                    this.fp = null;
                }

                if (typeof flatpickr === 'undefined') return;

                const self = this;
                const localeConfig = (window.flatpickr && window.flatpickr.l10ns && (window.flatpickr.l10ns.id || window.flatpickr.l10ns.Indonesian)) 
                    ? (window.flatpickr.l10ns.id || window.flatpickr.l10ns.Indonesian) 
                    : 'id';

                const parsedDefault = this.parseIndoDate(this.dateValue);

                this.fp = flatpickr(this.$refs.dateInput, {
                    mode: this.isRange ? 'range' : 'single',
                    locale: localeConfig,
                    dateFormat: 'j F Y',
                    disableMobile: true,
                    allowInput: true,
                    defaultDate: parsedDefault,
                    onChange(selectedDates) {
                        if (self.isRange) {
                            if (selectedDates.length === 2) {
                                const formatted = self.formatIndoRange(selectedDates[0], selectedDates[1]);
                                self.dateValue = formatted;
                                self.$refs.dateInput.value = formatted;
                                self.fp.close();
                            } else if (selectedDates.length === 1) {
                                const formatted = self.formatIndoDate(selectedDates[0]);
                                self.dateValue = formatted;
                                self.$refs.dateInput.value = formatted;
                            }
                        } else if (selectedDates.length >= 1) {
                            const formatted = self.formatIndoDate(selectedDates[0]);
                            self.dateValue = formatted;
                            self.$refs.dateInput.value = formatted;
                            self.fp.close();
                        }
                    },
                    onClose(selectedDates) {
                        if (self.isRange && selectedDates.length === 2) {
                            const formatted = self.formatIndoRange(selectedDates[0], selectedDates[1]);
                            self.dateValue = formatted;
                            self.$refs.dateInput.value = formatted;
                        }
                    }
                });

                if (this.dateValue) {
                    this.$refs.dateInput.value = this.dateValue;
                }
            },

            toggleMode() {
                this.setupFlatpickr();
                this.openCalendar();
            },

            openCalendar() {
                if (!this.fp) {
                    this.setupFlatpickr();
                }
                if (this.fp) {
                    const parsed = this.parseIndoDate(this.dateValue);
                    if (parsed) {
                        this.fp.setDate(parsed, false);
                    }
                    this.fp.open();
                }
            },

            clearDate() {
                this.dateValue = '';
                if (this.$refs.dateInput) {
                    this.$refs.dateInput.value = '';
                }
                if (this.fp) {
                    this.fp.clear();
                }
            },

            parseIndoDate(val) {
                if (!val || typeof val !== 'string') return null;
                const trimmed = val.trim();
                if (trimmed.includes('-')) {
                    const parts = trimmed.split('-').map(s => s.trim());
                    if (parts.length === 2) {
                        const endParts = parts[1].split(' ');
                        if (endParts.length === 3) {
                            const day2 = parseInt(endParts[0], 10);
                            const mIdx2 = this.indoMonths.indexOf(endParts[1]);
                            const yr2 = parseInt(endParts[2], 10);
                            if (!isNaN(day2) && mIdx2 !== -1 && !isNaN(yr2)) {
                                const d2 = new Date(yr2, mIdx2, day2);
                                const startParts = parts[0].split(' ');
                                if (startParts.length === 1) {
                                    const day1 = parseInt(startParts[0], 10);
                                    if (!isNaN(day1)) {
                                        return [new Date(yr2, mIdx2, day1), d2];
                                    }
                                } else if (startParts.length === 3) {
                                    const day1 = parseInt(startParts[0], 10);
                                    const mIdx1 = this.indoMonths.indexOf(startParts[1]);
                                    const yr1 = parseInt(startParts[2], 10);
                                    if (!isNaN(day1) && mIdx1 !== -1 && !isNaN(yr1)) {
                                        return [new Date(yr1, mIdx1, day1), d2];
                                    }
                                }
                            }
                        }
                    }
                }
                const parts = trimmed.split(' ');
                if (parts.length === 3) {
                    const day = parseInt(parts[0], 10);
                    const mIdx = this.indoMonths.indexOf(parts[1]);
                    const yr = parseInt(parts[2], 10);
                    if (!isNaN(day) && mIdx !== -1 && !isNaN(yr)) {
                        return new Date(yr, mIdx, day);
                    }
                }
                return null;
            },

            formatIndoDate(d) {
                if (!d) return '';
                const date = new Date(d);
                return `${date.getDate()} ${this.indoMonths[date.getMonth()]} ${date.getFullYear()}`;
            },

            formatIndoRange(d1, d2) {
                if (!d1) return '';
                if (!d2 || d1.getTime() === d2.getTime()) return this.formatIndoDate(d1);

                const date1 = new Date(d1);
                const date2 = new Date(d2);

                if (date1.getMonth() === date2.getMonth() && date1.getFullYear() === date2.getFullYear()) {
                    return `${date1.getDate()} - ${date2.getDate()} ${this.indoMonths[date1.getMonth()]} ${date1.getFullYear()}`;
                }

                if (date1.getFullYear() === date2.getFullYear()) {
                    return `${date1.getDate()} ${this.indoMonths[date1.getMonth()]} - ${date2.getDate()} ${this.indoMonths[date2.getMonth()]} ${date1.getFullYear()}`;
                }

                return `${this.formatIndoDate(date1)} - ${this.formatIndoDate(date2)}`;
            }
        }));
    }

    if (window.Alpine) {
        registerEventDatePicker();
    } else {
        document.addEventListener('alpine:init', registerEventDatePicker);
    }
})();
</script>
@endpush
