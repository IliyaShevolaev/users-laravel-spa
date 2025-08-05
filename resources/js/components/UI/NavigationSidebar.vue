<template>
    <v-navigation-drawer expand-on-hover permanent rail>
        <v-list density="compact" nav>
            <!-- <v-list-item type="subheader" prepend-icon="mdi-account" title="Пользователи"></v-list-item> -->
            <v-list-item
                @click="handleClick('departments')"
                prepend-icon="mdi-office-building"
                title="Отделы"
            ></v-list-item>

            <v-list-item
                @click="handleClick('positions')"
                prepend-icon="mdi-briefcase"
                title="Должности"
            ></v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const handleClick = function (type) {
    router.push({ name: type });
};
</script>
<style></style>
