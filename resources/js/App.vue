<script setup>
import axios from 'axios';
import { useAuthStore } from './stores/auth';

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

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
