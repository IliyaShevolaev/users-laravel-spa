import axios from "axios";
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        auth: false,
        loading: false,
        userData: {
            id: null,
            name: null,
            role: null
        },
        userPermissions: [],
    }),
    getters: {
        isAuth() {
            return this.auth;
        },

        userInfo() {
            return this.userData;
        },

        permissions() {
            return this.userPermissions;
        },
        isLoading() {
            return this.loading;
        },
    },
    actions: {
        requestAuth() {
            this.loading = true;
            return axios
                .get("/api/user")
                .then((response) => {
                    if (response.data.user !== null) {
                        this.authUser(
                            response.data.user,
                            response.data.permissions,
                            response.data.role
                        );
                    } else {
                        this.logoutUser();
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        checkPermission(permissionName) {
            return this.permissions.includes(permissionName);
        },

        hasOneOfEachPermission(...permissions) {
            let result = false;
            permissions.forEach(permission => {
                if (this.checkPermission(permission)) {
                    result = true;
                }
            });

            return result;
        },

        authUser(userData, permissions, role) {
            localStorage.setItem("auth", "true");
            this.auth = true;
            this.userData.id = userData.id;
            this.userData.name = userData.name;
            this.userData.email = userData.email;
            this.userData.role = role;
            this.userPermissions = permissions;
        },

        logoutUser() {
            localStorage.removeItem("auth");
            this.auth = false;
            this.userData.id = null;
            this.userData.name = null;
            this.userData.email = null;
            this.userPermissions = [];
        },
    },
});
