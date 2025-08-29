<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";
import CalendarEventDialog from "../components/dialog/Calendar/CalendarEventDialog.vue";
import { useI18n } from "vue-i18n";
import ViewCalendarEventDialog from "../components/dialog/Calendar/ViewCalendarEventDialog.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import { useModelChangesStore } from "../stores/modelChanges";
import Snackbar from "../components/toaster/Snackbar.vue";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";
import dayjs from "dayjs";
import { useAuthStore } from "../stores/auth";
import { listenCalendarUpdates } from "../composables/events/calendarEventsChange";

const modelChangesStore = useModelChangesStore();
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
            console.log(response);
            events.value = response.data.data;
            validateEvents();
        });
};

const validateEvents = function () {
    events.value.forEach((event) => {
        if (event.is_done) {
            event.backgroundColor = "green";
            event.borderColor = "green";
        } else {
            event.backgroundColor = "#2a90bf";
            event.borderColor = "#2a90bf";
        }

        if (event.end) {
            event.end = dayjs(event.end).format("YYYY-MM-DD HH:mm:ss");
        }
    });
};

const handleCalendarChange = function () {
    requestEvents(startStr.value, endStr.value);
};

listenCalendarUpdates(handleCalendarChange);

const calendarRef = ref(null);

const startStr = ref(null);
const endStr = ref(null);

const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
    initialView: "dayGridMonth",
    locale: ruLocale,
    displayEventTime: false,
    editable: authStore.checkPermission("tasks-update"),

    headerToolbar: false,

    events: events,

    datesSet: (dateInfo) => {
        requestEvents(dateInfo.startStr, dateInfo.endStr);

        startStr.value = dateInfo.startStr;
        endStr.value = dateInfo.endStr;

        currentViewTitle.value = calendarRef.value.getApi().view.title;
    },

    eventClick: (info) => {
        info.jsEvent.preventDefault();
        openViewDialog(info.event.id);
    },

    dateClick: (info) => {
        openDialog(null, dayjs(info.date).format("YYYY-MM-DD HH:mm:ss"));
    },

    eventDrop: (info) => {
        handleEventDrop(info);
    },
});

const currentViewTitle = ref(null);

const goPrev = function () {
    calendarRef.value.getApi().prev();
};

const goNext = function () {
    calendarRef.value.getApi().next();
};

const goToday = function () {
    calendarRef.value.getApi().today();
};

const changeViewFormat = function (format) {
    calendarRef.value.getApi().changeView(format);
};

const isDialogOpen = ref(false);
const dialogEditId = ref(null);
const dialogChoosenDate = ref(null);

const openDialog = function (id = null, choosenDate = null) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
    dialogChoosenDate.value = choosenDate;
};

const closeDialog = function (dataChanged, method) {
    dialogChoosenDate.value = null;

    if (dataChanged) {
        requestEvents(startStr.value, endStr.value);
    }

    if (method === "add") {
        showSnackBar(
            t("calendar.event") +
                " " +
                modelChangesStore.getEvent.lastAdd +
                " " +
                t("calendar.was_append"),
            "success"
        );
    } else if (method === "edit") {
        showSnackBar(
            t("calendar.event") +
                " " +
                modelChangesStore.getEvent.lastEdit +
                " " +
                t("calendar.was_edited"),
            "warning"
        );
    }

    isDialogOpen.value = false;
    dialogEditId.value = null;
};

let patchAbortController = null;

const handleEventDrop = function (info) {
    if (patchAbortController) {
        patchAbortController.abort();
    }
    patchAbortController = new AbortController();

    let requestDateStart = info.event.start;
    let requestDateEnd = info.event.end;

    if (requestDateStart !== null && requestDateEnd === null) {
        requestDateEnd = requestDateStart;
    }

    modelChangesStore.editEvent(info.event.title);
    axios
        .patch(
            `/api/events/patch/${info.event.id}`,
            {
                start: dayjs(requestDateStart).format("YYYY-MM-DD HH:mm:ss"),
                end: dayjs(requestDateEnd).format("YYYY-MM-DD HH:mm:ss"),
            },
            { signal: patchAbortController.signal }
        )
        .then(() => {
            showSnackBar(
                t("calendar.event") +
                    " " +
                    modelChangesStore.getEvent.lastEdit +
                    " " +
                    t("calendar.was_edited"),
                "warning"
            );
            requestEvents(startStr.value, endStr.value);
        })
        .catch((error) => {
            info.revert();
            if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("calendar.no_selected");
            } else if (error.response.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("calendar.no_permission");
            }
        });
};

const showViewDialog = ref(false);
const showId = ref(null);
const openViewDialog = function (id) {
    showViewDialog.value = true;
    showId.value = id;
};

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");

const idToDelete = ref(0);

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `${t("calendar.delete_event")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteEvent(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/events/${id}`)
        .then(() => {
            requestEvents(startStr.value, endStr.value);
            showSnackBar(
                t("calendar.event") +
                    " " +
                    modelChangesStore.getEvent.lastDelete +
                    " " +
                    t("calendar.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("calendar.no_selected");
            }
        });
    showAlertAcceptDialog.value = false;
};

const closeViewDialogToEdit = function (id) {
    showViewDialog.value = false;
    openDialog(id);
};

const closeViewDialogToDelete = function (id, name) {
    showViewDialog.value = false;
    askToDeleteRow(id, name);
};

const isSnackbarOpen = ref(false);
const snackbarMessage = ref("");
const snackbarColor = ref("");

const showSnackBar = function (message, color) {
    isSnackbarOpen.value = false;
    setTimeout(() => {
        snackbarMessage.value = message;
        snackbarColor.value = color;
        isSnackbarOpen.value = true;
    }, 10);
};

const showAlertDialog = ref(false);
const alertText = ref("");

const closeViewDialog = function (eventWasMarked) {
    showViewDialog.value = false;
    if (eventWasMarked) {
        requestEvents(startStr.value, endStr.value);
    }
};
</script>

<template>
    <CalendarEventDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
        :choosen-date="dialogChoosenDate"
    ></CalendarEventDialog>

    <ViewCalendarEventDialog
        @close-dialog="closeViewDialog"
        @edit-event="closeViewDialogToEdit"
        @delete-event="closeViewDialogToDelete"
        :is-open="showViewDialog"
        :show-id="showId"
    ></ViewCalendarEventDialog>

    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteRow(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    ></AcceptDialog>

    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

    <Snackbar
        :color="snackbarColor"
        :message="snackbarMessage"
        :is-open="isSnackbarOpen"
        @close-snackbar="isSnackbarOpen = false"
    ></Snackbar>

    <div class="mb-3">
        <v-btn
            color="success"
            prepend-icon="ri-add-line"
            @click="openDialog()"
            v-if="authStore.hasOneOfEachPermission('tasks-create')"
        >
            {{ t("main.append_button") }}
        </v-btn>
    </div>

    <div class="flex justify-between">
        <div class="flex gap-2 mb-4 items-center">
            <v-btn variant="outlined" @click="changeViewFormat('dayGridMonth')">
                Месяц
            </v-btn>
            <v-btn variant="outlined" @click="changeViewFormat('timeGridWeek')">
                Неделя
            </v-btn>
            <v-btn variant="outlined" @click="changeViewFormat('timeGridDay')">
                День
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
