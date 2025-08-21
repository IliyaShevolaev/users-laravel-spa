<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";
import CalendarEventDialog from "../components/dialog/Calendar/CalendarEventDialog.vue";
import ViewCalendarEvent from "../components/dialog/Calendar/ViewCalendarEventDialog.vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../stores/auth";
import ViewCalendarEventDialog from "../components/dialog/Calendar/ViewCalendarEventDialog.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import { useModelChangesStore } from "../stores/modelChanges";
import Snackbar from "../components/toaster/Snackbar.vue";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";

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

    eventClick: (info) => {
        console.log("Clicked event:", info.event);
        info.jsEvent.preventDefault();
        openViewDialog(info.event._def.publicId);
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
const isDialogViewMode = ref(false);

const openDialog = function (id = null, isViewMode = false) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
    isDialogViewMode.value = isViewMode;
};

const closeDialog = function (dataChanged, method) {
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
</script>

<template>
    <CalendarEventDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
        :dialogViewMode="isDialogViewMode"
    ></CalendarEventDialog>

    <ViewCalendarEventDialog
        @close-dialog="showViewDialog = false"
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
