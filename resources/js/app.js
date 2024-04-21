import './bootstrap';
import { createApp } from 'vue';
import MainComponent from './components/MainComponent.vue';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Create A Vue App
createApp(MainComponent).mount('#app')