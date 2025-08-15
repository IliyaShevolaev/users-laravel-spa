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
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    name: null,
});

const formDataErrors = reactive({});

const close = function (dataChanged, method) {
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", dataChanged, method);
};

const add = function () {
    axios
        .post("/api/positions", formData)
        .then((response) => {
            modelChangesStore.addPosition(formData.name);
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
    clearFields(formData);
    axios
        .get(`/api/positions/${props.editId}/edit`, formData)
        .then((response) => {
            Object.keys(response.data.data).forEach((key) => {
                formData[key] = response.data.data[key];
            });
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
        .patch(`/api/positions/${id}`, formData)
        .then((response) => {
            modelChangesStore.editPosition(formData.name);
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
    ></AlertDangerDialog>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">{{
                    props.editId
                        ? t("users.positions.edit_button")
                        : t("users.positions.add_button")
                }}</span>
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
                        validateOn="blur"
                    ></v-text-field>
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
