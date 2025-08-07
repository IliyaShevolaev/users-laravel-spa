<script setup>
import { useTheme } from "vuetify";
import logo from "@images/logo.svg?raw";
import authV1MaskDark from "@images/pages/auth-v1-mask-dark.png";
import authV1MaskLight from "@images/pages/auth-v1-mask-light.png";
import authV1Tree2 from "@images/pages/auth-v1-tree-2.png";
import authV1Tree from "@images/pages/auth-v1-tree.png";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const vuetifyTheme = useTheme();

const authThemeMask = computed(() => {
    return vuetifyTheme.global.name.value === "light"
        ? authV1MaskLight
        : authV1MaskDark;
});

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

const isPasswordVisible = ref(false);

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
                router.push('/');
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

<template>
    <!-- eslint-disable vue/no-v-html -->

    <div class="auth-wrapper d-flex align-center justify-center pa-4">
        <VCard class="auth-card pa-4 pt-7" max-width="448">
            <VCardItem class="justify-center">
                <div class="d-flex align-center gap-3">
                    <!-- eslint-disable vue/no-v-html -->
                    <div class="d-flex" v-html="logo" />
                    <h2 class="font-weight-medium text-2xl text-uppercase">
                        Materio
                    </h2>
                </div>
            </VCardItem>

            <VCardText>
                <VForm @submit.prevent="registerSubmit">
                    <VRow>
                        <!-- Username -->
                        <VCol cols="12">
                            <VTextField
                                v-model="registerFormData.name"
                                :error="!!registerErrorFormData.name"
                                :error-messages="registerErrorFormData.name"
                                label="Имя"
                            />
                        </VCol>
                        <!-- email -->
                        <VCol cols="12">
                            <VTextField
                                v-model="registerFormData.email"
                                :error="!!registerErrorFormData.email"
                                :error-messages="registerErrorFormData.email"
                                label="Почта"
                                type="email"
                            />
                        </VCol>

                        <!-- gender -->
                        <VCol cols="12">
                            <VSelect
                                v-model="registerFormData.gender"
                                :items="userGenders"
                                :error="!!registerErrorFormData.gender"
                                :error-messages="registerErrorFormData.gender"
                                item-title="text"
                                item-value="value"
                                label="Пол"
                                required
                            ></VSelect>
                        </VCol>

                        <!-- password -->
                        <VCol cols="12">
                            <VTextField
                                v-model="registerFormData.password"
                                :error="!!registerErrorFormData.password"
                                :error-messages="registerErrorFormData.password"
                                label="Пароль"
                                :type="isPasswordVisible ? 'text' : 'password'"
                                autocomplete="password"
                                :append-inner-icon="
                                    isPasswordVisible
                                        ? 'ri-eye-off-line'
                                        : 'ri-eye-line'
                                "
                                @click:append-inner="
                                    isPasswordVisible = !isPasswordVisible
                                "
                            />
                        </VCol>

                        <VCol cols="12">
                            <VTextField
                                v-model="registerFormData.password_confirmation"
                                :error="!!registerErrorFormData.password"
                                :error-messages="registerErrorFormData.password"
                                label="Подтверждение пароля"
                                :type="isPasswordVisible ? 'text' : 'password'"
                                autocomplete="password"
                                :append-inner-icon="
                                    isPasswordVisible
                                        ? 'ri-eye-off-line'
                                        : 'ri-eye-line'
                                "
                                @click:append-inner="
                                    isPasswordVisible = !isPasswordVisible
                                "
                            />
                        </VCol>

                        <VCol cols="12">
                            <VBtn block @click="registerSubmit">
                                Зарегистрироваться
                            </VBtn>
                        </VCol>

                        <!-- login instead -->
                        <VCol cols="12" class="text-center text-base">
                            <span>Уже есть аккаунт?</span>
                            <RouterLink class="text-primary ms-2" to="login">
                                Войти
                            </RouterLink>
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>

        <VImg
            class="auth-footer-start-tree d-none d-md-block"
            :src="authV1Tree"
            :width="250"
        />

        <VImg
            :src="authV1Tree2"
            class="auth-footer-end-tree d-none d-md-block"
            :width="350"
        />

        <!-- bg img -->
        <VImg class="auth-footer-mask d-none d-md-block" :src="authThemeMask" />
    </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>
