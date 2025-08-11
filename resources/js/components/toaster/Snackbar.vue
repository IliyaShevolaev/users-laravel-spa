<script setup>
const props = defineProps({
    isOpen: Boolean,
    message: String,
    color: String,
    timeout: {
        type: Number,
        default: 5000,
    },
});

const emit = defineEmits(["closeSnackbar"]);
</script>

<template>
    <v-snackbar
        v-model="props.isOpen"
        :color="props.color"
        :timeout="props.timeout"
        @pointerenter.capture.stop
        @pointerleave.capture.stop
        @update:model-value="emit('closeSnackbar')"
        timer="info"
    >
        <span class="text-base text-gray-900"> {{ props.message }} </span>

        <template v-slot:actions>
            <v-btn
                icon
                variant="text"
                size="large"
                @click="emit('closeSnackbar')"
            >
                <v-icon size="30" class="text-gray-900">ri-close-line</v-icon>
            </v-btn>
        </template>
    </v-snackbar>
</template>
