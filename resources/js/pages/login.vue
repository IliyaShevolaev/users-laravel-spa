<script setup>
import { useTheme } from "vuetify";
import logo from "@images/logo.svg?raw";
import authV1MaskDark from "@images/pages/auth-v1-mask-dark.png";
import authV1MaskLight from "@images/pages/auth-v1-mask-light.png";
import authV1Tree2 from "@images/pages/auth-v1-tree-2.png";
import authV1Tree from "@images/pages/auth-v1-tree.png";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const vuetifyTheme = useTheme();

const authThemeMask = computed(() => {
    return vuetifyTheme.global.name.value === "light"
        ? authV1MaskLight
        : authV1MaskDark;
});

const loginFormData = reactive({
    email: "",
    password: "",
});
const loginErrorFormData = reactive({});

const isPasswordVisible = ref(false);

const authStore = useAuthStore();
const router = useRouter();

const loginSubmit = function () {
    axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
            .post("/login", loginFormData, {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                withCredentials: true,
            })
            .then((response) => {
                //authStore.authUser(response.data.user);
                authStore.requestAuth();
                router.push("/"); //@todo перестало редиректить
            })
            .catch((error) => {
                clearErrors();
                console.log(error);
                if (error.response.status === 422) {
                    const errors = error.response.data.errors;
                    console.log(errors);
                    for (error in errors) {
                        loginErrorFormData[error] = errors[error][0];
                    }
                    console.log(loginErrorFormData);
                } else {
                    console.log(error);
                }
            });
    });
};

const clearErrors = function () {
    Object.keys(loginErrorFormData).forEach((key) => {
        loginErrorFormData[key] = "";
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
                <VForm @submit.prevent="loginSubmit">
                    <VRow>
                        <!-- email -->
                        <VCol cols="12">
                            <VTextField
                                v-model="loginFormData.email"
                                :error="!!loginErrorFormData.email"
                                :error-messages="loginErrorFormData.email"
                                :label="t('users.email')"
                                type="email"
                            />
                        </VCol>

                        <!-- password -->
                        <VCol cols="12">
                            <VTextField
                                v-model="loginFormData.password"
                                :error="!!loginErrorFormData.password"
                                :error-messages="loginErrorFormData.password"
                                :label="t('users.password')"
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

                            <!-- login button -->
                            <VBtn class="mt-5" block @click="loginSubmit">
                                {{ t("auth.login") }}
                            </VBtn>
                        </VCol>

                        <!-- create account -->
                        <VCol cols="12" class="text-center text-base">
                            <span>{{ t("auth.not_register_yet") }}</span>
                            <RouterLink
                                class="text-primary ms-2"
                                to="/register"
                            >
                                {{ t("auth.register") }}
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
