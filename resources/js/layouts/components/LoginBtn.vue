<script setup>
    import { useRouter } from 'vue-router';

    const router = useRouter();

    const redirectToLogin = function() {
        router.push({path: '/login'})
    }
</script>

<template>
        <v-icon @click="redirectToLogin" >ri-login-box-line</v-icon>
</template>
