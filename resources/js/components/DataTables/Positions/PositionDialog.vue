<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">Должность</span>
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
                <v-btn
                    v-if="props.editId !== null"
                    color="green"
                    text
                    @click="update(props.editId)"
                >
                    Изменить
                </v-btn>
                <v-btn v-else color="green" text @click="add"> Добавить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import axios from "axios";
import { reactive, ref, watch } from "vue";

const props = defineProps({
    editId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    name: null,
});

const formDataErrors = reactive({});

const close = function (dataChanged) {
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", dataChanged);
};

const add = function () {
    axios
        .post("/api/positions", formData)
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

const edit = function () {
    clearFields(formData);
    axios
        .get(`/api/positions/${props.editId}/edit`, formData)
        .then((response) => {
            Object.keys(response.data.data).forEach((key) => {
                formData[key] = response.data.data[key];
            });
        })
        .catch((error) => {
            console.log(error);
        });
};

const update = function (id) {
    axios
        .patch(`/api/positions/${id}`, formData)
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

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false && props.editId !== null) {
            edit();
        }
    }
);

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = "";
    });
};
</script>
