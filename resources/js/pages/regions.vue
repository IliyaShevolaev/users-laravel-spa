<script setup>
import axios from "axios";
import { onMounted, ref, computed, watch } from "vue";
import RegionDialog from "../components/dialog/RegionDialog.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";
import Snackbar from "../components/toaster/Snackbar.vue";
import { useDisplay } from "vuetify";
import { useI18n } from "vue-i18n";
import debounce from "lodash/debounce";
import { useModelChangesStore } from "../stores/modelChanges";
import { useAuthStore } from "../stores/auth";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();
const { mobile } = useDisplay();

const regions = ref([]);

const headers = computed(() => {
    const baseHeaders = [
        { title: "ID", key: "id" },
        { title: t("main.title"), key: "name" },
        { title: t("main.created"), key: "created_at" },
        { title: t("main.updated"), key: "updated_at" },
    ];

    if (
        authStore.checkPermission("cities-update") ||
        authStore.checkPermission("cities-delete")
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

    axios
        .post("/api/regions/datatable", params, { signal: abortController.signal })
        .then((response) => {
            regions.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
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
            regions.value = [];
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

    if (method === "add") {
        showSnackBar(
            t("region") +
                " " +
                modelChangesStore.getRegion.lastAdd +
                " " +
                t("regions.was_append"),
            "success"
        );
    } else if (method === "edit") {
        showSnackBar(
            t("region") +
                " " +
                modelChangesStore.getRegion.lastEdit +
                " " +
                t("regions.was_edited"),
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
    alertAcceptText.value = `${t("regions.delete")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteRegion(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/regions/${id}`)
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
            showSnackBar(
                t("regions.region") +
                    " " +
                    modelChangesStore.getRegion.lastDelete +
                    " " +
                    t("regions.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value = t("regions.unable_to_delete");
            } else if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("regions.no_selected");
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
    <div class="mb-5" v-if="authStore.checkPermission('cities-create')">
        <v-btn @click="openDialog()" prepend-icon="ri-add-line" color="success">
            {{ $t("main.append_button") }}
        </v-btn>
    </div>
    <RegionDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></RegionDialog>

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
        :items="regions"
        :loading="loadingTable"
        @update:options="requestData"
    >
        <template v-slot:top>
            <div class="flex flex-row-reverse">
                <div :class="mobile ? 'w-full' : 'w-25'">
                    <v-text-field
                        v-model="search"
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
                v-if="authStore.checkPermission('cities-update')"
                icon="ri-edit-line"
                class="me-3"
                size="small"
                color="warning"
                @click="edit(item.id)"
            ></v-btn>
            <v-btn
                v-if="authStore.checkPermission('cities-delete')"
                icon="ri-delete-bin-fill"
                class="me-3"
                size="small"
                color="error"
                @click="askToDeleteRow(item.id, item.name)"
            ></v-btn>
        </template>
    </v-data-table-server>
</template>
