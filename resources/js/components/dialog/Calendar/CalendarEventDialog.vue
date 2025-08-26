<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { computed, reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../../stores/modelChanges";
import AlertDangerDialog from "../../alerts/AlertDangerDialog.vue";
const modelChangesStore = useModelChangesStore();
import { useAuthStore } from "../../../stores/auth";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { useTheme } from "vuetify";

const vuetifyTheme = useTheme();
const { t } = useI18n();
const authStore = useAuthStore();

const formData = reactive({
    title: null,
    start: null,
    end: null,
    user_id: null,
    description: null,
    creator_id: null,
});

const users = ref([]);
const usersComputed = computed(() => {
    return users.value.map((user) => ({
        text: user.name,
        value: user.id,
    }));
});
const currentUser = ref(null);

const appendAllVissionUser = function () {
    users.value.unshift({
        id: -1,
        name: t("calendar.all_vision"),
    });
};

const blockUserSelect = ref(false);

const requestCreateUserData = function () {
    axios.get("/api/events/create").then((response) => {
        users.value = response.data.users;
        currentUser.value = response.data.user;

        if (response.data.users.length === 0) {
            formData.user_id = currentUser.value.id;
            blockUserSelect.value = true;
        } else {
            appendAllVissionUser();
        }

        currentUser.value.name = `Вы — ${currentUser.value.name}`;
        users.value.unshift(currentUser.value);
        formData.creator_id = currentUser.value.id;
    });
};

if (authStore.hasOneOfEachPermission("tasks-create")) {
    requestCreateUserData();
}

const props = defineProps({
    editId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
    choosenDate: String,
});
const emit = defineEmits(["closeDialog"]);

const formDataErrors = reactive({});

const dateRange = ref(null);
watch(dateRange, (newDate) => {
    if (newDate) {
        formData.start = dayjs(newDate[0]).format("YYYY-MM-DD HH:mm:ss");
        formData.end = dayjs(newDate[1]).format("YYYY-MM-DD HH:mm:ss");
    }
});

const close = function (dataChanged, method) {
    dateRange.value = null;
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", dataChanged, method);
};

const validatedBeforeRequest = function () {
    const result = { ...formData };

    if (result.user_id === -1) {
        result.user_id = users.value
            .map((user) => user.id)
            .filter((id) => id !== -1);
    } else {
        result.user_id = [result.user_id];
    }

    if (dayjs(formData.start).isAfter(dayjs(formData.end))) {
        formData.end = formData.start;
    }

    return result;
};

const setFullDateFromString = function (start, end) {
    dateRange.value = [
        dayjs(start).format("YYYY-MM-DD HH:mm"),
        dayjs(end).format("YYYY-MM-DD HH:mm"),
    ];
};

const add = function () {
    console.log(validatedBeforeRequest());
    axios
        .post("/api/events", validatedBeforeRequest())
        .then((response) => {
            modelChangesStore.addEvent(formData.title);
            close(true, "add");
        })
        .catch((error) => {
            clearFields(formDataErrors);
            console.log(error);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            } else {
                console.log(error);
            }
        });
};

