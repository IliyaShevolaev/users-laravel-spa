import { defineStore } from "pinia";

export const useEventNotifyStore = defineStore("eventNotify", {
    state: () => ({
        notifyRecords: [],
    }),

    getters: {
        badge(state) {
            return state.notifyRecords.length
        },
        getNotifyRecords(state) {
            return state.notifyRecords;
        }
    },

    actions: {
        pushNewEvent(event) {
            this.notifyRecords.push({
                title: event.title,
                creator: event.creator.name
            });
        },
    },
});
