import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router/router';

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'

const app = createApp(App);
app.use(router);
app.use(createVuetify());
app.mount('#app');
