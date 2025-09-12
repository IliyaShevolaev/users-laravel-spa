import axios from "axios";
import { saveAs } from "file-saver";

export async function downloadExportFile(fileName, downloadName = null) {
    if (downloadName === null) {
        downloadName = fileName;
    }

    const response = await axios.get(`/api/exports/download/${fileName}`, {
        responseType: "blob",
    });

    saveAs(response.data, downloadName);
}

export async function markFileAsDownloaded(fileName) {
    return axios.get(`/api/exports/mark-downloaded/${fileName}`);
}
