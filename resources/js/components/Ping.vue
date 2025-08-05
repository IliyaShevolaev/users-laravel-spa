<template>
    <div></div>
</template>
<script setup>
import axios from "axios";

axios.get("/api/user").then((result) => {
    console.log(result.data);
});
</script>
<style></style>
