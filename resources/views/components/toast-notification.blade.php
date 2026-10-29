{{-- 
    KOMPONEN NOTIFIKASI PROFESIONAL STUKKA
    - Toast Floating Notification (Success, Error, Warning, Info)
    - Modal Dialog Konfirmasi & Alert (Menggantikan alert() dan confirm() browser yang biasa)
    - Auto-detect Session Flash Laravel
--}}

{{-- Container Toast (Floating Top-Right) --}}
<div id="stukka-toast-container" 
     class="fixed top-5 right-5 z-[999999] flex flex-col gap-3 pointer-events-none max-w-sm sm:max-w-md w-[calc(100%-2.5rem)] sm:w-auto"
     aria-live="polite">
</div>

{{-- Modal Konfirmasi / Alert Profesional --}}
<div id="stukka-modal-backdrop" 
     class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[9999999] hidden flex items-center justify-center p-4 transition-opacity duration-200 opacity-0 pointer-events-none">
    <div id="stukka-modal-card" 
         class="bg-white rounded-3xl border-2 border-slate-300 max-w-md w-full p-6 shadow-2xl transform scale-95 transition-transform duration-200 pointer-events-auto">
        <div class="flex items-start gap-4">
            <div id="stukka-modal-icon-wrap" class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border-2">
                <i id="stukka-modal-icon" data-lucide="help-circle" class="w-6 h-6"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h3 id="stukka-modal-title" class="text-base font-black text-gray-900 leading-tight">Konfirmasi Tindakan</h3>
                <p id="stukka-modal-message" class="text-xs text-slate-600 font-medium leading-relaxed mt-1.5 break-words"></p>
            </div>
        </div>
        <div id="stukka-modal-actions" class="mt-6 pt-4 border-t-2 border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="stukka-modal-btn-cancel" 
                    class="px-5 py-2.5 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                Batal
            </button>
            <button type="button" id="stukka-modal-btn-confirm" 
                    class="px-5 py-2.5 rounded-xl text-white text-xs font-bold transition-all border-2 cursor-pointer shadow-sm">
                Lanjutkan
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes stukkaSlideIn {
        from {
            opacity: 0;
            transform: translateX(100%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }
    @keyframes stukkaSlideOut {
        from {
            opacity: 1;
            transform: translateX(0) scale(1);
            max-height: 120px;
            margin-bottom: 0.75rem;
        }
        to {
            opacity: 0;
            transform: translateX(100%) scale(0.95);
            max-height: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
    }
    .stukka-toast-enter {
        animation: stukkaSlideIn 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .stukka-toast-exit {
        animation: stukkaSlideOut 0.25s cubic-bezier(0.4, 0, 1, 1) forwards;
    }
</style>

<script>
(function() {
    // 1. Audio Synthesizer (Chime Halus Tanpa File Eksternal)
    function playAudio(type = 'success') {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            if (type === 'success') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.1); // A5
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.25);
            } else if (type === 'error') {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(261.63, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(196, ctx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.22);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.22);
            } else {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(523.25, ctx.currentTime);
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.2);
            }
        } catch (e) {}
    }

    // 2. Global Toast Manager
    const toastConfig = {
        success: {
            border: 'border-emerald-600',
            iconBg: 'bg-emerald-600 text-white border-emerald-700',
            icon: 'check',
            titleText: 'text-emerald-950',
            barBg: 'bg-emerald-600',
            defaultTitle: 'Berhasil'
        },
        error: {
            border: 'border-red-600',
            iconBg: 'bg-red-600 text-white border-red-700',
            icon: 'alert-triangle',
            titleText: 'text-red-950',
            barBg: 'bg-red-600',
            defaultTitle: 'Terjadi Kesalahan'
        },
        warning: {
            border: 'border-[#F7941D]',
            iconBg: 'bg-[#F7941D] text-white border-[#D67B0F]',
            icon: 'alert-circle',
            titleText: 'text-[#F7941D]',
            barBg: 'bg-[#F7941D]',
            defaultTitle: 'Peringatan'
        },
        info: {
            border: 'border-[#001D5E]',
            iconBg: 'bg-[#001D5E] text-white border-[#001138]',
            icon: 'info',
            titleText: 'text-[#001D5E]',
            barBg: 'bg-[#001D5E]',
            defaultTitle: 'Informasi'
        }
    };

    function createToast({ type = 'info', title = null, message = '', duration = 4500 }) {
        const container = document.getElementById('stukka-toast-container');
        if (!container) return;

        const cfg = toastConfig[type] || toastConfig.info;
        const displayTitle = title || cfg.defaultTitle;

        playAudio(type);

        const toast = document.createElement('div');
        toast.className = `stukka-toast-enter pointer-events-auto relative overflow-hidden bg-white rounded-2xl border-2 ${cfg.border} shadow-2xl p-4 flex items-start gap-3.5 transition-all`;

        toast.innerHTML = `
            <div class="w-9 h-9 rounded-xl ${cfg.iconBg} border-2 flex items-center justify-center shrink-0 shadow-xs">
                <i data-lucide="${cfg.icon}" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-black uppercase tracking-wider ${cfg.titleText}">${displayTitle}</p>
                    <button type="button" class="text-slate-400 hover:text-slate-700 p-1 -mr-1 -mt-1 rounded-lg transition-colors cursor-pointer" aria-label="Tutup">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-700 font-medium leading-relaxed mt-1 break-words">${message}</p>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-100">
                <div class="h-full ${cfg.barBg} transition-all" style="width: 100%;"></div>
            </div>
        `;

        container.appendChild(toast);
        if (window.lucide) window.lucide.createIcons({ root: toast });

        const progressBar = toast.querySelector('div > div.transition-all');
        const closeBtn = toast.querySelector('button');

        let remaining = duration;
        const interval = 25;
        let isPaused = false;

        const progressTimer = setInterval(() => {
            if (!isPaused) {
                remaining -= interval;
                const percent = Math.max(0, (remaining / duration) * 100);
                if (progressBar) progressBar.style.width = percent + '%';

                if (remaining <= 0) {
                    clearInterval(progressTimer);
                    dismiss();
                }
            }
        }, interval);

        function dismiss() {
            clearInterval(progressTimer);
            toast.classList.remove('stukka-toast-enter');
            toast.classList.add('stukka-toast-exit');
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 260);
        }

        closeBtn.addEventListener('click', dismiss);
        toast.addEventListener('mouseenter', () => isPaused = true);
        toast.addEventListener('mouseleave', () => isPaused = false);
    }

    // 3. Modal Dialog Manager (Alert & Confirm)
    let modalResolver = null;
    const modalBackdrop = document.getElementById('stukka-modal-backdrop');
    const modalCard = document.getElementById('stukka-modal-card');
    const modalTitle = document.getElementById('stukka-modal-title');
    const modalMessage = document.getElementById('stukka-modal-message');
    const modalIconWrap = document.getElementById('stukka-modal-icon-wrap');
    const modalIcon = document.getElementById('stukka-modal-icon');
    const btnCancel = document.getElementById('stukka-modal-btn-cancel');
    const btnConfirm = document.getElementById('stukka-modal-btn-confirm');

    function openModal({ 
        type = 'warning', 
        title = 'Konfirmasi', 
        message = '', 
        confirmText = 'Lanjutkan', 
        cancelText = 'Batal',
        isConfirm = true 
    }) {
        return new Promise((resolve) => {
            modalResolver = resolve;

            modalTitle.innerText = title;
            modalMessage.innerHTML = message;
            btnConfirm.innerText = confirmText;
            btnCancel.innerText = cancelText;

            if (isConfirm) {
                btnCancel.classList.remove('hidden');
            } else {
                btnCancel.classList.add('hidden');
            }

            // Styling berdasarkan type
            const cfg = toastConfig[type] || toastConfig.warning;
            modalIconWrap.className = `w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border-2 ${cfg.iconBg}`;
            modalIcon.setAttribute('data-lucide', cfg.icon);

            if (type === 'error') {
                btnConfirm.className = 'px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all border-2 border-red-700 cursor-pointer shadow-sm';
            } else if (type === 'success') {
                btnConfirm.className = 'px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all border-2 border-emerald-700 cursor-pointer shadow-sm';
            } else if (type === 'info') {
                btnConfirm.className = 'px-5 py-2.5 rounded-xl bg-[#001D5E] hover:bg-blue-900 text-white text-xs font-bold transition-all border-2 border-[#001138] cursor-pointer shadow-sm';
            } else {
                btnConfirm.className = 'px-5 py-2.5 rounded-xl bg-[#F7941D] hover:bg-orange-600 text-white text-xs font-bold transition-all border-2 border-[#D67B0F] cursor-pointer shadow-sm';
            }

            if (window.lucide) window.lucide.createIcons({ root: modalIconWrap });

            modalBackdrop.classList.remove('hidden', 'pointer-events-none');
            // Trigger animation
            requestAnimationFrame(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalCard.classList.remove('scale-95');
                modalCard.classList.add('scale-100');
            });

            playAudio(type);
        });
    }

    function closeModal(result) {
        modalBackdrop.classList.add('opacity-0');
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
        setTimeout(() => {
            modalBackdrop.classList.add('hidden', 'pointer-events-none');
            if (modalResolver) {
                modalResolver(result);
                modalResolver = null;
            }
        }, 180);
    }

    btnCancel.addEventListener('click', () => closeModal(false));
    btnConfirm.addEventListener('click', () => closeModal(true));
    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) closeModal(false);
    });

    document.addEventListener('keydown', (e) => {
        if (!modalBackdrop.classList.contains('hidden')) {
            if (e.key === 'Escape') closeModal(false);
            if (e.key === 'Enter') closeModal(true);
        }
    });

    // 4. Expose Global API (window.notify)
    window.notify = {
        success: (msg, title = 'Berhasil') => createToast({ type: 'success', title, message: msg }),
        error: (msg, title = 'Terjadi Kesalahan') => createToast({ type: 'error', title, message: msg, duration: 6000 }),
        warning: (msg, title = 'Peringatan') => createToast({ type: 'warning', title, message: msg, duration: 5000 }),
        info: (msg, title = 'Informasi') => createToast({ type: 'info', title, message: msg }),
        show: createToast,
        alert: (msg, title = 'Pemberitahuan', type = 'info') => openModal({ type, title, message: msg, isConfirm: false, confirmText: 'Mengerti' }),
        confirm: (msg, title = 'Konfirmasi Tindakan', options = {}) => openModal({
            type: options.type || 'warning',
            title,
            message: msg,
            confirmText: options.confirmText || 'Ya, Lanjutkan',
            cancelText: options.cancelText || 'Batal',
            isConfirm: true
        })
    };

    // Override browser alert default agar selalu profesional
    window.alert = function(msg) {
        window.notify.alert(msg, 'Informasi Sistem');
    };

    // 5. Intercept Forms with data-confirm / onsubmit confirm
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        const onsubmitAttr = form.getAttribute('onsubmit');
        const hasConfirm = onsubmitAttr && onsubmitAttr.includes('confirm(');
        const confirmMsgAttr = form.getAttribute('data-confirm');

        if (hasConfirm || confirmMsgAttr) {
            if (form._stukkaConfirmed) {
                delete form._stukkaConfirmed;
                return; // Allow submission
            }

            e.preventDefault();
            e.stopImmediatePropagation();

            let msg = confirmMsgAttr;
            if (!msg && hasConfirm) {
                const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                msg = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            }

            const isDelete = form.querySelector('input[name="_method"][value="DELETE"]') || 
                             (onsubmitAttr && onsubmitAttr.toLowerCase().includes('hapus')) ||
                             (msg && msg.toLowerCase().includes('hapus'));

            window.notify.confirm(msg, 'Konfirmasi Tindakan', {
                type: isDelete ? 'error' : 'warning',
                confirmText: isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan'
            }).then(confirmed => {
                if (confirmed) {
                    form._stukkaConfirmed = true;
                    form.submit();
                }
            });
        }
    }, true);

    document.addEventListener('click', async (e) => {
        const target = e.target.closest('a[data-confirm]');
        if (!target) return;

        const confirmMsg = target.getAttribute('data-confirm');
        if (confirmMsg) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const confirmed = await window.notify.confirm(confirmMsg, 'Konfirmasi Tindakan', {
                type: target.getAttribute('data-confirm-type') || 'warning',
                confirmText: target.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan'
            });

            if (confirmed && target.href) {
                window.location.href = target.href;
            }
        }
    }, true);

    // 6. Flash Session Auto-trigger dari Backend Laravel
    @if(session('success'))
        setTimeout(() => window.notify.success(@json(session('success'))), 200);
    @endif

    @if(session('error'))
        setTimeout(() => window.notify.error(@json(session('error'))), 250);
    @endif

    @if(session('warning'))
        setTimeout(() => window.notify.warning(@json(session('warning'))), 250);
    @endif

    @if(session('status'))
        setTimeout(() => window.notify.info(@json(session('status'))), 200);
    @endif

    @if($errors->any())
        setTimeout(() => {
            const errList = @json($errors->all());
            const errMsg = errList.length > 1 
                ? '<ul class="list-disc list-inside mt-1 space-y-0.5">' + errList.map(e => `<li>${e}</li>`).join('') + '</ul>'
                : errList[0];
            window.notify.error(errMsg, 'Validasi Gagal');
        }, 300);
    @endif
})();
</script>
