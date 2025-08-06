<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-alert type="error"> {{ props.message }} </v-alert>
        <v-btn color="error" @click="emit('closeDialog')">Закрыть</v-btn>
    </v-dialog>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
    message: String
});

const emit = defineEmits(["closeDialog"]);

</script>
