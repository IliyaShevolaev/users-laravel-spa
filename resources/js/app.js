import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router/router';
import { createPinia } from 'pinia'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import vuetify from './vuetify';

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.use(createPinia())
app.mount('#app');
