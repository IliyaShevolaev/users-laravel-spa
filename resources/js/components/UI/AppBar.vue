<template>
    <v-app-bar>
        <v-btn icon="mdi-magnify"></v-btn>

        <template v-slot:append>
            <v-btn
                @click="theme.toggle()"
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
</script>

<style></style>
