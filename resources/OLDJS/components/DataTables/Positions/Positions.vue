<template>
    <div v-if="!loadingTable">
        <div class="mb-5">
            <v-btn @click="openDialog()" prepend-icon="mdi-plus" color="green">
                Добавить должность
            </v-btn>
        </div>
        <PositionDialog
            @close-dialog="closeDialog"
            :isOpen="isDialogOpen"
            :edit-id="dialogEditId"
        ></PositionDialog>

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
            :items="positions"
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
import PositionDialog from "../Positions/PositionDialog.vue";
import AlertDialog from "../../UI/Alerts/AlertDangerDialog.vue";
import AcceptDialog from "../../UI/Alerts/AcceptDialog.vue";

const positions = ref([]);
const loadingTable = ref(true);

const headers = [
    { title: "ID", key: "id" },
    { title: "Название должности", key: "name" },
    { title: "Создан", key: "created_at" },
    { title: "Обновлен", key: "updated_at" },
    { title: "Действия", key: "actions", sortable: false, align: "center" },
];

const isDialogOpen = ref(false);
const dialogEditId = ref(null);

const requestData = function () {
    axios.get("/api/positions").then((response) => {
        console.log(response.data.data);
        positions.value = response.data.data;
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
    alertAcceptText.value = `Удалить должность ${name}?`;
    idToDelete.value = id;
};

const deleteRow = function (id) {
    let deleteIndex = positions.value.findIndex((item) => item.id === id);
    if (deleteIndex !== -1) {
        positions.value.splice(deleteIndex, 1);
    }

    axios
        .delete(`/api/positions/${id}`)
        .then((response) => {
            console.log(response);
            requestData();
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 409) {
                showAlertDialog.value = true;
                alertText.value =
                    "Невозможно удалить должность, пока есть люди с ней";
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
