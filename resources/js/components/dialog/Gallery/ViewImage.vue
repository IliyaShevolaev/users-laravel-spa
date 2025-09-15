<script setup>
import { reactive, ref, watch } from "vue";

const props = defineProps({
    imageUrl: String,
    isOpen: Boolean,
});

const emit = defineEmits(["closeDialog"]);

const imageSize = reactive({ width: 0, height: 0 });

const close = () => {
    emit("closeDialog");
};

const loadImage = () => {
    if (!props.imageUrl) return;
    const img = new Image();
    img.src = props.imageUrl;
    img.onload = () => {
        const screenWidth = window.innerWidth * 0.9;
        const screenHeight = window.innerHeight * 0.9;

        const widthScreenScale = screenWidth / img.width;
        const heightScreenScale = screenHeight / img.height;
        const minScreenScale = Math.min(widthScreenScale, heightScreenScale, 1);

        imageSize.width = img.width * minScreenScale;
        imageSize.height = img.height * minScreenScale;
    };
};

watch(
    () => props.isOpen,
    (newValue) => {
        if (newValue) loadImage();
    }
);
</script>

<template>
    <v-dialog
        v-model="props.isOpen"
        persistent
        max-width="95%"
        max-height="95%"
    >
        <v-card class=" mx-auto">
            <v-card-title class="d-flex justify-end pa-0">
                <v-btn icon variant="text" size="medium" @click="close">
                    <v-icon size="30">ri-close-line</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-img
                    :src="props.imageUrl"
                    :width="imageSize.width"
                    :height="imageSize.height"
                    contain
                ></v-img>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
