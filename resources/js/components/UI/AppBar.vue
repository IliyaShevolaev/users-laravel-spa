<template>
    <v-app-bar>
        <template v-slot:append>
            <v-btn
                @click="changeTheme"
                :icon="
                    theme.name._value === 'dark'
                        ? 'mdi-weather-night'
                        : 'mdi-weather-sunny'
                "
            >
            </v-btn>

            <ProfileDropdownMenu v-if="authStore.isAuth"></ProfileDropdownMenu>
            <v-btn v-else @click="loginRedirect"  prepend-icon="mdi-login">
                Вход
            </v-btn>
        </template>
    </v-app-bar>
</template>

<script setup>
import { useTheme } from "vuetify";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";
import ProfileDropdownMenu from "./ProfileDropdownMenu.vue";

const theme = useTheme();
const router = useRouter();
const authStore = useAuthStore();

const changeTheme = () => {
    theme.toggle();
    localStorage.setItem("userTheme", theme.global.name.value);
};

const loginRedirect = () => {
    router.push({ name: "login" });
};
</script>

<style></style>
