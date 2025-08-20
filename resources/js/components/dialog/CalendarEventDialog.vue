<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../stores/modelChanges";
import AlertDangerDialog from "../alerts/AlertDangerDialog.vue";
import { VDateInput } from "vuetify/labs/VDateInput";

const modelChangesStore = useModelChangesStore();

const { t } = useI18n();

const departments = ref([]);
const userDepartmentsComputed = computed(() => {
    return departments.value.map((department) => ({
        text: department.name,
        value: department.id,
    }));
});

const requestCreateUserData = function () {
    axios.get("/api/departments").then((response) => {
        departments.value = response.data.data;
    });
};
requestCreateUserData();

const props = defineProps({
    editId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    title: null,
    start: null,
    end: null,
    department_id: null,
});

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
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", dataChanged, method);
};

const add = function () {
    console.log(formData);
    axios
        .post("/api/events", formData)
        .then((response) => {
           // modelChangesStore.addPosition(formData.name);
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

const edit = function () {
    // clearFields(formData);
    // axios
    //     .get(`/api/positions/${props.editId}/edit`, formData)
    //     .then((response) => {
    //         Object.keys(response.data.data).forEach((key) => {
    //             formData[key] = response.data.data[key];
    //         });
    //     })
    //     .catch((error) => {
    //         if (error.status === 404) {
    //             showAlertDialog.value = true;
    //             alertText.value = t("users.positions.no_selected");
    //             close(false);
    //         }
    //         console.log(error);
    //     });
};

const update = function (id) {
    // axios
    //     .patch(`/api/positions/${id}`, formData)
    //     .then((response) => {
    //         modelChangesStore.editPosition(formData.name);
    //         close(true, "edit");
    //     })
    //     .catch((error) => {
    //         clearFields(formDataErrors);
    //         if (error.response.status === 422) {
    //             const errors = error.response.data.errors;
    //             console.log(errors);
    //             for (error in errors) {
    //                 formDataErrors[error] = errors[error][0];
    //             }
    //             console.log(formDataErrors);
    //         } else {
    //             console.log(error);
    //         }
    //     });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            if (props.editId !== null) {
                edit();
            }
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = null;
    });
    dateRange.value = null
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
                        :label="t('users.department')"
                        clearable
                    ></v-select>

                    <v-date-input
                        class="mt-2"
                        v-model="dateRange"
                        :label="t('calendar.select_date_range')"
                        :placeholder="t('calendar.date_placeholder')"
                        multiple="range"
                        clearable
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
