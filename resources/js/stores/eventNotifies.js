import { defineStore } from "pinia";

export const useEventNotifyStore = defineStore("eventNotify", {
    state: () => ({
        notifyRecords: [],
    }),

    getters: {
        badge(state) {
            return state.notifyRecords.length;
        },
        getNotifyRecords(state) {
            return state.notifyRecords;
        },
    },

    actions: {
        pushNotify(title, subtitle, icon) {
            this.notifyRecords.push({
                title: title,
                subtitle: subtitle,
                icon: icon,
            });
        },
    },
});
