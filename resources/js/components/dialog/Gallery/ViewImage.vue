<script setup>
import { reactive, ref, watch } from "vue";

const props = defineProps({
    images: Array,
    currentImage: Number,
    isOpen: Boolean,
});

const emit = defineEmits(["closeDialog"]);

const imageSize = reactive({ width: 0, height: 0 });
const currentSlide = ref(0);

const close = () => emit("closeDialog");

const loadImage = (index) => {
    const img = new Image();
    const currentArrayImage = props.images[index];
    img.src =
        currentArrayImage.imageUrl[
            Object.keys(currentArrayImage.imageUrl)[0]
        ].original_url;

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
        if (newValue) {
            currentSlide.value = props.currentImage;
            loadImage(currentSlide.value);
        }
    }
);

watch(currentSlide, (newIndex) => {
    if (props.isOpen) {
        loadImage(newIndex);
    }
});

const visibleIndicators = computed(() => {
    const total = props.images.length;
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
</script>

<template>
    <v-dialog
        v-model="props.isOpen"
        persistent
        max-width="95%"
        max-height="95%"
    >
        <v-card class="mx-auto">
            <v-card-title class="d-flex justify-end pa-0">
                <v-btn icon variant="text" size="medium" @click="close">
                    <v-icon size="30">ri-close-line</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="d-flex justify-center">
                <v-carousel
                    v-model="currentSlide"
                    style="height: 100%"
                    show-arrows="hover"
                    hide-delimiters
                >
                    <v-carousel-item
                        v-for="image in props.images"
                        :key="image.id"
                    >
                        <v-img
                            :src="
                                image.imageUrl[Object.keys(image.imageUrl)[0]]
                                    .original_url
                            "
                            :width="imageSize.width"
                            :height="imageSize.height"
                            contain
                        >
                            <span class="text-sky-50 pa-2"
                                >{{ currentSlide + 1 }}/{{
                                    images.length
                                }}</span
                            >
                        </v-img>
                    </v-carousel-item>
                </v-carousel>
            </v-card-text>

            <div class="d-flex justify-center mb-2">
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
        </v-card>
    </v-dialog>
</template>
