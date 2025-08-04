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
        </template>
    </v-app-bar>
</template>

<script setup>
import { useTheme } from "vuetify";

const theme = useTheme();

const changeTheme = function () {
    theme.toggle();
    localStorage.setItem('userTheme', theme.global.name.value);
}

const test = function() {
    console.log('theme - ' + localStorage.getItem('userTheme'));
    console.log(localStorage.getItem('userTheme') === null ? localStorage.getItem('userTheme'): 'dark1');
}
</script>

<style></style>
