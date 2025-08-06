<template>
    <div v-if="!loadingTable">
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

        <AlertDialog
            @close-dialog="showAlertDialog = false"
            :is-open="showAlertDialog"
            :message="alertText"
        ></AlertDialog>

        <AcceptDialog
            @close-dialog="showAlertAcceptDialog = false"
            @accept-action="deleteRow(idToDelete)"
            :is-open="showAlertAcceptDialog"
            :message="alertAcceptText"
        ></AcceptDialog>

        <v-data-table
            :headers="headers"
            :items="departments"
            :items-per-page="10"
            class="elevation-1"
        >
            <template v-slot:item.actions="{ item }">
                <v-btn
                    icon="mdi-pen"
                    class="me-3"
                    size="small"
                    color="yellow"
                    @click="edit(item.id)"
                >
                </v-btn>
                <v-btn
                    icon="mdi-delete"
                    class="me-3"
                    size="small"
                    color="red"
                    @click="askToDeleteRow(item.id, item.name)"
                >
                </v-btn>
            </template>
        </v-data-table>
    </div>
    <div v-else class="flex justify-center">
        <v-progress-circular
            color="primary"
            indeterminate
            :size="80"
        ></v-progress-circular>
    </div>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";
import DepartmentDialog from "./DepartmentDialog.vue";
import AlertDialog from "../../UI/Alerts/AlertDangerDialog.vue";
import AcceptDialog from "../../UI/Alerts/AcceptDialog.vue";

const departments = ref([]);
const loadingTable = ref(true);

const headers = [
    { title: "ID", key: "id" },
    { title: "Название отдела", key: "name" },
    { title: "Действия", key: "actions", sortable: false, align: "center" },
];

const isDialogOpen = ref(false);
const dialogEditId = ref(null);

const requestData = function () {
    axios.get("/api/departments").then((response) => {
        console.log(response.data.data);
        departments.value = response.data.data;
        loadingTable.value = false;
    });
};
requestData();

const openDialog = function (id = null) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
};

const closeDialog = function (dataChanged) {
    if (dataChanged) {
        requestData();
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
    alertAcceptText.value = `Удалить отдел ${name}?`;
    idToDelete.value = id;
};

const deleteRow = function (id) {
    let deleteIndex = departments.value.findIndex((item) => item.id === id);
    if (deleteIndex !== -1) {
        departments.value.splice(deleteIndex, 1);
    }

    axios
        .delete(`/api/departments/${id}`)
        .then((response) => {
            console.log(response);
            requestData();
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
            } else {
                console.log(error);
            }
            requestData();
        });
    showAlertAcceptDialog.value = false;
};

const showAlertDialog = ref(false);
const alertText = ref("");

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");
</script>
<style></style>
