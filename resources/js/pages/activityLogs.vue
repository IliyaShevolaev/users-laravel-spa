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
        { title: t("logs.subject_type"), key: "subject_type" },
        { title: t("logs.subject_name"), key: "subject_name" },
        { title: t("logs.message"), key: "message" },
        { title: t("logs.message"), key: "description" },
        { title: t("logs.at"), key: "created_at" },
    ];

    return baseHeaders;
});

const itemsPerPage = ref(10);
const currentPage = ref(1);
const currentSortBy = ref([]);
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
    currentSortBy.value = sortBy;

    const params = {
        page: page,
        per_page: itemsPerPage,
        sort_by: sortBy.length ? sortBy[0].key : null,
        sort_order: sortBy.length ? sortBy[0].order : null,
        search: search.value,
    };

    console.log("Request:", params);

    axios
        .post(`/api/activity-logs/datatable/${props.id}`, params, {
            signal: abortController.signal,
        })
        .then((response) => {
            console.log(response);
            activityLogs.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);

            prepareLogs();
            console.log("activityLogs.value");
            console.log(activityLogs.value);
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
        log.description = log.description.join("<br>");

        if (log.event === "updated") {
            log.message = prepraredUpdateLogMessages(log.properties);
        } else if (log.event === "created") {
            log.message = prepraredCreateLogMessages(log.properties);
        } else if (log.event === "deleted") {
            log.subject_name = log.properties.old.name;
            log.message = prepraredDeleteLogMessages(log.properties);
        }

        log.subject_type = t(
            `logs.models.${log.subject_type.split("\\").pop()}`
        );
        log.event = t(`logs.actions.${log.event}`);
    });
};

const prepraredCreateLogMessages = function (properties) {
    const attributes = properties.attributes;
    const logs = [];

    for (const key in attributes) {
        const value = attributes[key];

        if (!value) {
            continue;
        }

        if (key === 'password') {
            continue;
        }

        let message = `${t(
            `logs.fields.user.${key}`
        )} установленно на "${value}"`;

        logs.push(message);
    }

    return logs.join("<br>");
};

const prepraredUpdateLogMessages = function (properties) {
    const { attributes, old } = properties;
    const logs = [];

    for (const key in attributes) {
        const oldValue = old[key];
        const newValue = attributes[key];

        if (oldValue === newValue) continue;

        if (key === 'password') {
            logs.push('Пароль был изменен');
            continue;
        }

        let message = `${t(`logs.fields.user.${key}`)} изменено`;
        if (oldValue === null) {
            message += ` на "${newValue}"`;
        } else if (newValue === null) {
            message += ` с "${oldValue}" на пустое значение`;
        } else {
            message += ` с "${oldValue}" на "${newValue}"`;
        }

        logs.push(message);
    }

    return logs.join("<br>");
};

const prepraredDeleteLogMessages = function (properties) {
    return "Запись была удалена";
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
        <template v-slot:item.message="{ item }">
            <div v-html="item.message"></div>
        </template>
        <template v-slot:item.description="{ item }">
            <div v-html="item.description"></div>
        </template>
    </v-data-table-server>
</template>

<style></style>
