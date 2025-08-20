<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";
import CalendarEventDialog from "../components/dialog/CalendarEventDialog.vue";

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

const startStr = ref(null)
const endStr = ref(null)

const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    locale: ruLocale,

    customButtons: {
        addButton: {
            text: "Создать событие",
            click: () => {
                openDialog();
            },
        },
    },

    headerToolbar: {
        left: "title",
        center: "addButton",
        right: "today prev,next",
    },

    events: events,

    datesSet: (dateInfo) => {
        requestEvents(dateInfo.startStr, dateInfo.endStr);
        startStr.value = dateInfo.startStr
        endStr.value = dateInfo.endStr
    },
});

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

    <FullCalendar :options="calendarOptions" />
</template>
