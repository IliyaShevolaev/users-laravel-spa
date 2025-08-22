<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { computed, reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../../stores/modelChanges";
import AlertDangerDialog from "../../alerts/AlertDangerDialog.vue";
import { VDateInput } from "vuetify/labs/VDateInput";
const modelChangesStore = useModelChangesStore();
import { useAuthStore } from "../../../stores/auth";
import ViewCalendarEventDialog from "./ViewCalendarEventDialog.vue";

const { t } = useI18n();
const authStore = useAuthStore();

const formData = reactive({
    title: null,
    start: null,
    end: null,
    user_id: null,
    description: null,
    creator_id: null
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
});
const emit = defineEmits(["closeDialog"]);

const formDataErrors = reactive({});

const dateRange = ref(null);
watch(dateRange, (newDate) => {
    if (newDate && newDate.length > 0) {
        formData.start = dayjs(newDate[0]).format("YYYY-MM-DD");
        formData.end = dayjs(newDate[newDate.length - 1]).format("YYYY-MM-DD");
    } else {
        formData.start = null;
        formData.end = null;
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
        result.user_id = [currentUser.value.id];
    }

    return result;
};

const lockDepartmentSelect = computed(() => {
    return !authStore.checkPermission("tasks-createAll");
});

const add = function () {
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
                if (key === "department" && response.data.data[key]) {
                    formData.department_id = response.data.data[key].id;
                }

                if (key === "all_vision" && response.data.data[key]) {
                    formData.department_id = -1;
                }
            });
            dateRange.value = [
                dayjs(response.data.data.start).format("YYYY-MM-DD"),
                dayjs(response.data.data.end).format("YYYY-MM-DD"),
            ];
            console.log(formData);
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
            requestCreateUserData()
            if (props.editId !== null) {
                requestEditInfo();
            }
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = null;
    });
};

const showAlertDialog = ref(false);
const alertText = ref("");

const isUserSelectDisabled = computed(() => {
    return blockUserSelect.value;
});
</script>

<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline flex justify-between">
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
                        validateOn="blur"
                    ></v-textarea>

                    <v-select
                        v-model="formData.user_id"
                        :items="usersComputed"
                        :error="!!formDataErrors.department_id"
                        :error-messages="formDataErrors.department_id"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('calendar.event_for')"
                        clearable
                        :disabled="isUserSelectDisabled"
                    ></v-select>

                    <v-date-input
                        class="mt-2"
                        v-model="dateRange"
                        :error="!!formDataErrors.start"
                        :error-messages="formDataErrors.start"
                        :label="t('calendar.select_date_range')"
                        :placeholder="t('calendar.date_placeholder')"
                        multiple="range"
                        clearable
                        variant="underlined"
                        prepend-icon=""
                    ></v-date-input>
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
