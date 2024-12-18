import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './vendor/intl-tel-input/build/css/intlTelInput.min.css'
import './styles/app.css';
import './styles/admin.css';
import './styles/mustmenager.css';
import './styles/home-carousel.css';
import './styles/single-product.css';
import './styles/filter.css';

import { initFlowbite } from 'flowbite';

document.addEventListener('turbo:render', () => {
    initFlowbite();
});
document.addEventListener('turbo:frame-render', () => {
    initFlowbite();
});