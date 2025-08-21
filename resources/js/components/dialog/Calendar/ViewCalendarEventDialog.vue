<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { watch } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../../../stores/auth";

const { t } = useI18n();
const authStore = useAuthStore();

const props = defineProps({
    showId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog", "deleteEvent"]);

const close = function () {
    emit("closeDialog");
    eventInfo.value = null;
};

const eventInfo = ref(null);

const requestInfo = function () {
    axios
        .get(`/api/events/${props.showId}`)
        .then((response) => {
            console.log(response);
            eventInfo.value = response.data.data;
        })
        .catch((error) => {
            if (error.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("users.positions.no_selected");
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

const deleteButtonHandle = function () {
    if (eventInfo.value) {
        emit("deleteEvent", eventInfo.value.id, eventInfo.value.title);
        eventInfo.value = null;
    }
};
</script>

<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
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
                    <span class="text-l mt-2">
                        {{ t("calendar.direction") }}:
                        {{
                            eventInfo
                                ? dayjs(eventInfo.start).format("DD.MM.YYYY")
                                : ""
                        }}
                        —
                        {{
                            eventInfo
                                ? dayjs(eventInfo.end).format("DD.MM.YYYY")
                                : ""
                        }}
                    </span>
                    <span class="text-l mt-2">
                        {{ t("calendar.assigned_to_event") }}:
                        {{
                            eventInfo
                                ? eventInfo.all_vision
                                    ? t("calendar.all_vision_task_assigned")
                                    : t("calendar.department_assigned") +
                                      eventInfo.department.name
                                : ""
                        }}
                    </span>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close()">
                    {{ t("main.close_button") }}
                </v-btn>
                <v-btn
                    color="error"
                    variant="elevated"
                    @click="deleteButtonHandle"
                >
                    {{ t("main.delete_button") }}
                </v-btn>
                <v-btn color="warning" variant="elevated" @click="close()">
                    {{ t("main.edit_button") }}
                </v-btn>
                <v-btn
                    color="success"
                    variant="elevated"
                    style="text-transform: none"
                    @click="close()"
                >
                    {{ t("calendar.set_done") }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
