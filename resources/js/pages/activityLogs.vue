<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useDisplay } from "vuetify";
import debounce from "lodash/debounce";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../stores/modelChanges";
import { useAuthStore } from "../stores/auth";

const authStore = useAuthStore();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();
const { mobile } = useDisplay();

const props = defineProps({
    id: Number,
});

const activityLogs = ref([]);

const headers = computed(() => {
    const baseHeaders = [
        { title: t("logs.action"), key: "event" },
        { title: t("logs.subject_name"), key: "causer_name" },
        { title: t("logs.message"), key: "description", sortable: false },
        { title: t("logs.at"), key: "created_at" },
    ];

    return baseHeaders;
});

const itemsPerPage = ref(10);
const currentPage = ref(1);
const currentSortBy = ref([{ key: "created_at", order: "desc" }]);
const totalItems = ref(0);
const search = ref("");

const loadingTable = ref(false);
let abortController = null;

const requestData = function ({ page, itemsPerPage, sortBy }) {
    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();

    loadingTable.value = true;

    const requestSortBy = sortBy?.length ? sortBy : currentSortBy.value;
    currentSortBy.value = requestSortBy;

    const params = {
        page: page,
        per_page: itemsPerPage,
        sort_by: requestSortBy[0].key,
        sort_order: requestSortBy[0].order,
        search: search.value,
    };

    console.log("Request:", params);

    axios
        .post(`/api/activity-logs/datatable/${props.id}`, params, {
            signal: abortController.signal,
        })
        .then((response) => {
            activityLogs.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);

            prepareLogs();
        })
        .catch((error) => {
            if (error.status === 403) {
                showAlertDialog.value = true;
                alertText.value = t("main.no_permission");
            }
            if (error.status === 422) {
                showAlertDialog.value = true;
                alertText.value = t("main.bad_request");
            }
            console.error(error);
            activityLogs.value = [];
            totalItems.value = 0;
        })
        .finally(() => {
            loadingTable.value = false;
        });
};

const prepareLogs = function () {
    activityLogs.value.forEach((log) => {
        //log.description = log.description.join("<br>");
        let description = log.description.join("<br>");

        description = description
            .split("<br>")
            .map((line) => {
                if (line.length <= 70) return line;

                const words = line.split(" ");
                let newLine = "";
                let currentLength = 0;
                const result = [];

                words.forEach((word) => {
                    if (currentLength + word.length + 1 > 70) {
                        result.push(newLine.trim());
                        newLine = "";
                        currentLength = 0;
                    }
                    newLine += word + " ";
                    currentLength += word.length + 1;
                });

                if (newLine.trim()) {
                    result.push(newLine.trim());
                }

                return result.join("<br>");
            })
            .join("<br>");

        log.description = description;

        log.description = description;

        log.subject_type = t(
            `logs.models.${log.subject_type.split("\\").pop()}`
        );
        log.event = t(`logs.actions.${log.event}`);
    });
};

const debouncedSearch = debounce(() => {
    requestData({
        page: 1,
        itemsPerPage: itemsPerPage.value,
        sortBy: currentSortBy.value,
    });
}, 500);

watch(search, () => {
    debouncedSearch();
});
</script>

<template>
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="activityLogs"
        :loading="loadingTable"
        @update:options="requestData"
    >
        <template v-slot:item.description="{ item }">
            <div v-html="item.description"></div>
        </template>
    </v-data-table-server>
</template>

<style></style>
