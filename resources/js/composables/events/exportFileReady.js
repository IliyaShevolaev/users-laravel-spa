import { onMounted, onBeforeUnmount, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useEventNotifyStore } from "../../stores/eventNotifies";
import { useI18n } from "vue-i18n";
import { saveAs } from "file-saver";
import axios from "axios";

export function listenExportFileReady() {
    const authStore = useAuthStore();
    const echoChannel = ref(null);
    const notifyStore = useEventNotifyStore();
    const { t } = useI18n();

    const listenUser = (userId) => {
        if (!userId) return;

        if (echoChannel.value) {
            echoChannel.value.stopListening(".export.file.ready");
            echoChannel.value = null;
        }

        echoChannel.value = window.Echo.private(
            `export.file.ready.${userId}`
        ).listen(".export.file.ready", (event) => {
            console.log("Your file ready!!!!");
            console.log(event);

            notifyStore.pushNotify(
                t("cities.end_export_download"),
                t("cities.file_name") + event.fileName,
                "ri-file-excel-2-line"
            );

            axios
                .get(`/api/exports/download/${event.fileName}`, {
                    responseType: "blob",
                })
                .then((response) => {
                    saveAs(response.data, event.fileName);
                });
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
