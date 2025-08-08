import { createI18n } from "vue-i18n";
import ru from "../../lang/ru.json";

const messages = { ru };

const i18n = createI18n({
    legacy: false,
    locale: "ru",
    fallbackLocale: "en",
    messages,
});

export default function (app) {
  app.use(i18n)
}
