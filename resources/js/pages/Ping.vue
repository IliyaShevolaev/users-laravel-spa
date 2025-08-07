<template>
    <div>
        ping
    </div>
</template>
<script setup>
import axios from 'axios';

    axios.get('/api/user').then(res => {
        console.log(res);
    })
</script>
<style>

</style>
