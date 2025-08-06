<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-alert type="warning"> {{ props.message }} </v-alert>
        <div class="flex">
            <v-btn class="w-50" color="warning" @click="emit('closeDialog')">Нет</v-btn>
            <v-btn class="w-50" color="warning" @click="emit('acceptAction')">Да</v-btn>
        </div>
    </v-dialog>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
    message: String,
});

const emit = defineEmits(["closeDialog", "acceptAction"]);
</script>
