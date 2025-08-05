<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-alert type="error"> I'm an error alert. </v-alert>
        <v-btn color="error" @click="emit('closeDialog')">Закрыть</v-btn>
    </v-dialog>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(["closeDialog"]);

</script>
