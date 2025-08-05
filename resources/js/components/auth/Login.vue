<template>
    <v-row class="flex justify-center">
        <v-col cols="12" md="4">
            <v-card class="pa-3 ma-3" elevation="3">
                <v-card-title primary-title class="my-4 text-h4">
                    <span
                        class="flex-fill"
                        :class="
                            $vuetify.display.mobile
                                ? 'text-h6'
                                : 'text-h4'
                        "
                        >Вход в аккаунт</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" class="text-left" lazy-validation>
                        <v-text-field
                            v-model="loginFormData.email"
                            :error="error"
                            label="Email"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="email"
                            outlined
                            validateOn="blur"
                        ></v-text-field>

                        <v-text-field
                            v-model="loginFormData.password"
                            :append-inner-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            :error="error"
                            label="Пароль"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="password"
                            outlined
                            validateOn="blur"
                            @click:append-inner="showPassword = !showPassword"
                        ></v-text-field>

                        <v-btn
                            block
                            size="x-large"
                            color="primary"
                            class="mt-2"
                            :class="$vuetify.display.mobile ? 'text-body-2' : 'text-subtitle-1'"
                            @click="loginSubmit"
                        >
                            Войти
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>

            <div class="text-center mt-6">
                Нет аккаунта?
                <router-link
                    to="/register"
                    class="text-primary font-weight-bold"
                >
                    Зарегистрироваться
                </router-link>
            </div>
        </v-col>
    </v-row>
</template>

<script setup>
import axios from "axios";
import { reactive, ref } from "vue";
import { useRouter } from 'vue-router'
import { useAuthStore } from "../../stores/auth";

const router = useRouter()

const loginFormData = reactive({
    email: "",
    password: "",
});

const showPassword = ref(false);
const error = ref(false);

const authStore = useAuthStore();

const loginSubmit = function () {
    axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
            .post("/login", loginFormData, {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                withCredentials: true
            })
            .then((response) => {
                authStore.authUser(response.data.user)
                router.push({name: 'dashboard'})
            }).catch(error => {
                console.log(error)
            });
    });
};
</script>

