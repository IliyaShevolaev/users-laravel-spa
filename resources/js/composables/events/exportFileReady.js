import { onMounted, onBeforeUnmount, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useJobStatusStore } from "../../stores/jobStatus";
import { useI18n } from "vue-i18n";
import { saveAs } from "file-saver";
import { downloadExportFile, markFileAsDownloaded } from "../api/export/exportRequests";
import axios from "axios";

export function listenExportFileReady() {
    const authStore = useAuthStore();
    const echoChannel = ref(null);
    const jobStatusStore = useJobStatusStore();
    const { t } = useI18n();

    const listenUser = (userId) => {
        if (!userId) return;

        if (echoChannel.value) {
            echoChannel.value.stopListening(".export.file.ready");
            echoChannel.value = null;
        }

        echoChannel.value = window.Echo.private(
            `export.file.ready.${userId}`
        ).listen(".export.file.ready", async (event) => {
            console.log("Your file ready!!!!");
            console.log(event);

            try {
                await downloadExportFile(event.fileName, event.downloadName);
                await markFileAsDownloaded(event.fileName);
            } catch (error) {
                console.log(error);
            } finally {
                jobStatusStore.endCitiesExport();
            }
        });
    };

    listenUser(authStore.userData.id);

    watch(
        () => authStore.userData.id,
        (userId) => {
            listenUser(userId);
        }
    );
}
