<script setup>
import { useAuthStore } from "../stores/auth";
import { useModelChangesStore } from "../stores/modelChanges";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();

import ImageDialog from "../components/dialog/Gallery/ImageDialog.vue";
import { ref } from "vue";
import axios from "axios";

const images = ref([]);
const imagesLoading = ref(false);

const requestStartData = function () {
    imagesLoading.value = true;
    axios.get("/api/images").then((response) => {
        console.log(response.data.data);
        images.value = response.data.data;
        imagesLoading.value = false;
    });
};
requestStartData();

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
            t("users.file_templates.file_template") +
                " " +
                modelChangesStore.getFileTemplate.lastAdd +
                " " +
                t("users.file_templates.was_append"),
            "success"
        );
    } else if (method === "edit") {
        showSnackBar(
            t("users.file_templates.file_template") +
                " " +
                modelChangesStore.getFileTemplate.lastEdit +
                " " +
                t("users.file_templates.was_edited"),
            "warning"
        );
    }

    isDialogOpen.value = false;
    dialogEditId.value = null;
};

const deleteImage = function(id) {
    console.log(id)
}
</script>

<template>
    <ImageDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></ImageDialog>

    <div class="mb-5" v-if="authStore.checkPermission('gallery-create')">
        <v-btn @click="openDialog()" prepend-icon="ri-add-line" color="success">
            {{ $t("main.append_button") }}
        </v-btn>
    </div>

    <div>
        <v-skeleton-loader
            v-if="imagesLoading"
            type="image"
        ></v-skeleton-loader>
        <v-carousel v-else height="400">
            <v-carousel-item v-for="image in images" :key="image.id">
                <v-card class="fill-height">
                    <v-img
                        :src="
                            image.imageUrl[Object.keys(image.imageUrl)[0]]
                                .original_url
                        "
                        class="fill-height"
                        cover
                    >
                        <div
                            class="d-flex flex-column justify-end fill-height"
                        >
                            <v-card-title class="!text-sky-50">
                                {{ image.name }}
                            </v-card-title>

                            <v-card-actions>
                                <v-btn
                                    color="primary"
                                    @click="editImage(image.id)"
                                    >Редактировать</v-btn
                                >
                                <v-btn
                                    color="error"
                                    @click="deleteImage(image.id)"
                                    variant="outlined"
                                    >Удалить</v-btn
                                >
                            </v-card-actions>
                        </div>
                    </v-img>
                </v-card>
            </v-carousel-item>
        </v-carousel>
    </div>
</template>
