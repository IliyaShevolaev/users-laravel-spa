import { defineStore } from "pinia";

export const useJobStatusStore = defineStore("jobStatus", {
    state: () => ({
        citiesExporting: false,
    }),

    getters: {
        isCitiesExporting(state) {
            return state.citiesExporting;
        },
    },

    actions: {
        startCitiesExport() {
            this.citiesExporting = true;
        },

        endCitiesExport() {
            this.citiesExporting = false;
        }
    },
});
