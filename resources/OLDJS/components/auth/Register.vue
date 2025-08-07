<template>
    <v-row class="flex justify-center">
        <v-col cols="12" md="4">
            <v-card class="pa-3 ma-3" elevation="3">
                <v-card-title primary-title class="my-4 text-h4">
                    <span
                        class="flex-fill"
                        :class="$vuetify.display.mobile ? 'text-h6' : 'text-h4'"
                        >Регистрация</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-form class="text-left" @submit.prevent="registerSubmit">
                        <v-text-field
                            v-model="registerFormData.name"
                            :error="registerErrorFormData.name"
                            label="Имя пользователя"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="name"
                            outlined
                            validateOn="blur"
                        ></v-text-field>
                        <v-alert
                            class="mb-4"
                            v-if="registerErrorFormData.name"
                            type="error"
                        >
                            {{ registerErrorFormData.name }}
                        </v-alert>

                        <v-text-field
                            v-model="registerFormData.email"
                            :error="registerErrorFormData.email"
                            label="Email"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="email"
                            outlined
                            validateOn="blur"
                        ></v-text-field>
                        <v-alert
                            class="mb-4"
                            v-if="registerErrorFormData.email"
                            type="error"
                        >
                            {{ registerErrorFormData.email }}
                        </v-alert>

                        <v-select
                            v-model="registerFormData.gender"
                            :items="userGenders"
                            :error="registerErrorFormData.gender"
                            item-title="text"
                            variant="underlined"
                            item-value="value"
                            label="Пол"
                            required
                        ></v-select>
                        <v-alert
                            class="mb-4"
                            v-if="registerErrorFormData.gender"
                            type="error"
                        >
                            {{ registerErrorFormData.gender }}
                        </v-alert>

                        <v-text-field
                            v-model="registerFormData.password"
                            :append-inner-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            :error="registerErrorFormData.password"
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
                            v-if="registerErrorFormData.password"
                            type="error"
                        >
                            {{ registerErrorFormData.password }}
                        </v-alert>

                        <v-text-field
                            v-model="registerFormData.password_confirmation"
                            :append-inner-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            :error="registerErrorFormData.password"
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
                            v-if="registerErrorFormData.password"
                            type="error"
                        >
                            {{ registerErrorFormData.password }}
                        </v-alert>

                        <v-btn
                            block
                            size="x-large"
                            color="primary"
                            class="mt-2"
                            :class="
                                $vuetify.display.mobile
                                    ? 'text-body-2'
                                    : 'text-subtitle-1'
                            "
                            @click="registerSubmit"
                        >
                            Зарегистрироваться
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>

            <div class="text-center mt-6">
                Уже есть аккаунт?
                <router-link to="/login" class="text-primary font-weight-bold">
                    Войти
                </router-link>
            </div>
        </v-col>
    </v-row>
</template>

<script setup>
import axios from "axios";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const registerFormData = reactive({
    name: "",
    email: "",
    gender: "",
    password: "",
    password_confirmation: "",
});

const registerErrorFormData = reactive({});

const showPassword = ref(false);

const userGenders = ref([]);
const getCreateUserData = function () {
    axios.get("/api/auth/create").then((response) => {
        console.log(response);
        userGenders.value = response.data.genders;
    });
};

getCreateUserData();

const registerSubmit = function () {
    axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
            .post("/register", registerFormData, {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
            .then((response) => {
                authStore.authUser(response.data.user);
                router.push({ name: "users" });
            })
            .catch((error) => {
                clearErrors();
                if (error.response.status === 422) {
                    const errors = error.response.data.errors;
                    console.log(errors);
                    for (error in errors) {
                        registerErrorFormData[error] = errors[error][0];
                    }
                    console.log(registerErrorFormData);
                } else {
                    console.log(error);
                }
            });
    });
};

const clearErrors = function () {
    Object.keys(registerErrorFormData).forEach((key) => {
        registerErrorFormData[key] = "";
    });
};
</script>
