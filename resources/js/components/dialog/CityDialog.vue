<script setup>
import axios from "axios";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../stores/modelChanges";
import AlertDangerDialog from "../alerts/AlertDangerDialog.vue";

const modelChangesStore = useModelChangesStore();
const { t } = useI18n();

const props = defineProps({
    editId: {
        type: Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    name: null,
    ip_start: null,
    ip_end: null,
    region_id: null,
});

const formDataErrors = reactive({});
const regions = ref([]);

const close = function (dataChanged, method) {
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", dataChanged, method);
};

const requestCreateData = function () {
    axios
        .get("/api/regions")
        .then((response) => {
            regions.value = response.data.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

const add = function () {
    axios
        .post("/api/cities", formData)
        .then(() => {
            modelChangesStore.addCity(formData.name);
            close(true, "add");
        })
        .catch((error) => {
            clearFields(formDataErrors);
            if (error.response?.status === 422) {
                const errors = error.response.data.errors;
                for (const errorKey in errors) {
                    formDataErrors[errorKey] = errors[errorKey][0];
                }
            }
        });
};

const edit = function () {
    clearFields(formData);
    axios
        .get(`/api/cities/${props.editId}/edit`)
        .then((response) => {
            console.log(response);
            Object.keys(response.data.data).forEach((key) => {
                formData[key] = response.data.data[key];

                if (key === 'region' && response.data.data[key] !== null) {
                    formData.region_id = response.data.data[key].id;
                }
            });
        })
        .catch((error) => {
            if (error.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("cities.no_selected");
                close(false);
            }
        });
};

const update = function (id) {
    axios
        .patch(`/api/cities/${id}`, formData)
        .then(() => {
            modelChangesStore.editCity(formData.name);
            close(true, "edit");
        })
        .catch((error) => {
            clearFields(formDataErrors);
            if (error.response?.status === 422) {
                const errors = error.response.data.errors;
                for (const errorKey in errors) {
                    formDataErrors[errorKey] = errors[errorKey][0];
                }
            }
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            requestCreateData();
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
};

const showAlertDialog = ref(false);
const alertText = ref("");
</script>

<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    />

    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline flex justify-between">
                    {{
                        props.editId
                            ? t("cities.edit_button")
                            : t("cities.add_button")
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
                        v-model="formData.name"
                        :error="!!formDataErrors.name"
                        :error-messages="formDataErrors.name"
                        :label="t('main.title')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="name"
                        outlined
                        validate-on="blur"
                    />

                    <v-text-field
                        v-model="formData.ip_start"
                        :error="!!formDataErrors.ip_start"
                        :error-messages="formDataErrors.ip_start"
                        label="IP Start"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="ip_start"
                        outlined
                    />

                    <v-text-field
                        v-model="formData.ip_end"
                        :error="!!formDataErrors.ip_end"
                        :error-messages="formDataErrors.ip_end"
                        label="IP End"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="ip_end"
                        outlined
                    />

                    <v-select
                        v-model="formData.region_id"
                        :items="regions"
                        item-title="name"
                        item-value="id"
                        :error="!!formDataErrors.region_id"
                        :error-messages="formDataErrors.region_id"
                        label="Регион"
                        density="default"
                        variant="underlined"
                        color="primary"
                        outlined
                    />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close(false)">
                    {{ t("main.close_button") }}
                </v-btn>
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
