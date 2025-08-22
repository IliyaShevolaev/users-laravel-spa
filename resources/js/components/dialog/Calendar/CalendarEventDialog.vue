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

const departments = ref([]);
const userDepartmentsComputed = computed(() => {
    return departments.value.map((department) => ({
        text: department.name,
        value: department.id,
    }));
});

const appendAllVissionDepartment = function () {
    departments.value.push({
        id: -1,
        name: t("calendar.all_vision"),
    });
};

const requestCreateUserData = function () {
    axios.get("/api/events/create").then((response) => {
        console.log(response.data);
        departments.value = response.data.departments;
        if (authStore.checkPermission("tasks-createAll")) {
            appendAllVissionDepartment();
        } else {
            formData.department_id = departments.value[0].id;
            console.log(departments.value[0]);
            console.log(formData);
        }
    });
};
if (
    authStore.hasOneOfEachPermission(
        "tasks-createDepartment",
        "tasks-createAll"
    )
) {
    requestCreateUserData();
}

const props = defineProps({
    editId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
    dialogViewMode: {
        type: Boolean,
        default: false,
    },
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    title: null,
    start: null,
    end: null,
    department_id: null,
    all_vision: false,
});

const formDataErrors = reactive({});

const dateRange = ref(null);
watch(dateRange, (newDate) => {
    if (newDate && newDate.length > 0) {
        formData.start = dayjs(newDate[0]).format("YYYY-MM-DD");
        formData.end = dayjs(newDate[newDate.length - 1])
            .format("YYYY-MM-DD");
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

    if (result.department_id === -1) {
        result.department_id = null;
        result.all_vision = true;
    } else {
        result.all_vision = false;
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
                dayjs(response.data.data.end)
                    .format("YYYY-MM-DD"),
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
            if (props.editId !== null) {
                requestEditInfo();
            }
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        if (key === "department_id" && lockDepartmentSelect.value) {
            return;
        }
        obj[key] = null;
    });
};

const showAlertDialog = ref(false);
const alertText = ref("");
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
                        name="name"
                        outlined
                        validateOn="blur"
                        :disabled="props.dialogViewMode"
                    ></v-text-field>

                    <v-select
                        v-model="formData.department_id"
                        :items="userDepartmentsComputed"
                        :error="!!formDataErrors.department_id"
                        :error-messages="formDataErrors.department_id"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('calendar.event_for')"
                        clearable
                        :disabled="lockDepartmentSelect || props.dialogViewMode"
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
                        :disabled="props.dialogViewMode"
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
