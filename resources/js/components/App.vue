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

<script>
import NavigationSidebar from './UI/NavigationSidebar.vue';
import AppBar from './UI/AppBar.vue';

export default {
    name: "App",

    components: {
        NavigationSidebar,
        AppBar
    }
};
</script>
