@extends('layouts.app')

@section('content')

    {{-- Setup Data Statis --}}
    @php
        // 1. Data Layanan (Services)
        $categories = [
            [
                'name' => 'Brand/Event Management', 
                'icon' => 'calendar-check', 
                'color' => 'bg-[#F7941D]',
                'desc' => 'Pengorganisasian Acara Digital: Kami menggabungkan konsep acara tradisional dengan teknologi digital yang inovatif. Mulai dari konferensi virtual, acara live streaming, hingga pengalaman interaktif menggunakan aplikasi dan platform digital terkini.'
            ], 
            [
                'name' => 'Visual & Creative', 
                'icon' => 'palette', 
                'color' => 'bg-blue-500',
                'desc' => 'Desain Kreatif dan Visual: Tim kami menciptakan konsep visual yang menarik, menggabungkan branding perusahaan dan desain digital menawan. Termasuk produksi konten multimedia, video promosi, animasi, grafis 3D, dan audiovisual.'
            ], 
            [
                'name' => 'App Digital Management', 
                'icon' => 'smartphone', 
                'color' => 'bg-green-500',
                'desc' => 'Kami mengembangkan aplikasi khusus, platform interaktif, dan solusi teknologi yang disesuaikan dengan kebutuhan acara Anda. Mencakup aplikasi acara, registrasi online, dan alat interaktif untuk meningkatkan keterlibatan peserta.'
            ], 
            [
                'name' => 'Production Management', 
                'icon' => 'settings-2', 
                'color' => 'bg-purple-500',
                'desc' => 'Keahlian mengelola setiap aspek acara: penjadwalan, lokasi, vendor, hingga anggaran. Kami memiliki peralatan lengkap seperti LED Videotron, Kamera Broadcasting, Sound System, dan Dekorasi Acara profesional.'
            ], 
            [
                'name' => 'Sponsorship', 
                'icon' => 'handshake', 
                'color' => 'bg-red-500',
                'desc' => 'Kami siap menjadi mitra Anda dalam mencari sponsorship. Dengan jaringan yang luas, kami membantu Anda menjalin kerjasama dengan sponsor yang tepat untuk mendukung keberhasilan acara Anda.'
            ], 
        ];
        
        // 2. Data Goals
        $goals = [
            [
                'title' => 'Menciptakan Acara yang Menakjubkan',
                'description' => 'Tujuan kami adalah menghadirkan acara yang menginspirasi, membangkitkan emosi kuat, dan tak terlupakan melalui kreativitas, keunikan, serta keberanian.',
                'icon' => 'zap'
            ],
            [
                'title' => 'Menghadirkan Ketepatan dan Presisi',
                'description' => 'Menjadi teladan dalam ketepatan setiap aspek (jadwal, logistik, vendor) untuk memastikan acara berjalan lancar sesuai harapan.',
                'icon' => 'target'
            ],
            [
                'title' => 'Membangun Hubungan Kuat dengan Klien',
                'description' => 'Memahami visi klien secara mendalam dan menjadi mitra terpercaya untuk tujuan jangka panjang.',
                'icon' => 'users'
            ],
            [
                'title' => 'Inovasi dan Teknologi Terkini',
                'description' => 'Menerapkan teknologi terbaru dan elemen digital untuk meningkatkan pengalaman acara yang cerdas dan efisien.',
                'icon' => 'cpu'
            ]
        ];

        // 3. Data How We Work
        $howWeWork = [
            [
                'step' => '01',
                'title' => 'Konsultasi & Penyesuaian',
                'desc' => 'Tim kami bekerja sama dengan Anda untuk memahami tujuan, kebutuhan, dan visi acara secara mendalam.',
                'icon' => 'message-circle'
            ],
            [
                'step' => '02',
                'title' => 'Persiapan Produksi',
                'desc' => 'Kami menyiapkan segala kebutuhan produksi termasuk pengaturan teknis, tata cahaya, dan tata suara.',
                'icon' => 'clipboard-list'
            ],
            [
                'step' => '03',
                'title' => 'Acara & Interaksi',
                'desc' => 'Kami mengelola acara secara profesional, memastikan kelancaran, kualitas optimal, dan interaksi peserta yang mudah.',
                'icon' => 'play-circle'
            ],
            [
                'step' => '04',
                'title' => 'Analisis dan Evaluasi',
                'desc' => 'Setelah acara, kami menyediakan laporan analisis data dan wawasan untuk memahami dampak dan perbaikan di masa depan.',
                'icon' => 'bar-chart-2'
            ]
        ];

        // 4. Data Langkah Sponsorship
        $sponsorshipSteps = [
            [
                'title' => 'Strategi Sponsorship',
                'desc' => 'Kami akan membantu Anda merumuskan Strategi sponsorship yang efektif. Kami akan melakukan analisis mendalam tentang acara Anda, audiens target, dan kebutuhan sponsor potensial.',
                'icon' => 'chess-knight' 
            ],
            [
                'title' => 'Identifikasi Sponsor Potensial',
                'desc' => 'Tim kami akan membantu Anda dalam mengidentifikasi dan menelusuri sponsor potensial yang sesuai dengan profil acara Anda melalui jaringan luas kami.',
                'icon' => 'search' 
            ],
            [
                'title' => 'Presentasi Proposal',
                'desc' => 'Kami akan membantu Anda dalam menyusun dan menyajikan proposal sponsorship yang profesional dan komprehensif kepada calon sponsor.',
                'icon' => 'file-text' 
            ],
            [
                'title' => 'Negosiasi dan Kontrak',
                'desc' => 'Setelah mendapatkan minat, kami membantu proses negosiasi dan penyusunan kontrak yang menguntungkan kedua belah pihak.',
                'icon' => 'pen-tool' 
            ],
            [
                'title' => 'Manajemen Sponsorship',
                'desc' => 'Kami memastikan semua kewajiban dan manfaat yang dijanjikan kepada sponsor terpenuhi dengan baik untuk membangun hubungan jangka panjang.',
                'icon' => 'users' 
            ]
        ];

        $videoId = "BOG_CbEDhag";
    @endphp

    {{-- 1. HERO SECTION --}}
    <div class="relative w-full h-screen min-h-[700px] overflow-hidden flex items-center justify-center bg-black pb-32">
        <div class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none overflow-hidden">
            <iframe src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&controls=0&loop=1&playlist={{ $videoId }}&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&playsinline=1" class="absolute top-1/2 left-1/2 w-[300%] h-[300%] -translate-x-1/2 -translate-y-1/2 object-cover opacity-80" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="absolute inset-0 bg-black/60 z-10"></div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-10">
            <span class="inline-block py-1 px-4 rounded-full bg-[#F7941D]/90 text-white text-sm font-bold tracking-widest uppercase mb-6 backdrop-blur-sm shadow-lg animate-fade-in-up">STUKKA EVENTS</span>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-white mb-6 leading-tight drop-shadow-2xl tracking-tight">
                Creative work, <br class="hidden sm:inline" /> <span class="text-[#F7941D]">reimagined</span>
            </h1>
            <p class="mt-6 text-lg md:text-2xl font-light text-gray-200 max-w-3xl mx-auto drop-shadow-md leading-relaxed">
                Menciptakan pengalaman luar biasa melalui keberanian, presisi, dan inovasi.
            </p>
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                @if(Auth::check() && Auth::user()->usertype == 'admin')
                    <a href="{{ route('admin.index') }}" 
                       class="water-fill-btn group relative inline-flex items-center justify-center px-10 py-4 text-base font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-lg border-2 border-blue-400 overflow-hidden">
                        {{-- Water Waves Layer --}}
                        <span class="water-layer" aria-hidden="true">
                            <span class="water-liquid-back"></span>
                            <span class="water-liquid"></span>
                            <span class="water-bubbles">
                                <span class="bubble b1"></span>
                                <span class="bubble b2"></span>
                                <span class="bubble b3"></span>
                            </span>
                        </span>
                        <span class="relative z-10 inline-flex items-center gap-2.5">
                            <span>Masuk ke Panel Admin</span>
                            <i data-lucide="arrow-right" class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1.5"></i>
                        </span>
                    </a>
                @elseif(Auth::check() && Auth::user()->usertype == 'karyawan')
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="inline-flex items-center justify-center px-10 py-4 text-base font-bold rounded-full text-white bg-[#F7941D] hover:bg-orange-600 transition-all shadow-lg border-2 border-orange-500">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-2"></i>
                        Dashboard Karyawan & Presensi
                    </a>
                @else
                    <a href="{{ route('services') }}" 
                       class="inline-flex items-center justify-center px-10 py-4 text-base font-bold rounded-full text-[#001D5E] bg-white hover:bg-gray-100 transition-all shadow-lg">
                        Daftarkan Event <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </a>
                @endif
                <a href="{{ route('portfolio') }}" 
                   class="inline-flex items-center justify-center px-10 py-4 text-base font-bold rounded-full text-white border-2 border-white/30 bg-white/10 backdrop-blur-sm hover:bg-white hover:text-[#001D5E] transition-all">
                    Lihat Portofolio
                </a>
            </div>
        </div>
    </div>
    
    {{-- 1.5. VISUAL GRID (Floating Cards) --}}
    <div class="relative z-20 -mt-20 mb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#1f2937] rounded-3xl shadow-2xl border border-gray-700 p-2 transform hover:-translate-y-2 transition-transform duration-300 h-64 overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover rounded-2xl opacity-80 group-hover:opacity-100 transition-opacity" alt="Concert">
                    <div class="absolute bottom-4 left-4 bg-[#F7941D]/90 backdrop-blur-md px-4 py-2 rounded-lg text-white font-bold text-sm">Visual Experience</div>
                </div>
                <div class="bg-[#1f2937] rounded-3xl shadow-2xl border border-[#F7941D] p-2 transform md:-translate-y-8 hover:-translate-y-10 transition-transform duration-300 h-64 md:h-80 overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1595407753234-0882f1e77954?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover rounded-2xl opacity-90 group-hover:opacity-100 transition-opacity" alt="Wedding">
                    <div class="absolute bottom-4 left-4 bg-[#F7941D]/90 backdrop-blur-md px-4 py-2 rounded-lg text-white font-bold text-sm">Creative Events</div>
                </div>
                <div class="bg-[#1f2937] rounded-3xl shadow-2xl border border-gray-700 p-2 transform hover:-translate-y-2 transition-transform duration-300 h-64 overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover rounded-2xl opacity-80 group-hover:opacity-100 transition-opacity" alt="Corporate">
                    <div class="absolute bottom-4 left-4 bg-[#F7941D]/90 backdrop-blur-md px-4 py-2 rounded-lg text-white font-bold text-sm">Production Mgmt</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. ABOUT STUKKA --}}
    <div class="bg-white pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2 relative z-0">
                    <div class="absolute top-4 left-4 w-full h-full bg-gray-100 rounded-3xl -z-10"></div>
                    <img src="{{ asset('images/stukka.jpg') }}" 
                         alt="Pesawat Stuka Ju 87" 
                         class="relative z-10 w-full rounded-3xl shadow-xl border border-gray-100 object-cover h-[300px] md:h-[400px] hover:scale-[1.02] transition-transform duration-500">
                    <div class="absolute bottom-6 right-6 z-20 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg shadow-sm text-xs font-bold text-[#001D5E] border border-gray-200">
                        Junkers Ju 87 "Stuka"
                    </div>
                </div>
                <div class="w-full md:w-1/2 text-left">
                    <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-4 block">Our Philosophy</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-[#001D5E] mb-6 leading-tight">
                        Apa itu <span class="text-[#F7941D]">"Stukka"</span>?
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed">
                        <p class="mb-4">
                            <strong>Stukka</strong> diambil dari nama pesawat legendaris asal Jerman, <em>Junkers Ju 87</em> atau sering disebut <em>Stuka</em>. 
                        </p>
                        <p class="mb-4">
                            Pesawat ini bukan sekadar mesin, melainkan simbol kekuatan yang menciptakan dampak besar melalui tiga prinsip utama:
                        </p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-[#F7941D]"></i> 
                                <span class="font-bold text-[#001D5E]">Keberanian</span> dalam mengambil langkah beda.
                            </li>
                            <li class="flex items-center gap-2">
                                <i data-lucide="target" class="w-5 h-5 text-[#F7941D]"></i> 
                                <span class="font-bold text-[#001D5E]">Presisi</span> dalam setiap detail eksekusi.
                            </li>
                            <li class="flex items-center gap-2">
                                <i data-lucide="zap" class="w-5 h-5 text-[#F7941D]"></i> 
                                <span class="font-bold text-[#001D5E]">Inovasi</span> teknologi yang terus bergerak maju.
                            </li>
                        </ul>
                        <p>
                            Dengan filosofi tersebut, kami membentuk <strong>Stukka Digital Creative</strong> untuk menghadirkan pendekatan yang berani, 
                            kreatif, dan berfokus pada detail guna menciptakan acara yang menginspirasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. WHY US --}}
    <div class="bg-gray-50 py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">Why Choose Us</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#001D5E] mb-6">
                    Mengapa Memilih Stukka?
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Kami tidak hanya mengorganisir acara, kami menciptakan pengalaman yang mengubah cara orang berinteraksi melalui sentuhan digital dan kreatif.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group flex flex-col">
                    <div class="w-14 h-14 bg-[#001D5E] text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="zap" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#001D5E] mb-4">Komitmen & Inovasi</h3>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">
                        "Di Stukka Digital Creative, kami berkomitmen untuk terus menciptakan pengalaman acara yang mengesankan dan memukau melalui sentuhan kreatif digital. Kami percaya bahwa dengan inovasi, keahlian, dan keberanian, kami dapat mengubah cara orang mengalami dan berinteraksi dengan acara."
                    </p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-[#F7941D]/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-150"></div>
                    <div class="w-14 h-14 bg-[#F7941D] text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="award" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#001D5E] mb-4">Prestasi & Dedikasi</h3>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">
                        "Kami bangga atas prestasi dan portofolio kami yang mencerminkan dedikasi kami dalam memberikan solusi terdepan. Kolaborasi dengan klien terkemuka, pengembangan aplikasi inovatif, dan kesuksesan mengorganisir acara bersejarah adalah cerminan dari nilai-nilai kami yang kuat."
                    </p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group flex flex-col">
                    <div class="w-14 h-14 bg-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="heart" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#001D5E] mb-4">Sinergi Bersama</h3>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">
                        "Kami ingin mengucapkan terima kasih kepada klien, mitra, dan tim kami yang mendukung kami dalam perjalanan ini. Bersama-sama, kami terus berinovasi, merangkul teknologi terkini, dan menciptakan pengalaman acara yang tak terlupakan."
                    </p>
                </div>
            </div>

            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-gray-200 pt-12">
                <div class="text-center"><p class="text-3xl font-black text-[#F7941D]">10+</p><p class="text-xs text-gray-500 uppercase font-bold tracking-wider mt-1">Tahun Pengalaman</p></div>
                <div class="text-center"><p class="text-3xl font-black text-[#001D5E]">100+</p><p class="text-xs text-gray-500 uppercase font-bold tracking-wider mt-1">Project Sukses</p></div>
                <div class="text-center"><p class="text-3xl font-black text-[#F7941D]">50+</p><p class="text-xs text-gray-500 uppercase font-bold tracking-wider mt-1">Klien Bahagia</p></div>
                <div class="text-center"><p class="text-3xl font-black text-[#001D5E]">24/7</p><p class="text-xs text-gray-500 uppercase font-bold tracking-wider mt-1">Support Tim</p></div>
            </div>
        </div>
    </div>

    {{-- 4. HOW WE WORK --}}
    <div class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">Process</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#001D5E]">How We Work?</h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">4 Langkah sederhana kami dalam mewujudkan acara impian Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                <div class="hidden lg:block absolute top-12 left-0 w-full h-0.5 bg-gray-200 -z-10"></div>
                @foreach($howWeWork as $work)
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 hover:-translate-y-2 transition-transform duration-300 relative group">
                    <div class="w-24 h-24 mx-auto bg-white border-4 border-[#F7941D] rounded-full flex items-center justify-center mb-6 shadow-sm group-hover:bg-[#F7941D] transition-colors duration-300">
                        <span class="text-2xl font-black text-[#001D5E] group-hover:text-white transition-colors">{{ $work['step'] }}</span>
                    </div>
                    <div class="flex justify-center mb-4">
                        <i data-lucide="{{ $work['icon'] }}" class="w-8 h-8 text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#001D5E] text-center mb-4">{{ $work['title'] }}</h3>
                    <p class="text-gray-600 text-center text-sm leading-relaxed">{{ $work['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 5. SPONSORSHIP MANAGEMENT --}}
    <div class="py-24 bg-[#F9FAFB] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto mb-16">
                <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">Partnership Support</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#001D5E] mb-6">Sponsorship Management</h2>
                <div class="bg-white p-6 rounded-2xl border-2 border-slate-300 shadow-sm">
                    <p class="text-[#001D5E] font-medium italic text-lg leading-relaxed">
                        "Komitmen kami adalah untuk membantu Anda mencapai kesuksesan acara dengan menjalin kemitraan yang kuat dengan sponsor yang tepat dan memulai kolaborasi kami dalam mencari sponsorship yang dapat mengangkat acara Anda ke tingkat yang lebih tinggi."
                    </p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-16 items-start">
                
                {{-- PERUBAHAN DI BARIS INI: Tambah 'lg:' sebelum sticky dan top-24 --}}
                <div class="w-full lg:w-5/12 lg:sticky lg:top-24">
                    <h3 class="text-2xl font-bold text-[#001D5E] mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#001D5E] rounded-lg flex items-center justify-center">
                            <i data-lucide="info" class="w-5 h-5 text-white"></i>
                        </div>
                        Penjelasan Layanan
                    </h3>
                    <div class="prose prose-lg text-gray-600 leading-relaxed text-justify">
                        <p class="mb-4">
                            <strong>Stukka Digital Creative</strong> adalah perusahaan event organizer yang berfokus pada penggunaan teknologi digital untuk menciptakan pengalaman acara yang unik dan menarik. Kami memiliki tim yang berpengalaman dalam industri event organizer dan ahli dalam teknologi digital.
                        </p>
                        <p class="mb-4">
                            Kami siap menjadi mitra Anda dalam mencari <strong>sponsorship</strong> untuk acara Anda. Kami mengerti bahwa sponsorship adalah elemen penting dalam keberhasilan sebuah acara, dan kami memiliki pengalaman dan jaringan yang luas untuk membantu Anda menjalin kerjasama dengan sponsor yang tepat.
                        </p>
                        <p>
                            Dengan pemahaman mendalam tentang industri event dan hubungan yang kuat dengan berbagai perusahaan dan merek, kami akan bekerja sama dengan Anda untuk mencapai kesuksesan.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-[#F7941D] font-bold hover:gap-3 transition-all">
                            Konsultasikan Sponsorship <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <div class="w-full lg:w-7/12">
                    <div class="space-y-6">
                        @foreach($sponsorshipSteps as $index => $step)
                        <div class="flex gap-6 group">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-gray-200 flex items-center justify-center group-hover:bg-[#001D5E] group-hover:border-[#001D5E] transition-all duration-300 shadow-sm">
                                    <i data-lucide="{{ $step['icon'] }}" class="w-6 h-6 text-gray-400 group-hover:text-white transition-colors"></i>
                                </div>
                            </div>
                            <div class="pb-8">
                                <h4 class="text-xl font-bold text-[#001D5E] mb-2 group-hover:text-[#F7941D] transition-colors">
                                    {{ $step['title'] }}
                                </h4>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5.5 FEATURED PROJECTS SECTION --}}
    @if(isset($featuredEvents) && $featuredEvents->count())
        <div class="bg-white py-24 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                    <div class="max-w-2xl">
                        <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">Featured Projects</span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-[#001D5E]">
                            Portofolio Unggulan Kami
                        </h2>
                        <p class="mt-3 text-gray-600">
                            Beberapa project pilihan yang kami tampilkan sebagai highlight. Lihat detail untuk inspirasi event Anda.
                        </p>
                    </div>

                    <a href="{{ route('portfolio') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-gray-200 bg-white text-[#001D5E] font-bold hover:bg-gray-50 transition">
                        Lihat Semua <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredEvents as $event)
                    <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition overflow-hidden">
                        <div class="relative h-56 bg-gray-100 overflow-hidden">
                            @php
                                $cover = $event->image;
                            @endphp

                            @if($cover)
                                <img src="{{ $cover }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-black/40"></div>

                            <div class="absolute bottom-4 left-4 right-4">
                                <p class="text-white font-extrabold text-lg leading-tight line-clamp-2">
                                    {{ $event->title }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                    @if($event->status)
                                        <span class="px-3 py-1 rounded-full bg-white/15 text-white border border-white/20 backdrop-blur-sm">
                                            {{ $event->status }}
                                        </span>
                                    @endif
                                    @if($event->client_name)
                                        <span class="px-3 py-1 rounded-full bg-white/15 text-white border border-white/20 backdrop-blur-sm">
                                            {{ $event->client_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col gap-2 text-sm text-gray-600">
                                @if($event->location)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#F7941D]"></i>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                                @endif

                                @if($event->date)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-[#F7941D]"></i>
                                    <span>{{ $event->date }}</span>
                                </div>
                                @endif

                                @if($event->price)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="wallet" class="w-4 h-4 text-[#F7941D]"></i>
                                    <span>{{ $event->price }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <a href="{{ route('portfolio') }}"
                                class="inline-flex items-center gap-2 font-bold text-[#001D5E] hover:text-[#F7941D] transition">
                                    Lihat Detail <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </a>

                                <span class="text-xs text-gray-400">
                                    {{ optional($event->created_at)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- 6. TESTIMONIAL SECTION --}}
        <div class="bg-white py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto mb-16">
                    <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">What Client Says</span>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        "Kami ingin mengucapkan terima kasih kepada klien, mitra, dan tim kami yang mendukung kami dalam perjalanan ini."
                    </p>
                </div>

                @php
                    $tCount = $testimonials->count();
                @endphp

                <div class="mb-16">
                    <div id="testi-carousel" class="relative overflow-hidden">
                        <div id="testi-track" class="flex transition-transform duration-700 ease-in-out">
                            @foreach($testimonials as $testi)
                                <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition-shadow relative group hover:-translate-y-1 h-full">
                                        <div class="flex gap-1 mb-4">
                                            @for($i=0; $i<$testi->stars; $i++)
                                                <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                                            @endfor
                                        </div>
                                        <p class="text-gray-700 italic mb-6 leading-relaxed">"{{ $testi->content }}"</p>
                                        <div class="flex items-center gap-4 border-t border-gray-200 pt-4 mt-auto">
                                            <div class="w-10 h-10 bg-[#001D5E] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($testi->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#001D5E]">{{ $testi->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $testi->role ?? 'Client' }}</p>
                                            </div>
                                        </div>
                                        <i data-lucide="quote" class="absolute top-6 right-6 w-10 h-10 text-gray-200 fill-gray-200 group-hover:text-blue-100 transition-colors"></i>
                                    </div>
                                </div>
                            @endforeach

                            @if($tCount > 3)
                                @foreach($testimonials->take(3) as $testi)
                                    <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                                        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition-shadow relative group hover:-translate-y-1 h-full">
                                            <div class="flex gap-1 mb-4">
                                                @for($i=0; $i<$testi->stars; $i++)
                                                    <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                                                @endfor
                                            </div>
                                            <p class="text-gray-700 italic mb-6 leading-relaxed">"{{ $testi->content }}"</p>
                                            <div class="flex items-center gap-4 border-t border-gray-200 pt-4 mt-auto">
                                                <div class="w-10 h-10 bg-[#001D5E] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                    {{ substr($testi->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-[#001D5E]">{{ $testi->name }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $testi->role ?? 'Client' }}</p>
                                                </div>
                                            </div>
                                            <i data-lucide="quote" class="absolute top-6 right-6 w-10 h-10 text-gray-200 fill-gray-200 group-hover:text-blue-100 transition-colors"></i>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        @if($tCount > 3)
                            <div class="mt-8 flex justify-center gap-2" id="testi-dots"></div>
                        @endif
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 bg-[#F7941D] text-white px-10 py-4 rounded-full font-bold shadow-lg shadow-orange-500/30 hover:bg-orange-600 hover:scale-105 transition-all">
                        Get In Touch <i data-lucide="message-circle" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

{{-- 9. CLIENT TRUST SECTION (FINAL: JARAK RAPAT & COMPACT) --}}
    <div class="bg-[#F9FAFB] py-24 border-y border-gray-200 relative overflow-hidden">
        
        <style>
            @keyframes scrollLeft {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            @keyframes scrollRight {
                0% { transform: translateX(-50%); }
                100% { transform: translateX(0); }
            }
            
            .animate-scroll-left {
                animation: scrollLeft var(--scroll-duration, 60s) linear infinite;
            }
            .animate-scroll-right {
                animation: scrollRight var(--scroll-duration, 60s) linear infinite;
            }

            .mask-gradient-left { background: linear-gradient(to right, #F9FAFB, transparent); }
            .mask-gradient-right { background: linear-gradient(to left, #F9FAFB, transparent); }
        </style>

        <div class="absolute inset-y-0 left-0 w-32 mask-gradient-left z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-32 mask-gradient-right z-10 pointer-events-none"></div>

        <div class="relative z-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="text-center">
                <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-3 block">They Trust Us</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#001D5E]">Trusted by Great Brands</h2>
                <p class="mt-4 text-gray-500">Kolaborasi dengan klien terkemuka untuk acara bersejarah.</p>
            </div>
        </div>

        <div class="space-y-12">
            
            {{-- BARIS 1: Bergerak ke KIRI --}}
            <div class="w-full overflow-hidden">
                {{-- PERUBAHAN: gap-6 md:gap-12 (Lebih rapat lagi) --}}
                <div class="flex w-max animate-scroll-left js-scroll-container gap-6 md:gap-12 px-4">
                    {{-- SET 1 --}}
                    @foreach($clients as $client)
                        <div class="flex-shrink-0 w-32 md:w-48 flex justify-center items-center group">
                            <img src="{{ $client->logo }}" 
                                 class="h-12 md:h-16 w-auto object-contain hover:scale-110 transition-transform duration-300 cursor-pointer" 
                                 alt="{{ $client->name }}">
                        </div>
                    @endforeach
                    {{-- SET 2 (Clone) --}}
                    @foreach($clients as $client)
                        <div class="flex-shrink-0 w-32 md:w-48 flex justify-center items-center group">
                            <img src="{{ $client->logo }}" 
                                 class="h-12 md:h-16 w-auto object-contain hover:scale-110 transition-transform duration-300 cursor-pointer" 
                                 alt="{{ $client->name }}">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- BARIS 2: Bergerak ke KANAN --}}
            <div class="w-full overflow-hidden">
                {{-- PERUBAHAN: gap-6 md:gap-12 --}}
                <div class="flex w-max animate-scroll-right js-scroll-container gap-6 md:gap-12 px-4">
                    {{-- SET 1 --}}
                    @foreach($clients->reverse() as $client)
                        <div class="flex-shrink-0 w-32 md:w-48 flex justify-center items-center group">
                            <img src="{{ $client->logo }}" 
                                 class="h-12 md:h-16 w-auto object-contain hover:scale-110 transition-transform duration-300 cursor-pointer" 
                                 alt="{{ $client->name }}">
                        </div>
                    @endforeach
                    {{-- SET 2 (Clone) --}}
                    @foreach($clients->reverse() as $client)
                        <div class="flex-shrink-0 w-32 md:w-48 flex justify-center items-center group">
                            <img src="{{ $client->logo }}" 
                                 class="h-12 md:h-16 w-auto object-contain hover:scale-110 transition-transform duration-300 cursor-pointer" 
                                 alt="{{ $client->name }}">
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const speed = 50; 
                const containers = document.querySelectorAll('.js-scroll-container');
                containers.forEach(container => {
                    const totalWidth = container.scrollWidth;
                    const distance = totalWidth / 2; 
                    let duration = distance / speed;
                    if (!duration || duration < 10) duration = 20;
                    container.style.setProperty('--scroll-duration', `${duration}s`);
                });
            });
        </script>
    </div>

    {{-- 6. OUR GOALS SECTION --}}
    <div class="bg-[#001D5E] py-24 relative overflow-hidden">
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
           <div class="flex flex-col lg:flex-row items-start gap-12 lg:gap-20">
               <div class="w-full lg:w-5/12 mb-12 lg:mb-0 relative sticky top-24">
                   <img 
                       src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" 
                       alt="Event Planning Team" 
                       class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover h-[400px] lg:h-[550px]"
                   />
                   <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-[#F7941D] rounded-full flex items-center justify-center border-8 border-[#001D5E] z-20 hidden md:flex animate-pulse">
                   </div>
               </div>

               <div class="w-full lg:w-6/12 text-white">
                   <span class="text-[#F7941D] font-bold tracking-widest uppercase text-sm mb-2 block">Our Goals</span>
                   <h2 class="text-3xl md:text-4xl font-bold mb-8 leading-tight">Misi Utama <span class="text-[#F7941D]">Stukka</span></h2>
                   <div class="space-y-8">
                       @foreach($goals as $goal)
                       <div class="flex gap-5 group">
                           <div class="flex-shrink-0">
                               <div class="w-12 h-12 rounded-xl bg-blue-900 group-hover:bg-[#F7941D] flex items-center justify-center transition-all duration-300 shadow-lg border border-blue-800 group-hover:border-orange-400">
                                   <i data-lucide="{{ $goal['icon'] }}" class="w-6 h-6 text-blue-200 group-hover:text-white transition-colors"></i>
                               </div>
                           </div>
                           <div>
                               <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#F7941D] transition-colors">{{ $goal['title'] }}</h3>
                               <p class="text-blue-200 leading-relaxed text-sm">
                                   {{ $goal['description'] }}
                               </p>
                           </div>
                       </div>
                       @endforeach
                   </div>
                   <div class="mt-10 p-6 bg-blue-900/50 rounded-2xl border border-blue-800">
                        <p class="text-gray-300 text-sm italic leading-relaxed">
                            "Dengan mencapai tujuan ini, <strong class="text-[#F7941D]">Stukka Digital Creative</strong> berharap dapat menciptakan pengalaman acara yang luar biasa, meningkatkan standar industri."
                        </p>
                   </div>
                   <div class="mt-8">
                       <a href="{{ route('services') }}" class="inline-flex bg-white text-[#001D5E] hover:bg-gray-100 px-8 py-3 rounded-full font-bold transition-all items-center gap-2 group shadow-xl transform hover:scale-105">
                           Hubungi Tim Kami <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                       </a>
                   </div>
               </div>
           </div>
       </div>
    </div>
@endsection

{{-- Script: auto slide 1-by-1 --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('testi-carousel');
    const track = document.getElementById('testi-track');
    if (!carousel || !track) return;

    const totalOriginal = {{ $tCount }};
    if (totalOriginal <= 3) return; 

    let index = 0;
    let perView = getPerView();

    function getPerView() {
        const w = window.innerWidth;
        if (w >= 1024) return 3; 
        if (w >= 768) return 2;  
        return 1;                
    }

    function slideTo(i, withTransition = true) {
        const itemWidth = carousel.clientWidth / perView;
        if (!withTransition) track.classList.remove('transition-transform', 'duration-700', 'ease-in-out');
        else track.classList.add('transition-transform', 'duration-700', 'ease-in-out');

        track.style.transform = `translateX(-${i * itemWidth}px)`;
    }

    function buildDots() {
        const dotsWrap = document.getElementById('testi-dots');
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (let i = 0; i < totalOriginal; i++) {
            const b = document.createElement('button');
            b.className = 'w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition';
            b.addEventListener('click', () => {
                index = i;
                slideTo(index, true);
                paintDots();
            });
            dotsWrap.appendChild(b);
        }
        paintDots();
    }

    function paintDots() {
        const dotsWrap = document.getElementById('testi-dots');
        if (!dotsWrap) return;
        [...dotsWrap.children].forEach((d, i) => {
            d.className = 'w-2.5 h-2.5 rounded-full transition ' + (i === index ? 'bg-[#F7941D]' : 'bg-gray-300 hover:bg-gray-400');
        });
    }

    let timer = null;
    function start() {
        stop();
        timer = setInterval(() => {
            index++;
            slideTo(index, true);
            paintDots();
        }, 3500);
    }
    function stop() {
        if (timer) clearInterval(timer);
        timer = null;
    }

    track.addEventListener('transitionend', () => {
        if (index >= totalOriginal) {
            index = 0;
            slideTo(index, false);
            paintDots();
        }
    });

    window.addEventListener('resize', () => {
        perView = getPerView();
        slideTo(index, false);
    });

    carousel.addEventListener('mouseenter', stop);
    carousel.addEventListener('mouseleave', start);

    buildDots();
    slideTo(0, false);
    start();
});
</script>