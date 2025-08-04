import { createVuetify } from 'vuetify'
import { ru } from 'vuetify/locale'

export default createVuetify({
    locale: {
        locale: 'ru',
        fallback: 'ru',
        messages: { ru },
      },
    theme: {
      defaultTheme: localStorage.getItem('userTheme') === null ? 'dark':  localStorage.getItem('userTheme'),
    },
  });
