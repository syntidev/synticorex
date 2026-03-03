import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();

// FlyonUI overlay (drawer/modal JS system — handles data-overlay triggers,
// [--opened:lg] auto-open, [--is-layout-affect:true] layout shift, etc.)
import 'flyonui/dist/overlay.mjs';
