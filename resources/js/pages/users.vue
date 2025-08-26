<script setup>
import axios from "axios";
import { computed, ref } from "vue";
import UserDialog from "../components/dialog/UserDialog.vue";
import Snackbar from "../components/toaster/Snackbar.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import debounce from "lodash/debounce";
import { useI18n } from "vue-i18n";
import { useDisplay } from "vuetify";
import { useModelChangesStore } from "../stores/modelChanges";
import { useAuthStore } from "../stores/auth";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();
const { mobile } = useDisplay();
const router = useRouter();

const users = ref([]);

const headers = computed(() => {
    const baseHeaders = [
        { title: "ID", key: "id" },
        { title: t("users.name"), key: "name" },
        { title: t("users.email"), key: "email" },
        { title: t("users.gender"), key: "gender" },
        { title: t("users.status"), key: "status" },
    ];

    if (authStore.checkPermission("roles-read")) {
        baseHeaders.push({ title: t("users.role"), key: "roles" });
    }

    baseHeaders.push(
        { title: t("users.department"), key: "department_id" },
        { title: t("users.position"), key: "position_id" },
        { title: t("main.created"), key: "created_at" },
        { title: t("main.updated"), key: "updated_at" }
    );

    if (
        authStore.checkPermission("users-update") ||
        authStore.checkPermission("users-delete")
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

    console.log("Request:", params);

    axios
        .post("/api/users/datatable", params, {
            signal: abortController.signal,
        })
        .then((response) => {
            console.log(response);
            users.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            if (error.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("main.no_permission");
            }
            if (error.status === 422) {
                showAlertDialog.value = true;
                alertText.value = t("main.bad_request");
            }
            console.error(error);
            users.value = [];
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

    console.log("here");
    console.log(method);
    if (method === "add") {
        console.log("add");
        showSnackBar(
            t("users.user") +
                " " +
                modelChangesStore.getUser.lastAdd +
                " " +
                t("users.was_append"),
            "success"
        );
    } else if (method === "edit") {
        console.log("edit");

        showSnackBar(
            t("users.user") +
                " " +
                modelChangesStore.getUser.lastEdit +
                " " +
                t("users.was_edited"),
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
    alertAcceptText.value = `${t("users.delete")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteUser(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/users/${id}`)
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
            showSnackBar(
                t("users.user") +
                    " " +
                    modelChangesStore.getUser.lastDelete +
                    " " +
                    t("users.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("users.no_selected");
            } else if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value = t("users.delete_itself");
            } else if (error.response.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("main.unable_system");
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

const viewLog = function (id) {
    router.push({
        path: `/activity-logs/${id}`,
    });
};
</script>

<template>
    <div class="mb-5">
        <v-btn
            v-if="authStore.checkPermission('users-create')"
            @click="openDialog()"
            prepend-icon="ri-add-line"
            color="success"
        >
            {{ t("main.append_button") }}
        </v-btn>
    </div>
    <UserDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></UserDialog>

    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteRow(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    ></AcceptDialog>

    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>

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
        :items="users"
        :loading="loadingTable"
        @update:options="requestData"
    >
        <template v-slot:top>
            <div
                v-if="authStore.checkPermission('users-find')"
                class="flex flex-row-reverse"
            >
                <div :class="mobile ? 'w-full' : 'w-25'">
                    <v-text-field
                        v-model="search"
                        class="ma-2"
                        density="compact"
                        :placeholder="t('main.search')"
                        hide-details
                        clearable
                    ></v-text-field>
                </div>
            </div>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn
                v-if="authStore.checkPermission('users-update')"
                icon="ri-news-line"
                class="me-3 !bg-teal-500"
                size="small"
                @click="viewLog(item.id)"
            ></v-btn>
            <v-btn
                v-if="authStore.checkPermission('users-update')"
                icon="ri-edit-line"
                class="me-3"
                color="warning"
                size="small"
                @click="edit(item.id)"
            ></v-btn>
            <v-btn
                v-if="authStore.checkPermission('users-delete')"
                icon="ri-delete-bin-fill"
                class="me-3"
                color="error"
                size="small"
                @click="askToDeleteRow(item.id, item.name)"
            ></v-btn>
        </template>
    </v-data-table-server>
</template>

<style></style>
