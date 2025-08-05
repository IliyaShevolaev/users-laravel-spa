<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-btn  color="indigo" dark v-bind="props" icon="mdi-account"> </v-btn>
        </template>

        <v-card>
            <v-list>
                <v-list-item>
                    <v-list-item-title>{{userInfo.name}}</v-list-item-title>
                    <v-list-item-subtitle>{{userInfo.email}}</v-list-item-subtitle>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list>
                <v-list-item @click="logout">
                    <v-list-item-title>Выход</v-list-item-title>
                    <template v-slot:prepend>
                        <v-icon icon="mdi-logout"></v-icon>
                    </template>
                </v-list-item>
            </v-list>
        </v-card>
    </v-menu>
</template>

<script setup>
import { useAuthStore } from "../../stores/auth";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";

const authStore = useAuthStore();
const { userInfo } = storeToRefs(authStore)

const router = useRouter();

const logout = function () {
    axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
            .post("/logout", {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
            .then((response) => {
                authStore.logoutUser();
                router.push({name: 'login'})
            })
            .catch((error) => {
                console.log(error);
            });
    });
};
</script>
