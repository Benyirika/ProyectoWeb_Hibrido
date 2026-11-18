console.log(import.meta.env.VITE_PAGOS_KEY);
import { createApp } from 'vue'
import App from './App.vue'
import { defineCustomElements } from '@ionic/pwa-elements/loader'

defineCustomElements(window)
createApp(App).mount('#app')