<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";
import CalendarEventDialog from "../components/dialog/CalendarEventDialog.vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../stores/auth";

const { t } = useI18n();
const authStore = useAuthStore();

const events = ref([]);

let abortController = null;

const requestEvents = (start, end) => {
    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();

    const params = { start, end };

    axios
        .get("/api/events", {
            params,
            signal: abortController.signal,
        })
        .then((response) => {
            events.value = response.data.data;
        });
};

const calendarRef = ref(null);

const startStr = ref(null);
const endStr = ref(null);

const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    locale: ruLocale,

    headerToolbar: {
        left: "",
        center: "",
        right: "",
    },

    events: events,

    datesSet: (dateInfo) => {
        requestEvents(dateInfo.startStr, dateInfo.endStr);

        startStr.value = dateInfo.startStr;
        endStr.value = dateInfo.endStr;

        currentViewTitle.value = calendarRef.value.getApi().view.title;
    },
});

const currentViewTitle = ref(null);

const goPrev = () => {
    calendarRef.value.getApi().prev();
};

const goNext = () => {
    calendarRef.value.getApi().next();
};

const goToday = () => {
    calendarRef.value.getApi().today();
};

const isDialogOpen = ref(false);
const dialogEditId = ref(null);

const openDialog = function (id = null) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
};

const closeDialog = function (dataChanged, method) {
    if (dataChanged) {
        requestEvents(startStr.value, endStr.value);
    }

    // if (method === "add") {
    //     showSnackBar(
    //         t("users.position") +
    //             " " +
    //             modelChangesStore.getPosition.lastAdd +
    //             " " +
    //             t("users.positions.was_append"),
    //         "success"
    //     );
    // } else if (method === "edit") {
    //     showSnackBar(
    //         t("users.position") +
    //             " " +
    //             modelChangesStore.getPosition.lastEdit +
    //             " " +
    //             t("users.positions.was_edited"),
    //         "warning"
    //     );
    // }

    isDialogOpen.value = false;
    dialogEditId.value = null;
};
</script>

<template>
    <CalendarEventDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></CalendarEventDialog>

    <div class="flex justify-between">
        <div>
            <v-btn
                color="success"
                prepend-icon="ri-add-line"
                @click="openDialog()"
                v-if="
                    authStore.hasOneOfEachPermission(
                        'tasks-createDepartment',
                        'tasks-createAll'
                    )
                "
            >
                {{ t("main.append_button") }}
            </v-btn>
        </div>
        <div>
            <p class="text-2xl font-bold">{{ currentViewTitle }}</p>
        </div>

        <div class="flex gap-2">
            <v-btn @click="goPrev" variant="outlined">
                <v-icon size="large">ri-arrow-left-line</v-icon>
            </v-btn>
            <v-btn @click="goToday" variant="outlined">
                {{ t("calendar.today") }}
            </v-btn>
            <v-btn @click="goNext" variant="outlined">
                <v-icon size="large">ri-arrow-right-line</v-icon>
            </v-btn>
        </div>
    </div>

    <FullCalendar :options="calendarOptions" ref="calendarRef" />
</template>
