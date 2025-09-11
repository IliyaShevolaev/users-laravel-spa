<script setup>
import axios from "axios";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../../../stores/auth";
import { saveAs } from "file-saver";
import AcceptDialog from "../../alerts/AcceptDialog.vue";

const { t } = useI18n();
const authStore = useAuthStore();

const props = defineProps({
    userId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({});

const formDataErrors = reactive({});

const close = function () {
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog");
};

const loading = ref(false);
const documents = ref([]);

const requestStartData = function () {
    loading.value = true;
    axios
        .get(`/api/files/user/documents/by-user/${props.userId}`)
        .then((response) => {
            console.log(response);
            documents.value = response.data.documents;
        })
        .finally(() => {
            loading.value = false;
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            requestStartData();
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = null;
    });
};

let abortDownloadController = null;

const downloadFile = function (id, fileName) {
    if (abortDownloadController) {
        abortDownloadController.abort();
    }
    abortDownloadController = new AbortController();

    axios
        .get(`/api/files/user/documents/${id}`, {
            responseType: "blob",
            signal: abortDownloadController.signal,
        })
        .then((response) => {
            saveAs(response.data, fileName);
        });
};

const idToDelete = ref(0);
const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `${t("file_template.delete")} ${name}?`;
    idToDelete.value = id;
};

const deleteDocument = function (id) {
    axios
        .delete(`/api/files/user/documents/${id}`)
        .then((response) => {
            requestStartData();
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            showAlertAcceptDialog.value = false;
        });
};

const fileInput = ref(null);

const add = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("file", file);
    formData.append("user_id", props.userId);

    axios
        .post("/api/files/user/documents", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(() => {
            requestStartData();
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            event.target.value = "";
        });
};
</script>

<template>
    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteDocument(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    ></AcceptDialog>

    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-skeleton-loader v-if="loading" type="card"></v-skeleton-loader>
        <v-card v-else>
            <v-card-title>
                <span class="headline flex justify-between">
                    {{ t("file_template.user_documents") }}
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
                <div v-for="(document, index) in documents" :key="document.id">
                    <div class="py-2 flex items-center justify-between">
                        <div
                            class="flex flex-between text-gray-800 font-medium"
                        >
                            {{ document.fileName }}
                        </div>
                        <div>
                            <v-btn
                                icon="ri-download-line"
                                class="me-3"
                                size="small"
                                color="blue"
                                @click="
                                    downloadFile(document.id, document.fileName)
                                "
                            ></v-btn>
                            <v-btn
                                v-if="authStore.checkPermission('users-delete')"
                                icon="ri-delete-bin-fill"
                                class="me-3"
                                color="error"
                                size="small"
                                @click="
                                    askToDeleteRow(
                                        document.id,
                                        document.fileName
                                    )
                                "
                            ></v-btn>
                        </div>
                    </div>

                    <v-divider
                        v-if="index < documents.length - 1"
                        class="my-1"
                    />
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close()">
                    {{ t("main.close_button") }}
                </v-btn>
                <v-btn variant="elevated" color="success" text @click="add">
                    {{ t("main.append_button") }}
                </v-btn>

                <input
                    type="file"
                    ref="fileInput"
                    class="hidden"
                    accept=".docx,.pdf"
                    @change="handleFileChange"
                />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
