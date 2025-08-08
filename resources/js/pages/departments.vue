<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import DepartmentDialog from "../components/dialog/DepartmentDialog.vue";
// import AlertDialog from "../../UI/Alerts/AlertDangerDialog.vue";
// import AcceptDialog from "../../UI/Alerts/AcceptDialog.vue";
import { useDisplay } from "vuetify";

const { mobile } = useDisplay();

const departments = ref([]);

const headers = [
    { title: "ID", key: "id" },
    { title: "Название отдела", key: "name" },
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
        .get("/api/departments/datatable", { params })
        .then((response) => {
            console.log(response);
            departments.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            console.error(error);
            console.error("!!!!!!!!!!!!!");
            departments.value = [];
            totalItems.value = 0;
        })
        .finally(() => {
            loadingTable.value = false;
        });
};

watch(
    () => search,
    (newValue, oldValue) => {
        if (newValue === "") {
            requestData({
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: currentSortBy.value,
            });
        }
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

    // if (method === "add") {
    //     console.log("ADD");
    //     totalItems.value += 1;
    // }

    isDialogOpen.value = false;
    dialogEditId.value = null;
};

const edit = function (id) {
    openDialog(id);
};

const idToDelete = ref(0);

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `Удалить отдел ${name}?`;
    idToDelete.value = id;
};

const deleteRow = function (id) {
    axios
        .delete(`/api/departments/${id}`)
        .then(() => {
            requestData({ page: page.value, itemsPerPage: itemsPerPage.value });
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value =
                    "Невозможно удалить отдел, пока есть люди прикрепленные к нему";
            } else if (error.response.status === 404) {
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
        <v-btn @click="openDialog()" prepend-icon="mdi-plus" color="green">
            Добавить отдел
        </v-btn>
    </div>
    <DepartmentDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></DepartmentDialog>

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
                        v-model="search"
                        class="ma-2"
                        density="compact"
                        placeholder="Search name..."
                        hide-details
                        clearable
                    ></v-text-field>
                </div>
            </div>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn
                icon="mdi-pen"
                class="me-3"
                size="small"
                color="yellow"
                @click="edit(item.id)"
                >EDIT</v-btn
            >
            <v-btn
                icon="mdi-delete"
                class="me-3"
                size="small"
                color="red"
                @click="askToDeleteRow(item.id, item.name)"
                >DEL</v-btn
            >
        </template>
    </v-data-table-server>
</template>

<style></style>
