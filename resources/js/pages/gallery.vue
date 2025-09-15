<script setup>
import { useAuthStore } from "../stores/auth";
import { useModelChangesStore } from "../stores/modelChanges";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();

import ImageDialog from "../components/dialog/Gallery/ImageDialog.vue";
import { ref } from "vue";
import axios from "axios";
import ViewImage from "../components/dialog/Gallery/ViewImage.vue";

const images = ref([]);
const imagesLoading = ref(false);

const currentSlide = ref(0);
const currentImage = computed(() => images.value[currentSlide.value]);

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

const isImageDialogOpen = ref(false);

const showImage = function () {
    isImageDialogOpen.value = true;
};

const closeImage = function (id) {
    isImageDialogOpen.value = false;
};

const editImage = function (id) {
    console.log(id);
};

const deleteImage = function (id) {
    console.log(id);
};
</script>

<template>
    <ImageDialog
        @close-dialog="closeDialog"
        :isOpen="isDialogOpen"
        :edit-id="dialogEditId"
    ></ImageDialog>

    <ViewImage
        @close-dialog="closeImage"
        :isOpen="isImageDialogOpen"
        :image-url="
            currentImage
                ? currentImage.imageUrl[Object.keys(currentImage.imageUrl)[0]]
                      .original_url
                : ''
        "
    ></ViewImage>

    <div class="mb-5" v-if="authStore.checkPermission('gallery-create')">
        <v-btn @click="openDialog()" prepend-icon="ri-add-line" color="success">
            {{ $t("main.append_button") }}
        </v-btn>
    </div>

    <div>
        <v-skeleton-loader v-if="imagesLoading" type="card"></v-skeleton-loader>

        <v-card v-else max-width="350" class="rounded-lg px-4 pt-4">
            <v-carousel
                v-model="currentSlide"
                show-arrows="hover"
                hide-delimiter-background
                height="300"
            >
                <v-carousel-item v-for="image in images" :key="image.id">
                    <v-img
                        :src="
                            image.imageUrl[Object.keys(image.imageUrl)[0]]
                                .preview_url
                        "
                        @click="showImage()"
                        cover
                        class="fill-height rounded"
                    >
                        <div class="d-flex justify-end pa-2">
                            <v-menu location="bottom end">
                                <template #activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="ri-information-2-line"
                                        variant="text"
                                        size="small"
                                        color="white"
                                    />
                                </template>

                                <v-list>
                                    <v-list-item @click="editImage(image.id)">
                                        <template #prepend>
                                            <v-icon
                                                icon="ri-edit-line"
                                            ></v-icon>
                                        </template>
                                        <v-list-item-title
                                            >Редактировать</v-list-item-title
                                        >
                                    </v-list-item>

                                    <v-list-item @click="deleteImage(image.id)">
                                        <template #prepend>
                                            <v-icon
                                                icon="ri-delete-bin-line"
                                            ></v-icon>
                                        </template>
                                        <v-list-item-title>
                                            Удалить
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </v-img>
                </v-carousel-item>
            </v-carousel>

            <v-card-title class="text-h6 font-weight-medium">
                {{ currentImage.name }}
            </v-card-title>

            <v-card-actions>
                <v-btn variant="tonal" color="primary" @click="addImage">
                    Добавить
                </v-btn>
                <v-spacer />
                <v-btn variant="outlined" color="error" @click="clearAll">
                    Очистить
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
