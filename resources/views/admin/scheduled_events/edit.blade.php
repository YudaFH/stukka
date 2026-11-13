@extends('layouts.admin')

@section('title', 'Edit Jadwal Event')
@section('header_title', 'Edit Jadwal: ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.scheduled-events.show', $event->id) }}" 
           class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#001D5E] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Detail Event</span>
        </a>
    </div>

    <form action="{{ route('admin.scheduled-events.update', $event->id) }}" method="POST" class="space-y-6" id="eventEditForm">
        @csrf
        @method('PUT')

        {{-- 1. INFORMASI UTAMA EVENT --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl border-2 border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-200">
                <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-[#001D5E]">Informasi Utama Event</h2>
                    <p class="text-xs text-slate-500">Judul, jenis acara, waktu, dan lokasi pelaksanaan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Judul Event --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama / Judul Event <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required
                           value="{{ old('title', $event->title) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                    @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tipe Acara --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tipe Event <span class="text-rose-500">*</span></label>
                    <select name="event_type" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                        @php $currentType = old('event_type', $event->event_type); @endphp
                        <option value="Wedding" {{ $currentType === 'Wedding' ? 'selected' : '' }}>Wedding / Pernikahan</option>
                        <option value="Corporate" {{ $currentType === 'Corporate' ? 'selected' : '' }}>Corporate / Perusahaan</option>
                        <option value="Concert" {{ $currentType === 'Concert' ? 'selected' : '' }}>Konser Musik / Musik Show</option>
                        <option value="Festival" {{ $currentType === 'Festival' ? 'selected' : '' }}>Festival / Pameran</option>
                        <option value="Gathering" {{ $currentType === 'Gathering' ? 'selected' : '' }}>Gathering / Reuni</option>
                        <option value="Birthday" {{ $currentType === 'Birthday' ? 'selected' : '' }}>Ulang Tahun / Party</option>
                        <option value="Other" {{ $currentType === 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Status Event --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Pelaksanaan <span class="text-rose-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                        @php $currStatus = old('status', $event->status); @endphp
                        <option value="scheduled" {{ $currStatus === 'scheduled' ? 'selected' : '' }}>Scheduled (Terjadwal)</option>
                        <option value="ongoing" {{ $currStatus === 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berjalan)</option>
                        <option value="completed" {{ $currStatus === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="cancelled" {{ $currStatus === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" required
                           value="{{ old('start_date', $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($event->start_time, 0, 5)) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($event->end_time, 0, 5)) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                {{-- Lokasi & Venue --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kota / Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" required
                           value="{{ old('location', $event->location) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Gedung / Detail Venue</label>
                    <input type="text" name="venue_detail"
                           value="{{ old('venue_detail', $event->venue_detail) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>
            </div>
        </div>

        {{-- 2. INFORMASI KLIEN & KONTRAK --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl border-2 border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-200">
                <div class="w-10 h-10 rounded-xl bg-[#F7941D] text-white shadow-sm flex items-center justify-center">
                    <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-[#001D5E]">Klien & Nilai Kontrak</h2>
                    <p class="text-xs text-slate-500">Nilai project yang disepakati dengan klien.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Klien / Instansi <span class="text-rose-500">*</span></label>
                    <input type="text" name="client_name" required
                           value="{{ old('client_name', $event->client_name) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nilai Kontrak Proyek <span class="text-rose-500">*</span></label>
                    <div x-data="{
                        raw: '{{ old('contract_amount', (int)$event->contract_amount) }}',
                        format(val) {
                            if (!val || val == 0) return '';
                            let num = val.toString().replace(/[^0-9]/g, '');
                            return num ? new Intl.NumberFormat('id-ID').format(num) : '';
                        },
                        update(e) {
                            let clean = e.target.value.replace(/[^0-9]/g, '');
                            this.raw = clean;
                            e.target.value = clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
                        }
                    }">
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-xs font-black text-slate-500 select-none pointer-events-none">Rp</span>
                            <input type="text"
                                   inputmode="numeric"
                                   required
                                   :value="format(raw)"
                                   @input="update($event)"
                                   placeholder="0"
                                   class="w-full pl-11 pr-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E] font-bold text-[#001D5E]">
                            <input type="hidden" name="contract_amount" :value="raw">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">No. WhatsApp Klien</label>
                    <input type="tel" name="client_phone"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="{{ old('client_phone', $event->client_phone) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Email Klien</label>
                    <input type="email" name="client_email"
                           value="{{ old('client_email', $event->client_email) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Catatan Khusus / Rundown</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-[#001D5E]">{{ old('notes', $event->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- 3. PENUGASAN KRU KARYAWAN & EKSTERNAL --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl border-2 border-slate-300 shadow-sm space-y-6" x-data="crewSelector()">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#001D5E] text-white shadow-sm flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-5 h-5 text-[#F7941D]"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-[#001D5E]">Penugasan Kru Lapangan</h2>
                        <p class="text-xs text-slate-500">Tentukan karyawan kantor Stukka maupun kru luar/freelance yang bertugas di event ini.</p>
                    </div>
                </div>
                
                {{-- Tombol Tambah Kru (Internal & Eksternal) --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="addCrew('internal')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white border-2 border-[#001138] text-xs font-bold transition-colors shadow-sm cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4 text-[#F7941D]"></i>
                        <span>Karyawan Stukka</span>
                    </button>
                    <button type="button" @click="addCrew('external')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white border-2 border-[#D67B0F] text-xs font-bold transition-colors shadow-sm cursor-pointer">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Kru Luar / Freelance</span>
                    </button>
                </div>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in crewList" :key="index">
                    <div class="p-3.5 bg-slate-50 rounded-2xl border-2 border-slate-200 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg text-white flex items-center justify-center font-black text-xs shrink-0"
                                     :class="item.crew_type === 'internal' ? 'bg-[#001D5E]' : 'bg-[#F7941D]'"
                                     x-text="index + 1"></div>
                                
                                {{-- Switch Tipe Kru --}}
                                <div class="inline-flex p-0.5 rounded-lg bg-slate-200 text-[10px] font-bold">
                                    <button type="button" 
                                            @click="item.crew_type = 'internal'"
                                            :class="item.crew_type === 'internal' ? 'bg-[#001D5E] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                            class="px-2 py-0.5 rounded-md transition-all cursor-pointer">
                                        Karyawan Stukka
                                    </button>
                                    <button type="button" 
                                            @click="item.crew_type = 'external'"
                                            :class="item.crew_type === 'external' ? 'bg-[#F7941D] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                            class="px-2 py-0.5 rounded-md transition-all cursor-pointer">
                                        Kru Luar / Freelance
                                    </button>
                                </div>
                                <input type="hidden" :name="'crew[' + index + '][crew_type]'" :value="item.crew_type">
                            </div>

                            <div class="flex items-center gap-1.5">
                                {{-- Tombol Duplikat (Solid Navy) --}}
                                <button type="button" @click="duplicateCrew(index)" 
                                        class="px-2.5 py-1.5 bg-[#001D5E] hover:bg-blue-900 text-white rounded-xl border-2 border-[#001138] shadow-xs transition-all cursor-pointer flex items-center gap-1.5 text-xs font-bold"
                                        title="Duplikat baris kru ini">
                                    <i data-lucide="copy" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                                    <span>Duplikat</span>
                                </button>

                                {{-- Tombol Hapus (Solid Merah) --}}
                                <button type="button" @click="askRemoveCrew(index)" 
                                        class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl border-2 border-rose-700 shadow-xs transition-all cursor-pointer flex items-center gap-1.5 text-xs font-bold"
                                        title="Hapus baris kru ini">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5 text-white"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
                            {{-- Field Identitas Kru --}}
                            <div class="lg:col-span-7">
                                {{-- Jika Karyawan Stukka --}}
                                <div x-show="item.crew_type === 'internal'">
                                    <select :name="'crew[' + index + '][user_id]'" x-model="item.user_id"
                                            :required="item.crew_type === 'internal'"
                                            class="w-full px-3 py-2 text-xs bg-white border-2 border-slate-300 rounded-lg focus:outline-none focus:border-[#001D5E]">
                                        <option value="">-- Pilih Karyawan Stukka --</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->position ?? 'Staff' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jika Kru Luar / Freelance --}}
                                <div x-show="item.crew_type === 'external'" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" :name="'crew[' + index + '][external_name]'" x-model="item.external_name"
                                           :required="item.crew_type === 'external'"
                                           placeholder="Nama Kru Luar / Vendor *"
                                           class="w-full px-3 py-2 text-xs bg-white border-2 border-amber-400 rounded-lg focus:outline-none focus:border-[#F7941D]">
                                    <input type="tel" :name="'crew[' + index + '][external_phone]'" x-model="item.external_phone"
                                           inputmode="numeric"
                                           @input="item.external_phone = $event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                                           placeholder="No. WhatsApp / HP (opsional)"
                                           class="w-full px-3 py-2 text-xs bg-white border-2 border-slate-300 rounded-lg focus:outline-none focus:border-[#F7941D]">
                                </div>
                            </div>

                            {{-- Field Peran / Role di Event --}}
                            <div class="lg:col-span-5">
                                <input type="text" :name="'crew[' + index + '][role]'" x-model="item.role" required
                                       placeholder="Peran (cth: Sound Engineer, Lighting, Runner, MC, Driver)"
                                       class="w-full px-3 py-2 text-xs bg-white border-2 border-slate-300 rounded-lg focus:outline-none focus:border-[#001D5E]">
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="crewList.length === 0" class="text-center py-6 border-2 border-dashed border-slate-300 rounded-xl text-slate-600 text-xs font-medium">
                    Belum ada kru yang ditugaskan. Klik tombol <strong>"+ Karyawan Stukka"</strong> atau <strong>"+ Kru Luar / Freelance"</strong> di atas.
                </div>
            </div>

            {{-- MODAL KONFIRMASI HAPUS KRU --}}
            <div x-show="confirmDeleteModal" 
                 x-cloak
                 x-transition.opacity.duration.200ms
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                <div @click.away="confirmDeleteModal = false"
                     class="bg-white rounded-3xl border-2 border-slate-300 max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
                    
                    <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center mx-auto shadow-md border-2 border-rose-700">
                        <i data-lucide="trash-2" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h3 class="text-lg font-black text-slate-900">Hapus Baris Kru?</h3>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            Apakah Anda yakin ingin menghapus <strong class="text-rose-600 font-bold" x-text="deleteCrewName"></strong> dari daftar penugasan event ini?
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" 
                                @click="confirmDeleteModal = false"
                                class="py-2.5 px-4 rounded-xl border-2 border-slate-300 text-slate-700 hover:bg-slate-100 font-bold text-xs transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="button" 
                                @click="confirmRemove()"
                                class="py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs border-2 border-rose-700 shadow-md transition-colors cursor-pointer">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.scheduled-events.show', $event->id) }}" 
               class="px-6 py-3 rounded-xl border-2 border-slate-400 bg-white text-slate-700 hover:bg-slate-100 font-bold text-sm transition-colors cursor-pointer">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white font-bold text-sm shadow-md transition-colors flex items-center gap-2 border-2 border-[#001138] cursor-pointer">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Perbarui Jadwal Event</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function crewSelector() {
        return {
            crewList: [
                @foreach($event->crewAssignments as $c)
                    { 
                        crew_type: '{{ $c->crew_type ?? 'internal' }}',
                        user_id: '{{ $c->user_id ?? '' }}', 
                        external_name: '{{ addslashes($c->external_name ?? '') }}', 
                        external_phone: '{{ addslashes($c->external_phone ?? '') }}', 
                        role: '{{ addslashes($c->role_in_event ?? 'Crew') }}' 
                    },
                @endforeach
            ],
            confirmDeleteModal: false,
            deleteIndex: null,
            deleteCrewName: '',

            addCrew(type = 'internal') {
                this.crewList.push({ 
                    crew_type: type,
                    user_id: '', 
                    external_name: '',
                    external_phone: '',
                    role: 'Crew' 
                });
                this.$nextTick(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            },

            duplicateCrew(index) {
                const orig = this.crewList[index];
                this.crewList.splice(index + 1, 0, {
                    crew_type: orig.crew_type,
                    user_id: orig.user_id,
                    external_name: orig.external_name ? orig.external_name + ' (Duplikat)' : '',
                    external_phone: orig.external_phone || '',
                    role: orig.role || 'Crew'
                });
                this.$nextTick(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                    if (window.notify) {
                        window.notify.success('Baris kru berhasil diduplikat.');
                    }
                });
            },

            askRemoveCrew(index) {
                this.deleteIndex = index;
                const crew = this.crewList[index];
                let name = 'Kru #' + (index + 1);
                if (crew.crew_type === 'internal' && crew.user_id) {
                    const selectEl = document.querySelector(`select[name="crew[${index}][user_id]"]`);
                    if (selectEl && selectEl.selectedOptions[0]?.text) {
                        name = selectEl.selectedOptions[0].text;
                    }
                } else if (crew.external_name) {
                    name = crew.external_name;
                }
                this.deleteCrewName = name;
                this.confirmDeleteModal = true;
                this.$nextTick(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            },

            confirmRemove() {
                if (this.deleteIndex !== null) {
                    this.crewList.splice(this.deleteIndex, 1);
                    this.deleteIndex = null;
                    this.confirmDeleteModal = false;
                    if (window.notify) {
                        window.notify.info('Baris kru berhasil dihapus.');
                    }
                }
            }
        };
    }
</script>
@endpush
@endsection
