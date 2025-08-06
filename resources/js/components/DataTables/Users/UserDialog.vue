<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">Пользователь</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="add">
                    <v-text-field
                        v-model="formData.name"
                        :error="!!formDataErrors.name"
                        label="Имя"
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

                    <v-text-field
                        v-model="formData.email"
                        :error="!!formDataErrors.email"
                        label="Почта"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="email"
                        outlined
                        validateOn="blur"
                    ></v-text-field>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.email"
                        type="error"
                    >
                        {{ formDataErrors.email }}
                    </v-alert>

                    <v-text-field
                        v-model="formData.password"
                        :append-inner-icon="
                            showPassword ? 'mdi-eye' : 'mdi-eye-off'
                        "
                        :type="showPassword ? 'text' : 'password'"
                        :error="!!formDataErrors.password"
                        label="Пароль"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="password"
                        outlined
                        validateOn="blur"
                        @click:append-inner="showPassword = !showPassword"
                    ></v-text-field>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.password"
                        type="error"
                    >
                        {{ formDataErrors.password }}
                    </v-alert>

                    <v-text-field
                        v-model="formData.password_confirmation"
                        :append-inner-icon="
                            showPassword ? 'mdi-eye' : 'mdi-eye-off'
                        "
                        :type="showPassword ? 'text' : 'password'"
                        :error="!!formDataErrors.password"
                        label="Подтвердите пароль"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="password_confirmation"
                        outlined
                        validateOn="blur"
                        @click:append-inner="showPassword = !showPassword"
                    ></v-text-field>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.password"
                        type="error"
                    >
                        {{ formDataErrors.password }}
                    </v-alert>

                    <v-select
                        v-model="formData.gender"
                        :items="userGenders"
                        :error="!!formDataErrors.gender"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        label="Пол"
                        required
                    ></v-select>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.gender"
                        type="error"
                    >
                        {{ formDataErrors.gender }}
                    </v-alert>

                    <v-select
                        v-model="formData.status"
                        :items="userStatuses"
                        :error="!!formDataErrors.status"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        label="Статус"
                        required
                    ></v-select>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.status"
                        type="error"
                    >
                        {{ formDataErrors.status }}
                    </v-alert>

                    <v-select
                        v-model="formData.department_id"
                        :items="userDepartmentsComputed"
                        :error="!!formDataErrors.department_id"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        label="Отдел"
                        clearable
                    ></v-select>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.department_id"
                        type="error"
                    >
                        {{ formDataErrors.department_id }}
                    </v-alert>

                    <v-select
                        v-model="formData.position_id"
                        :items="userPositionsComputed"
                        item-title="text"
                        item-value="value"
                        :error="!!formDataErrors.position_id"
                        variant="underlined"
                        label="Должность"
                        clearable
                    ></v-select>
                    <v-alert
                        class="mb-4"
                        v-if="formDataErrors.position_id"
                        type="error"
                    >
                        {{ formDataErrors.position_id }}
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
import { computed, reactive, ref, watch } from "vue";

const showPassword = ref(false);

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
    email: null,
    password: null,
    password_confirmation: null,
    gender: null,
    status: null,
    department_id: null,
    position_id: null,
});

const formDataErrors = reactive({});

const userGenders = ref([]);
const userStatuses = ref([]);
const userDepartments = ref([]);
const userPositions = ref([]);

const requestCreateUserData = function () {
    axios.get("/api/users/create").then((response) => {
        console.log(response);
        userGenders.value = response.data.genders;
        userStatuses.value = response.data.statuses;
        userDepartments.value = response.data.departments;
        userPositions.value = response.data.positions;
    });
};
requestCreateUserData();

const userDepartmentsComputed = computed(() => {
    return userDepartments.value.map((department) => ({
        text: department.name,
        value: department.id,
    }));
});

const userPositionsComputed = computed(() => {
    return userPositions.value.map((position) => ({
        text: position.name,
        value: position.id,
    }));
});

const close = function (dataChanged) {
    clearFields(formData);
    clearFields(formDataErrors);
    console.log(formDataErrors);
    emit("closeDialog", dataChanged);
};

const add = function () {
    axios
        .post("/api/users", formData)
        .then((response) => {
            console.log(response);
            close(true);
        })
        .catch((error) => {
            console.log(error);
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
        .get(`/api/users/${props.editId}/edit`, formData)
        .then((response) => {
            console.log("111res");
            console.log(response.data);
            Object.keys(response.data.user).forEach((key) => {
                formData[key] = response.data.user[key];
            });
        })
        .catch((error) => {
            console.log("error");
            console.log(error);
        });
};

const update = function (id) {
    console.log(formData);
    console.log(id);
    console.log(`/api/users/${id}`);
    axios
        .patch(`/api/users/${id}`, formData)
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
