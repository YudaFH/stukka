import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// =========================================================================
// Water Fill Button Animation (Efek Air Mengisi Saat Diklik)
// =========================================================================
function initWaterFillButtons() {
    document.querySelectorAll('.water-fill-btn').forEach((btn) => {
        if (btn.dataset.waterInit) return;
        btn.dataset.waterInit = 'true';

        btn.addEventListener('click', function (e) {
            // Allow middle click, cmd+click or ctrl+click to open in new tab normally
            if (e.metaKey || e.ctrlKey || e.button !== 0) return;

            const href = this.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:')) return;

            e.preventDefault();

            // Spawn liquid splash ripple at exact click point
            const rect = this.getBoundingClientRect();
            const splash = document.createElement('span');
            splash.className = 'water-click-splash';
            splash.style.left = `${e.clientX - rect.left}px`;
            splash.style.top = `${e.clientY - rect.top}px`;
            this.appendChild(splash);

            // Add filling class to trigger the liquid surge
            this.classList.add('is-filling');

            setTimeout(() => {
                window.location.href = href;
            }, 430);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWaterFillButtons);
} else {
    initWaterFillButtons();
}
window.initWaterFillButtons = initWaterFillButtons;
