<template>
    <div>
        <h2>test comp</h2>
        <v-btn prepend-icon="$vuetify"> Button </v-btn>
        <v-alert
        color="#2A3B4D"
        dark
        icon="mdi-firework"
        dense
      >
        Suspendisse enim turpis, dictum sed, iaculis a, condimentum nec, nisi. Vivamus quis mi. Quisque ut nisi. Maecenas malesuada.

      </v-alert>
    </div>
</template>
<script>
import axios from "axios";

export default {
    mounted() {
        axios.get("/api/ping").then((response) => {
            console.log(response);
        });
    },
};
</script>
<style></style>
