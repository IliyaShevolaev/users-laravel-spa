<script setup>
import { useAuthStore } from "../stores/auth";
import { useModelChangesStore } from "../stores/modelChanges";
import Snackbar from "../components/toaster/Snackbar.vue";
import { useI18n } from "vue-i18n";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();

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

const visibleIndicators = computed(() => {
    const total = images.value.length;
    const current = currentSlide.value;
    const indicators = new Set();

    if (total > 0) {
        indicators.add(0);
        indicators.add(total - 1);
    }

    for (let i = current - 2; i <= current + 2; i++) {
        if (i > 0 && i < total - 1) {
            indicators.add(i);
        }
    }

    return Array.from(indicators).sort((a, b) => a - b);
});

const isDialogOpen = ref(false);
const dialogEditId = ref(null);

const openDialog = function (id = null) {
    isDialogOpen.value = true;
    dialogEditId.value = id;
};

const closeDialog = function (dataChanged, method) {
    if (dataChanged) {
        requestStartData();
    }

    if (method === "add") {
        showSnackBar(
            t("gallery.image") +
                " " +
                modelChangesStore.getImage.lastAdd +
                " " +
                t("gallery.was_append"),
            "success"
        );
    } else if (method === "edit") {
        showSnackBar(
            t("gallery.image") +
                " " +
                modelChangesStore.getImage.lastEdit +
                " " +
                t("gallery.was_edited"),
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

    <Snackbar
        :color="snackbarColor"
        :message="snackbarMessage"
        :is-open="isSnackbarOpen"
        @close-snackbar="isSnackbarOpen = false"
    ></Snackbar>

    <div class="max-w-sm mx-auto">
        <v-skeleton-loader
            v-if="imagesLoading"
            max-width="350"
            type="card"
        ></v-skeleton-loader>

        <v-card v-else max-width="350" class="rounded-lg px-4 pt-4">
            <v-carousel
                v-model="currentSlide"
                show-arrows="hover"
                hide-delimiters
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
                                        <v-list-item-title>
                                            Редактировать
                                        </v-list-item-title>
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

            <div class="d-flex justify-center mt-2">
                <span
                    v-for="i in visibleIndicators"
                    :key="i"
                    class="mx-1 rounded-full"
                    :class="{
                        'bg-primary': currentSlide === i,
                        'bg-gray-300': currentSlide !== i,
                    }"
                    style="
                        width: 10px;
                        height: 10px;
                        display: inline-block;
                        cursor: pointer;
                    "
                    @click="currentSlide = i"
                ></span>
            </div>

            <v-card-title class="text-h6 font-weight-medium">
                {{ currentImage?.name }}
            </v-card-title>

            <v-card-actions>
                <div
                    class="w-full"
                    v-if="authStore.checkPermission('gallery-create')"
                >
                    <v-btn
                        class="w-full"
                        @click="openDialog()"
                        prepend-icon="ri-add-line"
                        variant="flat"
                        color="success"
                    >
                        {{ $t("main.append_button") }}
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </div>
</template>
