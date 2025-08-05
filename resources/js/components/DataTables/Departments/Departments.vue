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
        ></AlertDialog>

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
                    @click="deleteRow(item.id)"
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
import AlertDialog from "./AlertDialog.vue";

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

const deleteRow = function (id) {
    axios
        .delete(`/api/departments/${id}`)
        .then((response) => {
            console.log(response);
            requestData();
        })
        .catch((error) => {
            showAlertDialog.value = true
        });
};
// @todo: прятать пока запрос идет, alert при 409
const showAlertDialog = ref(false);
</script>
<style></style>
