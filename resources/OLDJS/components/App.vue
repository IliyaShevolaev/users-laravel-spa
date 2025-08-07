<template>
    <v-layout class="rounded rounded-md border">
        <AppBar></AppBar>

        <NavigationSidebar></NavigationSidebar>

        <v-main class="d-flex align-center justify-center">
            <v-container>
                <RouterView/>
            </v-container>
        </v-main>
    </v-layout>
</template>

<script setup>
import NavigationSidebar from './UI/NavigationSidebar.vue';
import AppBar from './UI/AppBar.vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();
axios.get('/api/user').then(response => {
    console.log(response.data)
    if (response.data !== '') {
        authStore.authUser(response.data);
    } else {
        authStore.logoutUser();
    }
})
</script>
