import { defineStore } from "pinia";

export const useModelChangesStore = defineStore("modelChanges", {
    state: () => ({
        user: {
            lastAdd: null,
            lastEdit: null,
            lastDelete: null
        },

        position: {
            lastAdd: null,
            lastEdit: null,
            lastDelete: null
        },

        department: {
            lastAdd: null,
            lastEdit: null,
            lastDelete: null
        },
    }),
    getters: {
        getUser() {
            return this.user;
        },

        getPosition() {
            return this.position;
        },

        getDepartment() {
            return this.department;
        },
    },
    actions: {
        addUser(name) {
            this.user.lastAdd = name;
        },
        editUser(name) {
            this.user.lastEdit = name;
        },
        deleteUser(name) {
            this.user.lastDelete = name;
        },

        addPosition(name) {
            this.position.lastAdd = name;
        },
        editPosition(name) {
            this.position.lastEdit = name;
        },
        deletePosition(name) {
            this.position.lastDelete = name;
        },

        addDepartment(name) {
            this.department.lastAdd = name;
        },
        editDepartment(name) {
            this.department.lastEdit = name;
        },
        deleteDepartment(name) {
            this.department.lastDelete = name;
        }
    },
});

