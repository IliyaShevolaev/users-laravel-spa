import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        auth: false,
        userData: {
            id: null,
            name: null
        }
    }),
    getters: {
        isAuth() {
            return this.auth;
        },

        userInfo() {
            return this.userData;
        }
    },
    actions: {
        authUser(userData) {
            this.auth = true;
            this.userData.id = userData.id;
            this.userData.name = userData.name;
            this.userData.email = userData.email;
        },

        logoutUser() {
            this.auth = false;
            this.userData.id = null;
            this.userData.name = null;
            this.userData.email = null;
        }
    },
});

// const auth = useAuthStore()
// const { isAuth } = storeToRefs(auth) перобразовать все свойства в ref
