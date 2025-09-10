<script setup>
import axios from "axios";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../stores/modelChanges";
import AlertDangerDialog from "../alerts/AlertDangerDialog.vue";
import { VFileUpload } from "vuetify/labs/VFileUpload";

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

const formDataObject = reactive({
    name: null,
    file: null
});

const formDataObjectErrors = reactive({});

const close = function (dataChanged, method) {
    clearFields(formDataObject);
    clearFields(formDataObjectErrors);
    emit("closeDialog", dataChanged, method);
};

const add = function () {
    const formData = new FormData();
    formData.append("name", formDataObject.name ?? "");
    formData.append("file_template", formDataObject.file);

    axios
        .post("/api/files/templates", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            modelChangesStore.addFileTemplate(formDataObject.name);
            close(true, "add");
        })
        .catch((error) => {
            clearFields(formDataObjectErrors);
            console.log(error);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataObjectErrors[error] = errors[error][0];
                }
                console.log(formDataObjectErrors);
            } else {
                console.log(error);
            }
        });
};

// const edit = function () {
//     clearFields(formDataObject);
//     axios
//         .get(`/api/file_templates/${props.editId}/edit`, formDataObject)
//         .then((response) => {
//             Object.keys(response.data.data).forEach((key) => {
//                 formDataObject[key] = response.data.data[key];
//             });
//         })
//         .catch((error) => {
//             if (error.status === 404) {
//                 showAlertDialog.value = true;
//                 alertText.value = t("users.file_templates.no_selected");
//                 close(false);
//             }
//             console.log(error);
//         });
// };

// const update = function (id) {
//     axios
//         .patch(`/api/file_templates/${id}`, formDataObject)
//         .then((response) => {
//             modelChangesStore.editDepartment(formDataObject.name);
//             close(true, "edit");
//         })
//         .catch((error) => {
//             clearFields(formDataObjectErrors);
//             if (error.response.status === 422) {
//                 const errors = error.response.data.errors;
//                 console.log(errors);
//                 for (error in errors) {
//                     formDataObjectErrors[error] = errors[error][0];
//                 }
//                 console.log(formDataObjectErrors);
//             } else {
//                 console.log(error);
//             }
//         });
// };

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formDataObject);
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
                <span class="headline flex justify-between">
                    {{
                        props.editId
                            ? t("users.file_templates.edit_button")
                            : t("users.file_templates.add_button")
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
                        v-model="formDataObject.name"
                        :error="!!formDataObjectErrors.name"
                        :error-messages="formDataObjectErrors.name"
                        :label="t('main.title')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="name"
                        outlined
                        validateOn="blur"
                    ></v-text-field>

                    <v-file-input
                        class="mt-2"
                        v-model="formDataObject.file"
                        :error="!!formDataObjectErrors.file_template"
                        :error-messages="formDataObjectErrors.file_template"
                        accept=".doc,.docx,.pdf"
                        :label="t('users.file_templates.upload_file')"
                        prepend-icon=""
                        density="default"
                        variant="underlined"
                        color="primary"
                        outlined
                        validateOn="blur"
                    ></v-file-input>
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
