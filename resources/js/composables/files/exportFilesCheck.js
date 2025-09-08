import axios from "axios";
import { useAuthStore } from "../../stores/auth";
import { useI18n } from "vue-i18n";
import { useExportNotifyStore } from "../../stores/exportNotifies";

export function checkMissDownloadedFiles() {
    const authStore = useAuthStore();
    const exportStore = useExportNotifyStore();
    const { t } = useI18n();

    const filesCheck = function () {
        axios.get(`/api/exports/get-miss-downloaded-files`).then((response) => {
            console.log("miss files:");
            console.log(response);

            response.data.forEach((fileName) => {
                exportStore.pushNotify(
                    t("cities.end_export_download"),
                    `${t("cities.file_name")} ${fileName}`,
                    "ri-file-excel-2-line",
                    fileName
                );
            });
        });
    };

    watch(
        () => authStore.userData.id,
        (userId) => {
            filesCheck(userId);
        }
    );
}
