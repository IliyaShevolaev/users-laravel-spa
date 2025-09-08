import { defineStore } from "pinia";
import { downloadExportFile, markFileAsDownloaded } from "../composables/api/export/exportRequests";

export const useExportNotifyStore = defineStore("exportNotify", {
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
        pushNotify(title, subtitle, icon, fileName) {
            this.notifyRecords.push({
                title: title,
                subtitle: subtitle,
                icon: icon,
                fileName: fileName,
            });
        },

        async downloadFile(fileName) {
            try {
                await downloadExportFile(fileName);
                await markFileAsDownloaded(fileName);

                this.notifyRecords = this.notifyRecords.filter(
                    (record) => record.fileName !== fileName
                );
            } catch (error) {
                console.log(error);
            }
        },
    },
});
