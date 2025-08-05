<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">Добавить новый отдел</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="add">
                    <v-text-field
                        v-model="formData.name"
                        label="Название"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="name"
                        outlined
                        validateOn="blur"
                    ></v-text-field>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.name"
                        type="error"
                    >
                        {{ formDataErrors.name }}
                    </v-alert>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="red" @click="close(false)">Закрыть</v-btn>
                <v-btn color="green" text @click="add"> Добавить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import axios from "axios";
import { reactive, ref } from "vue";

const props = defineProps({
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    name: null,
});

const formDataErrors = reactive({});

const close = function (dataChanged) {
    if (dataChanged) {
        clearFields(formData);
    }

    clearFields(formDataErrors);
    emit("closeDialog", dataChanged);
};

const add = function () {
    axios
        .post("/api/departments", formData)
        .then((response) => {
            close(true);
        })
        .catch((error) => {
            clearFields(formDataErrors);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            } else {
                console.log(error);
            }
        });
};

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = "";
    });
};
</script>
