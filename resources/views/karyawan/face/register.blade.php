@extends('layouts.karyawan')

@section('title', 'Perekaman Wajah')

@push('styles')
<style>
    @keyframes laserScan {
        0% { top: 8%; opacity: 0.3; }
        50% { top: 88%; opacity: 1; }
        100% { top: 8%; opacity: 0.3; }
    }
    .laser-line {
        position: absolute;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #00F0FF;
        box-shadow: 0 0 15px #00F0FF, 0 0 30px #00F0FF;
        border-radius: 50%;
        animation: laserScan 2.4s ease-in-out infinite;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb / Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#001D5E] text-white text-xs font-bold mb-2">
                <i data-lucide="scan" class="w-3.5 h-3.5 text-[#F7941D]"></i>
                REGISTRASI BIOMETRIK WAJAH
            </div>
            <h1 class="text-2xl font-bold text-[#001D5E]">Perekaman Wajah</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Perekaman fitur biometrik wajah satu kali untuk mencocokkan kehadiran harian Anda.
            </p>
        </div>
        <a href="{{ route('karyawan.dashboard') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all whitespace-nowrap shrink-0 self-start sm:self-auto shadow-xs">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Info Card if already registered --}}
    @if($user->hasRegisteredFace())
        <div class="bg-white border-2 border-slate-300 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[#001D5E]">Wajah Anda Sudah Terdaftar</p>
                    <p class="text-xs text-gray-600 mt-0.5">Terakhir diperbarui: <span class="font-bold">{{ $user->face_registered_at ? $user->face_registered_at->translatedFormat('d F Y H:i') : '-' }} WIB</span></p>
                </div>
            </div>
            @if($user->face_photo)
                <img src="{{ asset('storage/' . $user->face_photo) }}" alt="Foto Wajah" class="w-12 h-12 rounded-xl object-cover border-2 border-[#001D5E] shadow-sm">
            @endif
        </div>
    @endif

    {{-- Camera Container Card (Solid White + Border-2 Slate-300) --}}
    <div class="bg-white rounded-2xl p-6 border-2 border-slate-300 shadow-sm space-y-5">
        
        {{-- Video & HUD Viewfinder --}}
        <div class="relative w-full max-w-lg mx-auto aspect-[4/3] bg-[#0A0F1D] border-2 border-slate-800 rounded-2xl overflow-hidden shadow-2xl flex items-center justify-center">
            
            <video id="video" autoplay muted playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
            <canvas id="overlay" class="absolute inset-0 w-full h-full pointer-events-none transform -scale-x-100"></canvas>
            <div id="laser-line" class="laser-line hidden"></div>

            {{-- Reticle Corners --}}
            <div class="absolute inset-8 pointer-events-none flex flex-col justify-between">
                <div class="flex justify-between">
                    <div id="reticle-tl" class="w-8 h-8 border-t-4 border-l-4 border-cyan-400 rounded-tl transition-colors duration-300"></div>
                    <div id="reticle-tr" class="w-8 h-8 border-t-4 border-r-4 border-cyan-400 rounded-tr transition-colors duration-300"></div>
                </div>
                <div class="flex justify-between">
                    <div id="reticle-bl" class="w-8 h-8 border-b-4 border-l-4 border-cyan-400 rounded-bl transition-colors duration-300"></div>
                    <div id="reticle-br" class="w-8 h-8 border-b-4 border-r-4 border-cyan-400 rounded-br transition-colors duration-300"></div>
                </div>
            </div>



            {{-- Loading Spinner Overlay --}}
            <div id="loading-overlay" class="absolute inset-0 bg-[#0A0F1D]/90 flex flex-col items-center justify-center text-white p-6 text-center z-10">
                <div class="w-12 h-12 border-4 border-[#F7941D] border-t-transparent rounded-full animate-spin mb-4"></div>
                <p id="loading-text" class="text-sm font-bold">Menginisialisasi Kamera & Model Biometrik...</p>
                <p class="text-xs text-gray-400 mt-1">Harap berikan izin akses kamera pada browser Anda.</p>
            </div>
        </div>

        {{-- Status Bar --}}
        <div id="status-card" class="p-4 rounded-xl bg-white border-2 border-slate-300 text-center transition-colors">
            <p id="status-text" class="text-sm font-bold text-gray-700 flex items-center justify-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Posisikan wajah Anda tepat di dalam bingkai scanner...</span>
            </p>
        </div>

        {{-- Capture Button --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-1">
            <button id="btn-capture" disabled
                    class="w-full sm:w-auto px-10 py-4 rounded-xl bg-[#001D5E] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-blue-900 border-2 border-[#001138] text-white font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                <i data-lucide="camera" class="w-5 h-5 text-[#F7941D]"></i>
                <span id="btn-text">Rekam Biometrik Wajah</span>
            </button>
        </div>

        {{-- Petunjuk Pengambilan Wajah --}}
        <div class="p-4 rounded-xl bg-white border-2 border-slate-300 text-xs text-gray-700 space-y-1.5">
            <p class="font-bold text-[#001D5E] flex items-center gap-1.5">
                <i data-lucide="info" class="w-4 h-4 text-[#F7941D]"></i>
                Pedoman Perekaman Wajah yang Akurat:
            </p>
            <ul class="list-disc list-inside space-y-1.5 text-gray-600 pl-1 text-xs">
                <li><span class="font-semibold text-gray-800">Posisi Menghadap Lurus:</span> Tatap lurus kamera tegak ke depan (tidak perlu menengok kanan/kiri agar titik biometrik simetris).</li>
                <li><span class="font-semibold text-gray-800">Pengguna Kacamata:</span> Kacamata bening/minus <span class="text-emerald-700 font-semibold">boleh tetap dipakai</span> jika sehari-hari digunakan. Pastikan lensa tidak memantulkan silau lampu.</li>
                <li><span class="font-semibold text-gray-800">Hindari Penghalang:</span> Lepaskan masker, kacamata hitam/gelap, atau topi yang menutupi kontur alis, mata, dan rahang.</li>
                <li><span class="font-semibold text-gray-800">Pencahayaan Cukup:</span> Pastikan wajah terang dari arah depan (hindari membelakangi jendela / backlight).</li>
            </ul>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const laserLine = document.getElementById('laser-line');
    const loadingOverlay = document.getElementById('loading-overlay');
    const loadingText = document.getElementById('loading-text');
    const statusText = document.getElementById('status-text');
    const statusCard = document.getElementById('status-card');
    const btnCapture = document.getElementById('btn-capture');
    const btnText = document.getElementById('btn-text');

    const reticleTL = document.getElementById('reticle-tl');
    const reticleTR = document.getElementById('reticle-tr');
    const reticleBL = document.getElementById('reticle-bl');
    const reticleBR = document.getElementById('reticle-br');

    let isModelLoaded = false;
    let detectedDescriptor = null;
    let detectionInterval = null;

    function setReticleColor(color = 'cyan') {
        const borderClass = color === 'green' ? 'border-emerald-400' : (color === 'red' ? 'border-red-500' : 'border-cyan-400');
        [reticleTL, reticleTR, reticleBL, reticleBR].forEach(el => {
            el.className = el.className.replace(/border-(cyan|emerald|red)-\d+/, borderClass);
        });
    }

    // Audio Chime
    function playChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1760, ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.25);
        } catch (e) {}
    }

    // 1. Muat Model Face-API dari /models/face-api
    try {
        loadingText.innerText = 'Memuat Model AI Biometrik...';
        const MODEL_URL = '{{ asset("models/face-api") }}';

        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);

        isModelLoaded = true;
        loadingText.innerText = 'Menghubungkan ke Sensor Kamera...';

        // HTTPS Context Check
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            if (location.protocol === 'http:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                window.location.href = window.location.href.replace('http:', 'https:');
                return;
            }
            throw new Error('Webcam memerlukan koneksi aman HTTPS (https://' + location.host + ').');
        }

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            },
            audio: false
        });

        video.srcObject = stream;
        await new Promise(resolve => video.onloadedmetadata = resolve);
        video.play();

        loadingOverlay.classList.add('hidden');
        laserLine.classList.remove('hidden');
        startDetection();

    } catch (err) {
        console.error(err);
        loadingOverlay.innerHTML = `
            <div class="p-6 text-center text-white">
                <p class="font-bold text-base mb-1">Gagal Mengakses Kamera</p>
                <p class="text-xs text-gray-300 max-w-sm mx-auto">${err.message || 'Periksa izin kamera pada browser.'}</p>
                <button onclick="location.reload()" class="mt-4 px-5 py-2.5 bg-white text-[#001D5E] rounded-xl text-xs font-bold border-2 border-slate-300">Coba Lagi</button>
            </div>
        `;
    }

    // 2. Loop Deteksi Wajah
    function startDetection() {
        const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
        faceapi.matchDimensions(overlay, displaySize);

        detectionInterval = setInterval(async () => {
            if (!isModelLoaded) return;

            const detection = await faceapi.detectSingleFace(
                video, 
                new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 })
            ).withFaceLandmarks().withFaceDescriptor();

            const ctx = overlay.getContext('2d');
            ctx.clearRect(0, 0, overlay.width, overlay.height);

            if (detection) {
                detectedDescriptor = detection.descriptor;
                setReticleColor('green');

                statusCard.className = 'p-4 rounded-xl bg-white border-2 border-emerald-600 text-center transition-colors';
                statusText.innerHTML = `
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span class="text-emerald-950 font-bold">Wajah Terdeteksi Sempurna! Klik tombol di bawah untuk merekam.</span>
                `;
                btnCapture.disabled = false;
            } else {
                detectedDescriptor = null;
                setReticleColor('cyan');

                statusCard.className = 'p-4 rounded-xl bg-white border-2 border-slate-300 text-center transition-colors';
                statusText.innerHTML = `
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-gray-800 font-bold">Posisikan wajah Anda tepat di dalam bingkai scanner...</span>
                `;
                btnCapture.disabled = true;
            }
        }, 180);
    }

    // 3. Capture & Simpan ke Server
    btnCapture.addEventListener('click', async () => {
        if (!detectedDescriptor) return;

        btnCapture.disabled = true;
        btnText.innerText = 'Menyimpan Data Biometrik...';

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/jpeg', 0.85);

        try {
            const res = await fetch("{{ route('karyawan.face.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    face_descriptor: Array.from(detectedDescriptor),
                    image: imageData
                })
            });

            const data = await res.json();

            if (data.success) {
                clearInterval(detectionInterval);
                playChime();

                statusCard.className = 'p-4 rounded-xl bg-emerald-600 text-white text-center';
                statusText.innerHTML = `
                    <span class="text-white font-bold text-sm">${data.message} Mengalihkan ke scanner presensi...</span>
                `;
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                if (window.notify) {
                    window.notify.error(data.message || 'Terjadi kesalahan saat memvalidasi biometrik wajah.', 'Registrasi Gagal');
                } else {
                    alert(data.message || 'Terjadi kesalahan.');
                }
                btnCapture.disabled = false;
                btnText.innerText = 'Rekam Biometrik Wajah';
            }
        } catch (e) {
            console.error(e);
            if (window.notify) {
                window.notify.error('Gagal mengirim data biometrik ke server. Periksa jaringan internet Anda.', 'Koneksi Terputus');
            } else {
                alert('Gagal mengirim data biometrik ke server.');
            }
            btnCapture.disabled = false;
            btnText.innerText = 'Rekam Biometrik Wajah';
        }
    });
});
</script>
@endpush
