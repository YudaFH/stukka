@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6">Edit Project: {{ $event->title }}</h2>

    <div class="bg-white p-6 rounded-xl shadow">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- PENTING UNTUK UPDATE --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block mb-2 font-bold">Judul Project</label>
                    <input type="text" name="title" value="{{ $event->title }}" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block mb-2 font-bold">Lokasi</label>
                    <input type="text" name="location" value="{{ $event->location }}" class="w-full border rounded p-2" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div x-data="eventDatePicker('{{ old('date', $event->date) }}')" class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block font-bold">Tanggal Pelaksanaan *</label>
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
                               value="{{ old('date', $event->date) }}"
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
                        <span class="text-slate-500" x-text="isRange ? '📅 Mode Rentang: Klik 2 tanggal di kalender' : '📅 Klik kalender untuk memilih tanggal'"></span>
                        <button type="button" x-show="dateValue" @click="clearDate()" class="text-red-500 hover:underline cursor-pointer">Reset</button>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-bold">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="upcoming" {{ $event->status == 'upcoming' ? 'selected' : '' }}>Persiapan</option>
                        <option value="ongoing" {{ $event->status == 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="finished" {{ $event->status == 'finished' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="w-full border rounded p-2">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                <img src="{{ $event->image }}" class="w-32 mt-2 rounded">
            </div>

            <div class="mb-4 p-4 bg-white border-2 border-slate-300 rounded-xl flex items-center gap-3 shadow-sm">
                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    id="feat"
                    class="w-5 h-5 rounded text-[#001D5E]"
                    {{ $event->is_featured ? 'checked' : '' }}
                >
                <label for="feat" class="font-bold text-gray-700">Tampilkan di Halaman Depan (Featured)?</label>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">Update Project</button>
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
