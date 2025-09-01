import { createVuetify } from "vuetify";
import { VBtn } from "vuetify/components/VBtn";
import defaults from "./defaults";
import { icons } from "./icons";
import { themes } from "./theme";
import { ru } from 'vuetify/locale'

// Styles
import "@core/scss/template/libs/vuetify/index.scss";
import "vuetify/styles";

import colors from 'vuetify/util/colors'

export default function (app) {
    const vuetify = createVuetify({
        aliases: {
            IconBtn: VBtn,
        },
        locale: {
            locale: "ru",
            fallback: "ru",
            messages: { ru },
        },
        defaults,
        icons,
        theme: {
            defaultTheme: "light",
            themes,
        },
    });

    app.use(vuetify);
}
