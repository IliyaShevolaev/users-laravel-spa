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
            localStorage.setItem('auth', 'true');
            this.auth = true;
            this.userData.id = userData.id;
            this.userData.name = userData.name;
            this.userData.email = userData.email;
        },

        logoutUser() {
            localStorage.removeItem('auth');
            this.auth = false;
            this.userData.id = null;
            this.userData.name = null;
            this.userData.email = null;
        }
    },
});

