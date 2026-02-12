import './bootstrap';
import '../../vendor/alperenersoy/filament-export/resources/js/filament-export.js';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.flatpickr = flatpickr;
window.Alpine = Alpine;

Alpine.start();
