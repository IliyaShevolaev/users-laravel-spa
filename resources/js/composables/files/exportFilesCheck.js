import axios from "axios";
import { useAuthStore } from "../../stores/auth";
import { useI18n } from "vue-i18n";
import { useExportNotifyStore } from "../../stores/exportNotifies";

export function checkMissDownloadedFiles() {
    const authStore = useAuthStore();
    const exportStore = useExportNotifyStore();
    const { t } = useI18n();

    const getIconByFileType = function (type) {
        switch (type) {
            case "xlsx":
                return "ri-file-excel-2-line";
            case "docx":
                return "ri-file-word-2-line";
            case "pdf":
                return "ri-file-pdf-2-line";
            default:
                return "ri-file-2-line";
        }
    };

    const filesCheck = function () {
        axios.get(`/api/exports/get-miss-downloaded-files`).then((response) => {
            console.log("miss files:");
            console.log(response);

            response.data.forEach((file) => {
                exportStore.pushNotify(
                    t("cities.end_export_download"),
                    `${t("cities.file_name")} ${file.file_name}`,
                    getIconByFileType(file.file_type),
                    file.file_name
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
