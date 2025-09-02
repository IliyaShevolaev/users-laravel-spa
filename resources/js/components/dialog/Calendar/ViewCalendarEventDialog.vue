<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { computed, reactive, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../../../stores/auth";
import AlertDangerDialog from "../../alerts/AlertDangerDialog.vue";
import { useTheme } from "vuetify";
import VueDatePicker from "@vuepic/vue-datepicker";

const vuetifyTheme = useTheme();
const { t } = useI18n();
const authStore = useAuthStore();

const props = defineProps({
    showId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog", "deleteEvent", "editEvent"]);

const close = function () {
    emit("closeDialog", eventWasMarked.value);
    eventInfo.value = null;
    dateRange.value = null;
};

const loading = ref(true);

const eventInfo = ref(null);

const requestInfo = function () {
    loading.value = true;
    axios
        .get(`/api/events/${props.showId}`)
        .then((response) => {
            console.log(response);
            eventInfo.value = response.data.data;
            loading.value = false;
        })
        .catch((error) => {
            if (error.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("calendar.no_selected");
                close(false);
            }
            console.log(error);
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            requestInfo();
        }
    }
);

const dateRange = ref(null);

const editButtonHandle = function () {
    if (eventInfo.value) {
        emit("editEvent", eventInfo.value.id);
        eventInfo.value = null;
    }
};

const deleteButtonHandle = function () {
    if (eventInfo.value) {
        emit("deleteEvent", eventInfo.value.id, eventInfo.value.title);
    }
};

const eventWasMarked = ref(false);

const formData = reactive({
    end_time: null,
});

watch(
    dateRange,
    (newDate) => {
        if (newDate) {
            formData.end_time = dayjs(newDate).format("YYYY-MM-DD HH:mm:ss");
        }
    },
);

const markButtonHandle = function (newMarkedValue) {
    loading.value = true;
    eventWasMarked.value = false;
    if (eventInfo.value) {
        axios
            .post(`/api/events/mark/${eventInfo.value.id}`, formData)
            .then((response) => {
                if (eventInfo.value) {
                    eventInfo.value.is_done = newMarkedValue;
                }
                loading.value = false;
                eventWasMarked.value = true;
                console.log(eventWasMarked.value);
            })
            .catch((error) => {
                console.log(error);
            });
    }
};

const showAlertDialog = ref(false);
const alertText = ref("");

const checkDeleteButtonViewPermission = computed(() => {
    if (eventInfo.value) {
        return (
            authStore.checkPermission("tasks-delete") &&
            eventInfo.value.change_permission
        );
    }

    return false;
});

const checkEditButtonViewPermission = computed(() => {
    if (eventInfo.value) {
        return (
            authStore.checkPermission("tasks-update") &&
            eventInfo.value.change_permission
        );
    }

    return false;
});

const isDone = computed(() => {
    if (eventInfo.value) {
        return eventInfo.value.is_done;
    }
    return false;
});

const minEventDoneDate = computed(() => {
    if (eventInfo.value) {
        return eventInfo.value.start;
    }
    return null;
});

const maxEventDoneDate = computed(() => {
    if (eventInfo.value) {
        return eventInfo.value.end;
    }
    return null;
});
</script>

<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-skeleton-loader v-if="loading" type="card"></v-skeleton-loader>

        <v-card v-else>
            <v-card-title>
                <span class="headline flex justify-between">
                    {{ t("calendar.view_header") }}
                    <v-btn
                        icon
                        variant="text"
                        size="large"
                        @click="close(false)"
                    >
                        <v-icon size="30" class="text-gray-900">
                            ri-close-line
                        </v-icon>
                    </v-btn>
                </span>
            </v-card-title>
            <v-card-text>
                <div class="flex flex-column">
                    <span class="text-xl font-bold">
                        {{ t("main.title") }}:
                        {{ eventInfo ? eventInfo.title : "" }}
                    </span>
                    <span class="text-l mt-2 font-semibold">
                        {{ t("main.description") }}:
                        {{ eventInfo ? eventInfo.description : "" }}
                    </span>
                    <span class="text-l mt-2 font-semibold">
                        {{ t("calendar.task_was_assigned_by") }}:
                        {{ eventInfo ? eventInfo.creator.name : "" }}
                    </span>
                    <span class="text-l mt-2">
                        {{ t("calendar.direction") }}:
                        {{
                            eventInfo
                                ? dayjs(eventInfo.start).format(
                                      "DD.MM.YYYY HH:mm"
                                  )
                                : ""
                        }}
                        —
                        {{
                            eventInfo
                                ? dayjs(eventInfo.end).format(
                                      "DD.MM.YYYY HH:mm"
                                  )
                                : ""
                        }}
                    </span>
                    <span class="flex gap-3 text-l mt-2">
                        <p>Время исполнения:</p>
                        <VueDatePicker
                            :dark="vuetifyTheme.global.name.value === 'dark'"
                            v-model="dateRange"
                            locale="ru"
                            format="dd.MM.yyyy HH:mm"
                            :enable-time-picker="true"
                            :teleport="true"
                            teleport-center
                            :min-date="minEventDoneDate"
                            :max-date="maxEventDoneDate"
                            prevent-min-max-navigation
                        >
                            <template #action-row="{ selectDate, closePicker }">
                                <div class="flex justify-between w-full">
                                    <v-btn
                                        variant="outlined"
                                        size="small"
                                        @click="closePicker()"
                                    >
                                        {{ t("calendar.disable_button") }}
                                    </v-btn>

                                    <v-btn
                                        variant="outlined"
                                        size="small"
                                        @click="selectDate()"
                                    >
                                        {{ t("calendar.accept_button") }}
                                    </v-btn>
                                </div>
                            </template>
                        </VueDatePicker>
                    </span>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close()">
                    {{ t("main.close_button") }}
                </v-btn>
                <v-btn
                    v-if="checkDeleteButtonViewPermission"
                    color="error"
                    variant="elevated"
                    @click="deleteButtonHandle"
                >
                    {{ t("main.delete_button") }}
                </v-btn>
                <v-btn
                    v-if="checkEditButtonViewPermission"
                    color="warning"
                    variant="elevated"
                    @click="editButtonHandle"
                >
                    {{ t("main.edit_button") }}
                </v-btn>
                <v-btn
                    v-if="isDone"
                    color="success"
                    variant="outlined"
                    style="text-transform: none"
                    @click="markButtonHandle(false)"
                >
                    {{ t("calendar.is_done") }}
                </v-btn>

                <v-btn
                    v-else
                    color="success"
                    variant="elevated"
                    style="text-transform: none"
                    @click="markButtonHandle(true)"
                >
                    {{ t("calendar.set_done") }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
