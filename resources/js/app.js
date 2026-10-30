import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// =========================================================================
// Water Fill Button Animation (Efek Air Mengisi Saat Diklik)
// =========================================================================
function initWaterFillButtons() {
    document.querySelectorAll('.water-btn').forEach((btn) => {
        if (btn.dataset.waterInit) return;
        btn.dataset.waterInit = 'true';

        btn.addEventListener('click', function (e) {
            // Allow middle click, cmd+click or ctrl+click to open in new tab normally
            if (e.metaKey || e.ctrlKey || e.button !== 0) return;

            const href = this.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:')) return;

            e.preventDefault();

            // Spawn tactile water ripple drop at exact click point
            const rect = this.getBoundingClientRect();
            const drop = document.createElement('span');
            drop.className = 'water-ripple-drop';
            drop.style.left = `${e.clientX - rect.left}px`;
            drop.style.top = `${e.clientY - rect.top}px`;
            this.appendChild(drop);

            // Add filling class to trigger the liquid surge
            this.classList.add('is-filling');

            setTimeout(() => {
                window.location.href = href;
            }, 420);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWaterFillButtons);
} else {
    initWaterFillButtons();
}
window.initWaterFillButtons = initWaterFillButtons;
