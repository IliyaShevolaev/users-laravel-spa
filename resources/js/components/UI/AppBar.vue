<template>
    <v-app-bar>
        <v-btn @click="test" icon="mdi-magnify"></v-btn>

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

            <v-btn
                @click="logout"
                icon="mdi-logout"
            >
            </v-btn>
        </template>
    </v-app-bar>
</template>

<script setup>
import { useTheme } from "vuetify";

const theme = useTheme();

const changeTheme = function () {
    theme.toggle();
    localStorage.setItem("userTheme", theme.global.name.value);
};

const logout = function () {
    axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
            .post("/logout", {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
            .then((response) => {
                console.log(response);
                localStorage.setItem('auth', false);
            })
            .catch((error) => {
                console.log(error);
            });
    });
};
</script>

<style></style>
