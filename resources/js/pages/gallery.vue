<script setup>
import { useAuthStore } from "../stores/auth";
import { useModelChangesStore } from "../stores/modelChanges";
import Snackbar from "../components/toaster/Snackbar.vue";
import { useI18n } from "vue-i18n";
import { saveAs } from "file-saver";
import AcceptDialog from "../components/alerts/AcceptDialog.vue";

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
const choisenShowImage = ref(null);

const showImage = function (id) {
    isImageDialogOpen.value = true;
    choisenShowImage.value = id;
};

const closeImage = function (id) {
    isImageDialogOpen.value = false;
};

const downloadImage = function (url, name) {
    axios
        .get(url, {
            responseType: "blob",
        })
        .then((response) => {
            saveAs(response.data, name);
        });
};

const idToDelete = ref(0);

const showAlertAcceptDialog = ref(false);
const alertAcceptText = ref("");

const askToDeleteRow = function (id, name) {
    showAlertAcceptDialog.value = true;
    alertAcceptText.value = `${t("gallery.ask_to_delete")} ${name}?`;
    idToDelete.value = id;
    modelChangesStore.deleteImage(name);
};

const deleteRow = function (id) {
    axios
        .delete(`/api/images/${id}`)
        .then(() => {
            requestStartData();
            showSnackBar(
                t("gallery.image") +
                    " " +
                    modelChangesStore.getImage.lastDelete +
                    " " +
                    t("gallery.was_deleted"),
                "error"
            );
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("gallery.no_selected");
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
        :images="images"
        :current-image="choisenShowImage"
    ></ViewImage>

    <AcceptDialog
        @close-dialog="showAlertAcceptDialog = false"
        @accept-action="deleteRow(idToDelete)"
        :is-open="showAlertAcceptDialog"
        :message="alertAcceptText"
    ></AcceptDialog>

    <Snackbar
        :color="snackbarColor"
        :message="snackbarMessage"
        :is-open="isSnackbarOpen"
        @close-snackbar="isSnackbarOpen = false"
    ></Snackbar>

    <div v-if="authStore.checkPermission('gallery-create')">
        <v-btn
            @click="openDialog()"
            prepend-icon="ri-add-line"
            variant="flat"
            color="success"
        >
            {{ $t("main.append_button") }}
        </v-btn>
    </div>

    <v-row class="ma-0" dense>
        <v-col
            v-for="(image, index) in images"
            :key="image.id"
            cols="12"
            md="4"
            class="d-flex"
        >

            <v-card class="rounded-lg px-4 pt-4 w-100" height="370">
                <v-img
                    :src="
                        image.imageUrl[Object.keys(image.imageUrl)[0]]
                            .preview_url
                    "
                    @click="showImage(index)"
                    cover
                    height="300"
                    class="rounded"
                >
                    <div class="d-flex justify-end pa-2 text-sky-50">
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
                                <v-list-item
                                    @click="
                                        downloadImage(
                                            image.imageUrl[
                                                Object.keys(image.imageUrl)[0]
                                            ].original_url,
                                            image.name
                                        )
                                    "
                                >
                                    <template #prepend>
                                        <v-icon icon="ri-download-line" />
                                    </template>
                                    <v-list-item-title>
                                        {{ t("gallery.save") }}
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item
                                    v-if="
                                        authStore.checkPermission(
                                            'gallery-update'
                                        )
                                    "
                                    @click="openDialog(image.id)"
                                >
                                    <template #prepend>
                                        <v-icon icon="ri-edit-line" />
                                    </template>
                                    <v-list-item-title>
                                        {{ t("gallery.edit") }}
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item
                                    v-if="
                                        authStore.checkPermission(
                                            'gallery-delete'
                                        )
                                    "
                                    @click="
                                        askToDeleteRow(image.id, image.name)
                                    "
                                >
                                    <template #prepend>
                                        <v-icon icon="ri-delete-bin-line" />
                                    </template>
                                    <v-list-item-title>
                                        {{ t("gallery.delete") }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </v-img>

                <v-card-title class="text-xl mt-2">
                    {{ image.name }}
                </v-card-title>
            </v-card>
        </v-col>
    </v-row>

    <!-- <div class="max-w-sm mx-auto">
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
                        <div class="d-flex justify-between pa-2 text-sky-50">
                            <span
                                >{{ currentSlide + 1 }}/{{
                                    images.length
                                }}</span
                            >

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
                                    <v-list-item
                                        @click="
                                            downloadImage(
                                                image.imageUrl[
                                                    Object.keys(
                                                        image.imageUrl
                                                    )[0]
                                                ].original_url,
                                                image.name
                                            )
                                        "
                                    >
                                        <template #prepend>
                                            <v-icon
                                                icon="ri-download-line"
                                            ></v-icon>
                                        </template>
                                        <v-list-item-title>
                                            {{ t("gallery.save") }}
                                        </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item
                                        v-if="
                                            authStore.checkPermission(
                                                'gallery-update'
                                            )
                                        "
                                        @click="openDialog(image.id)"
                                    >
                                        <template #prepend>
                                            <v-icon
                                                icon="ri-edit-line"
                                            ></v-icon>
                                        </template>
                                        <v-list-item-title>
                                            {{ t("gallery.edit") }}
                                        </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item
                                        v-if="
                                            authStore.checkPermission(
                                                'gallery-delete'
                                            )
                                        "
                                        @click="
                                            askToDeleteRow(image.id, image.name)
                                        "
                                    >
                                        <template #prepend>
                                            <v-icon
                                                icon="ri-delete-bin-line"
                                            ></v-icon>
                                        </template>
                                        <v-list-item-title>
                                            {{ t("gallery.delete") }}
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

            <v-card-title class="text-xl">
                {{ currentImage?.name }}
            </v-card-title>
        </v-card>
    </div> -->
</template>
