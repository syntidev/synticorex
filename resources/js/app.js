import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// FlyonUI overlay (drawer/modal JS system — handles data-overlay triggers,
// [--opened:lg] auto-open, [--is-layout-affect:true] layout shift, etc.)
import 'flyonui/dist/overlay.mjs';
