<script setup>
import { ref, onMounted, reactive } from "vue";
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import { useI18n } from "vue-i18n";
import { useTheme } from "vuetify";
import "@vuepic/vue-datepicker/dist/main.css";
import dayjs from "dayjs";

const vuetifyTheme = useTheme();
const { t } = useI18n();

const chartRef = ref(null);

const formData = reactive({
    start: null,
    end: null,
});

const dateRange = ref(null);
watch(dateRange, (newDate) => {
    if (newDate) {
        formData.start = dayjs(newDate[0]).format("YYYY-MM-DD");
        formData.end = dayjs(newDate[1]).format("YYYY-MM-DD");
        console.log(formData);
        requestStatsData();
    }
});

const loading = ref(false);

const requestStatsData = function () {
    loading.value = true;
    axios
        .get("/api/events/stats", { params: formData })
        .then((response) => {
            console.log(response);
            series.value[0].data = response.data.data;
            if (chartRef.value) {
                chartRef.value.updateOptions({
                    xaxis: {
                        type: "category",
                        categories: response.data.categories,
                    },
                });
            }

            loading.value = false;
        })
        .catch((error) => {
            console.log(error);
        });
};

const series = ref([
    {
        name: "Задачи",
        data: [],
    },
]);

const options = reactive({
    chart: {
        id: "tasks-statistics",
        toolbar: { show: false },
    },
    xaxis: {
        categories: [],
    },
});
</script>

<template>
    <div>
        <v-row
            class="px-4 py-6 mb-5 rounded-2xl"
            align="center"
            justify="start"
            dense
        >
            <v-col cols="12" sm="3" md="2">
                <v-select
                    label="Тип данных"
                    :items="['Вариант 1', 'Вариант 2']"
                    density="comfortable"
                />
            </v-col>

            <v-col cols="12" sm="4" md="2">
                <VueDatePicker
                    :dark="vuetifyTheme.global.name.value === 'dark'"
                    :placeholder="t('calendar.time_event')"
                    v-model="dateRange"
                    locale="ru"
                    range
                    format="dd.MM.yyyy"
                >
                    <template #action-row="{ selectDate, closePicker }">
                        <div class="flex justify-between w-full">
                            <v-btn
                                variant="outlined"
                                size="small"
                                @click="closePicker()"
                            >
                                {{ t("calendar.disable_button") }}
                            </v-btn>
                            <v-btn
                                variant="outlined"
                                size="small"
                                @click="selectDate()"
                            >
                                {{ t("calendar.accept_button") }}
                            </v-btn>
                        </div>
                    </template>
                </VueDatePicker>
            </v-col>
        </v-row>

        <div class="w-full h-[70vh] rounded-2xl">
            <apexchart
                ref="chartRef"
                type="bar"
                :options="options"
                :series="series"
                width="100%"
                height="100%"
            />
        </div>
    </div>
</template>

<style>
.dp__theme_light {
    --dp-background-color: #fff;
    --dp-text-color: #212121;
    --dp-hover-color: #f3f3f3;
    --dp-hover-text-color: #212121;
    --dp-hover-icon-color: #959595;
    --dp-primary-color: #7c4dff;
    --dp-primary-disabled-color: #a186ff;
    --dp-primary-text-color: #f8f5f5;
    --dp-secondary-color: #c0c4cc;
    --dp-border-color: #ddd;
    --dp-menu-border-color: #ddd;
    --dp-border-color-hover: #7c4dff;
    --dp-border-color-focus: #7c4dff;
    --dp-disabled-color: #f6f6f6;
    --dp-scroll-bar-background: #f3f3f3;
    --dp-scroll-bar-color: #959595;
    --dp-success-color: #76d275;
    --dp-success-color-disabled: #a3d9b1;
    --dp-icon-color: #959595;
    --dp-danger-color: #ff6f60;
    --dp-marker-color: #ff6f60;
    --dp-tooltip-color: #fafafa;
    --dp-disabled-color-text: #8e8e8e;
    --dp-highlight-color: rgb(25 118 210 / 10%);
    --dp-range-between-dates-background-color: var(--dp-hover-color, #f3f3f3);
    --dp-range-between-dates-text-color: var(--dp-hover-text-color, #212121);
    --dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
}

.dp__theme_dark {
    --dp-background-color: #312d4b;
    --dp-text-color: #e0d7f2;
    --dp-hover-color: #3a3150;
    --dp-hover-text-color: #ffffff;
    --dp-hover-icon-color: #b0a7d6;
    --dp-primary-color: #7c4dff;
    --dp-primary-disabled-color: #a186ff;
    --dp-primary-text-color: #ffffff;
    --dp-secondary-color: #9e9e9e;
    --dp-border-color: #3a3150;
    --dp-menu-border-color: #3a3150;
    --dp-border-color-hover: #7c4dff;
    --dp-border-color-focus: #7c4dff;
    --dp-disabled-color: #555072;
    --dp-disabled-color-text: #aaa0d4;
    --dp-scroll-bar-background: #1e1b2e;
    --dp-scroll-bar-color: #3a3150;
    --dp-success-color: #00e676;
    --dp-success-color-disabled: #66ffa6;
    --dp-icon-color: #b0a7d6;
    --dp-danger-color: #ff1744;
    --dp-marker-color: #ff1744;
    --dp-tooltip-color: #2b2440;
    --dp-highlight-color: rgba(124, 77, 255, 0.2);
    --dp-range-between-dates-background-color: var(--dp-hover-color);
    --dp-range-between-dates-text-color: var(--dp-text-color);
    --dp-range-between-border-color: var(--dp-primary-color);
}
</style>