const requestEditInfo = function () {
    clearFields(formData);
    axios
        .get(`/api/events/${props.editId}`)
        .then((response) => {
            console.log(response);
            Object.keys(response.data.data).forEach((key) => {
                formData[key] = response.data.data[key];
            });

            setFullDateFromString(
                response.data.data.start,
                response.data.data.end
            );

            if (response.data.data.assigned_users.length > 1) {
                formData.user_id = -1;
            } else {
                formData.user_id = response.data.data.assigned_users[0].id;
            }
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

const update = function (id) {
    axios
        .patch(`/api/events/${id}`, validatedBeforeRequest())
        .then((response) => {
            modelChangesStore.editEvent(formData.title);
            close(true, "edit");
        })
        .catch((error) => {
            clearFields(formDataErrors);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            } else {
                console.log(error);
            }
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            requestCreateUserData();
            if (props.editId !== null) {
                requestEditInfo();
            }

            if (props.choosenDate !== null) {
                console.log("props.choosenDate");
                console.log(props.choosenDate);
                setFullDateFromString(props.choosenDate, props.choosenDate);
            }
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        if (key === "creator_id") {
            return;
        }

        obj[key] = null;
    });
};

const showAlertDialog = ref(false);
const alertText = ref("");

const isUserSelectDisabled = computed(() => {
    return blockUserSelect.value;
});

const showMenu = ref(false);
</script>

<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

    <v-dialog
        v-model="props.isOpen"
        persistent
        max-width="600px"
        min-height="450"
    >
        <v-card>
            <v-card-title>
                <span class="headline flex justify-between mt-3">
                    {{
                        props.editId
                            ? t("calendar.edit_button")
                            : t("calendar.add_button")
                    }}

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
                <v-form @submit.prevent="add">
                    <v-text-field
                        v-model="formData.title"
                        :error="!!formDataErrors.title"
                        :error-messages="formDataErrors.title"
                        :label="t('main.title')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        outlined
                        validateOn="blur"
                    ></v-text-field>

                    <v-textarea
                        v-model="formData.description"
                        :label="t('calendar.event_description')"
                        :error="!!formDataErrors.description"
                        :error-messages="formDataErrors.description"
                        clearable
                        density="default"
                        variant="underlined"
                        color="primary"
                        outlined
                        rows="6"
                        validateOn="blur"
                    ></v-textarea>

                    <v-select
                        v-model="formData.user_id"
                        :items="usersComputed"
                        :error="!!formDataErrors['user_id.0']"
                        :error-messages="formDataErrors['user_id.0']"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('calendar.event_for')"
                        clearable
                        :disabled="isUserSelectDisabled"
                    ></v-select>

                    <p class="mt-5">{{ t("calendar.time_event") }}</p>
                    <VueDatePicker
                        :dark="vuetifyTheme.global.name.value === 'dark'"
                        v-model="dateRange"
                        locale="ru"
                        range
                        format="dd.MM.yyyy HH:mm"
                        :enable-time-picker="true"
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
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close(false)">{{
                    t("main.close_button")
                }}</v-btn>
                <v-btn
                    v-if="props.editId !== null"
                    variant="elevated"
                    color="warning"
                    text
                    @click="update(props.editId)"
                >
                    {{ t("main.edit_button") }}
                </v-btn>
                <v-btn
                    v-else
                    variant="elevated"
                    color="success"
                    text
                    @click="add"
                >
                    {{ t("main.append_button") }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style>
.dp__theme_light {
    --dp-background-color: #fff;
    --dp-text-color: #212121;
    --dp-hover-color: #f3f3f3;
    --dp-hover-text-color: #212121;
    --dp-hover-icon-color: #959595;
    --dp-primary-color: #7c4dff;
    --dp-primary-disabled-color: #a186ff;
    --dp-primary-text-color: #f8f5f5;
    --dp-secondary-color: #c0c4cc;
    --dp-border-color: #ddd;
    --dp-menu-border-color: #ddd;
    --dp-border-color-hover: #7c4dff;
    --dp-border-color-focus: #7c4dff;
    --dp-disabled-color: #f6f6f6;
    --dp-scroll-bar-background: #f3f3f3;
    --dp-scroll-bar-color: #959595;
    --dp-success-color: #76d275;
    --dp-success-color-disabled: #a3d9b1;
    --dp-icon-color: #959595;
    --dp-danger-color: #ff6f60;
    --dp-marker-color: #ff6f60;
    --dp-tooltip-color: #fafafa;
    --dp-disabled-color-text: #8e8e8e;
    --dp-highlight-color: rgb(25 118 210 / 10%);
    --dp-range-between-dates-background-color: var(--dp-hover-color, #f3f3f3);
    --dp-range-between-dates-text-color: var(--dp-hover-text-color, #212121);
    --dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
}

.dp__theme_dark {
    --dp-background-color: #312d4b;
    --dp-text-color: #e0d7f2;
    --dp-hover-color: #3a3150;
    --dp-hover-text-color: #ffffff;
    --dp-hover-icon-color: #b0a7d6;
    --dp-primary-color: #7c4dff;
    --dp-primary-disabled-color: #a186ff;
    --dp-primary-text-color: #ffffff;
    --dp-secondary-color: #9e9e9e;
    --dp-border-color: #3a3150;
    --dp-menu-border-color: #3a3150;
    --dp-border-color-hover: #7c4dff;
    --dp-border-color-focus: #7c4dff;
    --dp-disabled-color: #555072;
    --dp-disabled-color-text: #aaa0d4;
    --dp-scroll-bar-background: #1e1b2e;
    --dp-scroll-bar-color: #3a3150;
    --dp-success-color: #00e676;
    --dp-success-color-disabled: #66ffa6;
    --dp-icon-color: #b0a7d6;
    --dp-danger-color: #ff1744;
    --dp-marker-color: #ff1744;
    --dp-tooltip-color: #2b2440;
    --dp-highlight-color: rgba(124, 77, 255, 0.2);
    --dp-range-between-dates-background-color: var(--dp-hover-color);
    --dp-range-between-dates-text-color: var(--dp-text-color);
    --dp-range-between-border-color: var(--dp-primary-color);
}
</style>
