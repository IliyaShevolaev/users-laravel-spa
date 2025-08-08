<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card prepend-icon="ri-delete-bin-fill" title="Ошибка!" :text="props.message">
            <template v-slot:actions>
                <v-spacer></v-spacer>

                <v-btn @click="emit('closeDialog')"> Ок </v-btn>
            </template>
        </v-card>
    </v-dialog>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
    message: String,
});

const emit = defineEmits(["closeDialog"]);
</script>
