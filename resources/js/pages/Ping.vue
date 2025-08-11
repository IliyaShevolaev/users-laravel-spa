<template>
    <div>
        ping
        <v-btn @click="open">snack</v-btn>
        <Snackbar
            :color="'success'"
            :message="'Text here jdskj'"
            :is-open="isSnacknarOpen"
            @close-snackbar="close"
        ></Snackbar>
    </div>
</template>
<script setup>
import Snackbar from '../components/toaster/Snackbar.vue';
import axios from 'axios';

    axios.get('/api/user').then(res => {
        console.log(res);
    })

    const isSnacknarOpen = ref(false);
    const open = function() {
        isSnacknarOpen.value = true;
    }
    const close = function() {
        isSnacknarOpen.value = false;
    }
</script>
<style>

</style>
