<template>
    <div v-if="!loadingTable">
        <div class="mb-5">
            <v-btn @click="openDialog" prepend-icon="mdi-plus" color="green">
                Добавить отдел
            </v-btn>
            <DepartmentDialog
                @close-dialog="closeDialog"
                :isOpen="isDialogOpen"
            ></DepartmentDialog>
        </div>

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
                    @click="showId(item)"
                >
                </v-btn>
                <v-btn
                    icon="mdi-delete"
                    size="small"
                    color="red"
                    @click="showId(item)"
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

const departments = ref([]);
const loadingTable = ref(true);

const headers = [
    { title: "ID", key: "id" },
    { title: "Название отдела", key: "name" },
    { title: "Действия", key: "actions", sortable: false, align: "center" },
];

const requestData = function () {
    axios.get("/api/departments").then((response) => {
        console.log(response.data.data);
        departments.value = response.data.data;
        loadingTable.value = false;
    });
};
requestData();

const showId = function (id) {
    console.log(id);
};

const isDialogOpen = ref(false);

const openDialog = function () {
    isDialogOpen.value = true;
};

const closeDialog = function (dataChanged) {
    if (dataChanged) {
        requestData();
    }
    isDialogOpen.value = false;
};
</script>
<style></style>
