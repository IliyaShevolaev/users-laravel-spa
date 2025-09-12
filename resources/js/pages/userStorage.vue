<script setup>
import axios from "axios";
import { ref, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../stores/auth";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import Snackbar from "../components/toaster/Snackbar.vue";
import { saveAs } from "file-saver";
import debounce from "lodash/debounce";

const { t } = useI18n();
const authStore = useAuthStore();

const props = defineProps({
    id: Number,
});

const documents = ref([]);
const headers = computed(() => {
    const base = [
        { title: "ID", key: "id" },
        { title: t("file_template.file_name"), key: "name" },
        { title: t("main.created"), key: "created_at" },
        {
            title: t("main.actions"),
            key: "actions",
            sortable: false,
            align: "center",
        }
    ];

    return base;
});

const itemsPerPage = ref(10);
const currentPage = ref(1);
const currentSortBy = ref([]);
const totalItems = ref(0);
const search = ref("");

const loadingTable = ref(false);
let abortController = null;

const requestData = function ({ page, itemsPerPage, sortBy }) {
    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();

    loadingTable.value = true;
    currentSortBy.value = sortBy;

    const params = {
        page: page,
        per_page: itemsPerPage,
        sort_by: sortBy.length ? sortBy[0].key : null,
        sort_order: sortBy.length ? sortBy[0].order : null,
        search: search.value,
    };

    axios
        .post(`/api/files/user/documents/datatable/${props.id}`, params, {
            signal: abortController.signal,
        })
        .then((response) => {
            console.log(response);
            documents.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
        })
        .catch((error) => {
            console.error(error);
            documents.value = [];
            totalItems.value = 0;
        })
        .finally(() => {
            loadingTable.value = false;
        });
};

const debouncedSearch = debounce(() => {
    requestData({
        page: 1,
        itemsPerPage: itemsPerPage.value,
        sortBy: currentSortBy.value,
    });
}, 500);

watch(search, () => {
    debouncedSearch();
});

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

const deleteRow = function (id) {
    axios
        .delete(`/api/files/user/documents/${id}`)
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
            showSnackBar(`${t("file_template.deleted")}`, "error");
        })
        .catch((error) => {
            console.error(error);
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
    formData.append("user_id", props.id);

    axios
        .post("/api/files/user/documents", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            event.target.value = "";
        });
};

const isSnackbarOpen = ref(false);
const snackbarMessage = ref("");
const snackbarColor = ref("");

const showSnackBar = function (message, color) {
    isSnackbarOpen.value = false;
    setTimeout(() => {
        snackbarMessage.value = message;
        snackbarColor.value = color;
        isSnackbarOpen.value = true;
    }, 10);
};
</script>

<template>
    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteRow(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    />

    <Snackbar
        :color="snackbarColor"
        :message="snackbarMessage"
        :is-open="isSnackbarOpen"
        @close-snackbar="isSnackbarOpen = false"
    />

    <div class="mb-5" v-if="authStore.checkPermission('users-create')">
        <v-btn @click="add" prepend-icon="ri-add-line" color="success">
            {{ $t("main.append_button") }}
        </v-btn>

        <input
            type="file"
            ref="fileInput"
            class="hidden"
            accept=".docx,.pdf"
            @change="handleFileChange"
        />
    </div>

    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="documents"
        :loading="loadingTable"
        @update:options="requestData"
    >
        <template v-slot:top>
            <div class="flex flex-row-reverse">
                <div class="w-1/3">
                    <v-text-field
                        v-model="search"
                        class="ma-2"
                        density="compact"
                        :placeholder="$t('main.search')"
                        hide-details
                        clearable
                    />
                </div>
            </div>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn
                icon="ri-download-line"
                class="me-3"
                size="small"
                color="blue"
                @click="downloadFile(item.id, item.name)"
            />
            <v-btn
                v-if="authStore.checkPermission('users-delete')"
                icon="ri-delete-bin-fill"
                class="me-3"
                size="small"
                color="error"
                @click="askToDeleteRow(item.id, item.file_name)"
            />
        </template>
    </v-data-table-server>
</template>
