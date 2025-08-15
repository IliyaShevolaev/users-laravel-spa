<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import DepartmentDialog from "../components/dialog/DepartmentDialog.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";
import Snackbar from "../components/toaster/Snackbar.vue";
import { useDisplay } from "vuetify";
import { debounce } from "vuetify/lib/util/helpers.mjs";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../stores/modelChanges";
import { useAuthStore } from "../stores/auth";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();
const { mobile } = useDisplay();

const departments = ref([]);

const headers = computed(() => {
    const baseHeaders = [
        { title: "ID", key: "id" },
        { title: t("main.title"), key: "name" },
        { title: t("main.created"), key: "created_at" },
        { title: t("main.updated"), key: "updated_at" },
    ];

    if (
        authStore.checkPermission("departments-update") ||
        authStore.checkPermission("departments-delete")
    ) {
        baseHeaders.push({
            title: t("main.actions"),
            key: "actions",
            sortable: false,
            align: "center",
        });
    }

    return baseHeaders;
});

const itemsPerPage = ref(10);
const currentPage = ref(1);
const currentSortBy = ref([]);
const totalItems = ref(0);
const search = ref("");

const loadingTable = ref(false);

const requestData = function ({ page, itemsPerPage, sortBy }) {
    if (loadingTable.value) {
        return;
    }

    loadingTable.value = true;
    currentSortBy.value = sortBy;

    const params = {
        page: page,
        per_page: itemsPerPage,
        sort_by: sortBy.length ? sortBy[0].key : null,
        sort_order: sortBy.length ? sortBy[0].order : null,
        search: search.value,
    };

    console.log("Request:", params);

    axios
        .get("/api/departments/datatable", { params })
        .then((response) => {
            console.log(response);
            departments.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            if (error.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("main.no_permission");
            }
            console.error(error);
            departments.value = [];
            totalItems.value = 0;
        })
        .finally(() => {
            loadingTable.value = false;
        });
};

const debouncedSearch = debounce((newValue) => {
    if (newValue === "" || newValue.length >= 3) {
        requestData({
            page: 1,
            itemsPerPage: itemsPerPage.value,
            sortBy: currentSortBy.value,
        });
    }
}, 1000);

watch(
    () => search.value,
    (newValue) => {
        debouncedSearch(newValue);
    }
);

const isDialogOpen = ref(false);
const dialogEditId = ref(null);

const openDialog = function (id = null) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
};

const closeDialog = function (dataChanged, method) {
    if (dataChanged) {
        requestData({
            page: currentPage.value,
            itemsPerPage: itemsPerPage.value,
            sortBy: currentSortBy.value,
        });
    }

    if (method === "add") {
        showSnackBar(
            t("users.department") +
                " " +
                modelChangesStore.getDepartment.lastAdd +
                " " +
                t("users.departments.was_append"),
            "success"
        );
    } else if (method === "edit") {
        showSnackBar(
            t("users.department") +
                " " +
                modelChangesStore.getDepartment.lastEdit +
                " " +
                t("users.departments.was_edited"),
            "warning"
        );
    }

    isDialogOpen.value = false;
    dialogEditId.value = null;
};

const edit = function (id) {
    openDialog(id);
};

const idToDelete = ref(0);

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `${t("users.departments.delete")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteDepartment(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/departments/${id}`)
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
            showSnackBar(
                t("users.department") +
                    " " +
                    modelChangesStore.getDepartment.lastDelete +
                    " " +
                    t("users.departments.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value = t("users.departments.unable_to_delete");
            } else if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("users.departments.no_selected");
            }
        });
    showAlertAcceptDialog.value = false;
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

const showAlertDialog = ref(false);
const alertText = ref("");

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");
</script>

<template>
    <div class="mb-5" v-if="authStore.checkPermission('departments-create')">
        <v-btn @click="openDialog()" prepend-icon="ri-add-line" color="success">
            {{ $t("main.append_button") }}
        </v-btn>
    </div>
    <DepartmentDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></DepartmentDialog>

    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteRow(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    ></AcceptDialog>

    <Snackbar
        :color="snackbarColor"
        :message="snackbarMessage"
        :is-open="isSnackbarOpen"
        @close-snackbar="isSnackbarOpen = false"
    ></Snackbar>

    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="departments"
        :loading="loadingTable"
        :search="search"
        @update:options="requestData"
    >
        <template v-slot:top>
            <div class="flex flex-row-reverse">
                <div :class="mobile ? 'w-full' : 'w-25'">
                    <v-text-field
                        v-model.lazy="search"
                        class="ma-2"
                        density="compact"
                        :placeholder="$t('main.search')"
                        hide-details
                        clearable
                    ></v-text-field>
                </div>
            </div>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn
                v-if="authStore.checkPermission('departments-update')"
                icon="ri-edit-line"
                class="me-3"
                size="small"
                color="warning"
                @click="edit(item.id)"
            ></v-btn>
            <v-btn
                v-if="authStore.checkPermission('departments-delete')"
                icon="ri-delete-bin-fill"
                class="me-3"
                size="small"
                color="error"
                @click="askToDeleteRow(item.id, item.name)"
            ></v-btn>
        </template>
    </v-data-table-server>
</template>

<style></style>
