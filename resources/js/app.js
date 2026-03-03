import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import 'preline';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (window.HSStaticMethods) {
        window.HSStaticMethods.autoInit();
    }
});
