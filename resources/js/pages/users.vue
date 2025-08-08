<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import UserDialog from "../components/dialog/UserDialog.vue";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";
import { useDisplay } from "vuetify";
import { debounce } from "vuetify/lib/util/helpers.mjs";

const { mobile } = useDisplay();

const users = ref([]);

const headers = [
    { title: "ID", key: "id" },
    { title: "Имя", key: "name" },
    { title: "Почта", key: "email" },
    { title: "Пол", key: "gender" },
    { title: "Статус", key: "status" },
    { title: "Отдел", key: "department_id" },
    { title: "Должность", key: "position_id" },
    { title: "Создан", key: "created_at" },
    { title: "Обновлен", key: "updated_at" },
    { title: "Действия", key: "actions", sortable: false, align: "center" },
];

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
        .get("/api/users/datatable", { params })
        .then((response) => {
            console.log(response);
            users.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            console.error(error);
            users.value = [];
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

    isDialogOpen.value = false;
    dialogEditId.value = null;
};

const edit = function (id) {
    openDialog(id);
};

const idToDelete = ref(0);

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `Удалить должность ${name}?`;
    idToDelete.value = id;
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
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = "Отдел отсутствует";
            }
        });
    showAlertAcceptDialog.value = false;
};

const showAlertDialog = ref(false);
const alertText = ref("");

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");
</script>

<template>
    <div class="mb-5">
        <v-btn @click="openDialog()" prepend-icon="ri-add-line" color="green">
            Добавить пользователя
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

    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="users"
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
                        placeholder="Поиск"
                        hide-details
                        clearable
                    ></v-text-field>
                </div>
            </div>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn
                icon="ri-edit-line"
                class="me-3"
                size="small"
                @click="edit(item.id)"
            ></v-btn>
            <v-btn
                icon="ri-delete-bin-fill"
                class="me-3"
                size="small"
                @click="askToDeleteRow(item.id, item.name)"
            ></v-btn>
        </template>
    </v-data-table-server>
</template>

<style></style>
