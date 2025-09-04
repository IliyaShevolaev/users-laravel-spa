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

        role: {
            lastAdd: null,
            lastEdit: null,
            lastDelete: null,
            betweenPagesMethod: null,
        },

        event: {
            lastAdd: null,
            lastEdit: null,
            lastDelete: null
        },

        region: {
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
        getRole() {
            return this.role;
        },
        getEvent() {
            return this.event;
        },
        getRegion() {
            return this.region;
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
        },

        addRole(name) {
            this.role.lastAdd = name;
        },
        editRole(name) {
            this.role.lastEdit = name;
        },
        deleteRole(name) {
            this.role.lastDelete = name;
        },
        setRoleBetweenPagesMethod(method) {
            this.role.betweenPagesMethod = method
        },
        unsetRoleBetweenPagesMethod() {
            this.role.betweenPagesMethod = null
        },

        addEvent(name) {
            this.event.lastAdd = name;
        },
        editEvent(name) {
            this.event.lastEdit = name;
        },
        deleteEvent(name) {
            this.event.lastDelete = name;
        },

        addRegion(name) {
            this.region.lastAdd = name;
        },
        editRegion(name) {
            this.region.lastEdit = name;
        },
        deleteRegion(name) {
            this.region.lastDelete = name;
        },
    },
});

