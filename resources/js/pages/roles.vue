<script setup>
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../stores/modelChanges";
import { useRouter } from "vue-router";
import { debounce } from "vuetify/lib/util/helpers.mjs";
import { useDisplay } from "vuetify";

const { mobile } = useDisplay();
const router = useRouter();
const modelChangesStore = useModelChangesStore();
const { t } = useI18n();

const roles = ref([]);

const headers = [
    { title: "ID", key: "id" },
    { title: t("main.title"), key: "name" },
    { title: t("main.created"), key: "created_at" },
    { title: t("main.updated"), key: "updated_at" },
    // {
    //     title: t("main.actions"),
    //     key: "actions",
    //     sortable: false,
    //     align: "center",
    // },
];

const itemsPerPage = ref(10);
const currentPage = ref(1);
const currentSortBy = ref([]);
const totalItems = ref(0);
const search = ref("");

const loadingTable = ref(false);

const requestData = function ({ page, itemsPerPage, sortBy }) {
    if (loadingTable.value) {
        return;
    }

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
        .get("/api/roles/datatable", { params })
        .then((response) => {
            roles.value = response.data.data.original.data;
            currentPage.value = response.data.input.page;
            totalItems.value = response.data.recordsFiltered;
            console.log("Response:", response.data);
        })
        .catch((error) => {
            console.error(error);
            roles.value = [];
            totalItems.value = 0;
        })
        .finally(() => {
            loadingTable.value = false;
        });
};

const debouncedSearch = debounce((newValue) => {
    if (newValue === "" || newValue.length >= 3) {
        requestData({
            page: 1,
            itemsPerPage: itemsPerPage.value,
            sortBy: currentSortBy.value,
        });
    }
}, 1000);

watch(
    () => search.value,
    (newValue) => {
        debouncedSearch(newValue);
    }
);

</script>
<template>
    <div class="mb-5">
        <v-btn
            @click="router.push('/roles/create')"
            prepend-icon="ri-add-line"
            color="success"
        >
            {{ $t("main.append_button") }}
        </v-btn>
    </div>

    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        v-model:page="currentPage"
        :headers="headers"
        :items-length="totalItems"
        :items="roles"
        :loading="loadingTable"
        :search="search"
        @update:options="requestData"
    >
        <template v-slot:top>
            <div class="flex flex-row-reverse">
                <div :class="mobile ? 'w-full' : 'w-25'">
                    <v-text-field
                        v-model.lazy="search"
                        class="ma-2"
                        density="compact"
                        :placeholder="$t('main.search')"
                        hide-details
                        clearable
                    ></v-text-field>
                </div>
            </div>
        </template>
    </v-data-table-server>
</template>

<style></style>
