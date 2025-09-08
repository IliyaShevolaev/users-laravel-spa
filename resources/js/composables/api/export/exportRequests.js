import axios from "axios";
import { saveAs } from "file-saver";

export async function downloadExportFile(fileName) {
    const response = await axios.get(`/api/exports/download/${fileName}`, {
        responseType: "blob",
    });

    saveAs(response.data, fileName);
}

export async function markFileAsDownloaded(fileName) {
    return axios.get(`/api/exports/mark-downloaded/${fileName}`);
}
