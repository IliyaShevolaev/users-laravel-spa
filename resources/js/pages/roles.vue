<script setup>
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../stores/modelChanges";
import { useRouter } from "vue-router";
import debounce from "lodash/debounce";
import { useDisplay } from "vuetify";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import Snackbar from "../components/toaster/Snackbar.vue";
import { useAuthStore } from "../stores/auth";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";

const authStore = useAuthStore();
const { mobile } = useDisplay();
const router = useRouter();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();

const roles = ref([]);

const headers = computed(() => {
    const baseHeaders = [
        { title: "ID", key: "id" },
        { title: t("main.title"), key: "display_name" },
        { title: t("main.created"), key: "created_at" },
        { title: t("main.updated"), key: "updated_at" },
    ];

    if (
        authStore.checkPermission("roles-update") ||
        authStore.checkPermission("roles-delete")
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
    loadingTable.value = true;

    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();

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
        .post("/api/roles/datatable", params, {
            signal: abortController.signal,
        })
        .then((response) => {
            roles.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            console.log(error)
            if (error.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("main.no_permission");
            }
            if (error.status === 422) {
                showAlertDialog.value = true;
                alertText.value = t("main.bad_request");
            }
            console.error(error);
            roles.value = [];
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

const edit = function (id) {
    router.push(`/roles/edit/${Number(id)}`);
};

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");
const idToDelete = ref(0);

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `${t("users.roles.delete_role")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteRole(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/roles/${id}`)
        .then(() => {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
            showSnackBar(
                t("users.role") +
                    " " +
                    modelChangesStore.getRole.lastDelete +
                    " " +
                    t("users.roles.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value = t("users.roles.unable_to_delete");
            } else if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("users.roles.no_selected");
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

const checkHistoryStateToNotify = function () {
    const betweenPagesMethod = modelChangesStore.role.betweenPagesMethod;
    if (betweenPagesMethod === "create") {
        showSnackBar(
            t("users.role") +
                " " +
                modelChangesStore.getRole.lastAdd +
                " " +
                t("users.roles.was_append"),
            "success"
        );
    } else if (betweenPagesMethod === "update") {
        showSnackBar(
            t("users.role") +
                " " +
                modelChangesStore.getRole.lastEdit +
                " " +
                t("users.roles.was_edited"),
            "warning"
        );
    }
    modelChangesStore.unsetRoleBetweenPagesMethod();
};
checkHistoryStateToNotify();

const showAlertDialog = ref(false);
const alertText = ref("");
</script>
<template>
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

    <div class="mb-5">
        <v-btn
            v-if="authStore.checkPermission('roles-create')"
            @click="router.push('/roles/create')"
            prepend-icon="ri-add-line"
            color="success"
        >
            {{ $t("main.append_button") }}
        </v-btn>
    </div>

    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="roles"
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
                v-if="authStore.checkPermission('roles-update')"
                icon="ri-edit-line"
                color="warning"
                class="me-3"
                size="small"
                @click="edit(item.id)"
            ></v-btn>
            <v-btn
                v-if="authStore.checkPermission('roles-delete')"
                icon="ri-delete-bin-fill"
                color="error"
                class="me-3"
                size="small"
                @click="askToDeleteRow(item.id, item.display_name)"
            ></v-btn>
        </template>
    </v-data-table-server>
</template>

<style></style>
