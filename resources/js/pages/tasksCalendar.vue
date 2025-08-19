<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";

const events = ref([]);

let abortController = null;

const requestEvents = (start, end) => {
    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();

    const params = { start, end };

    axios
        .get("/api/events", {
            params,
            signal: abortController.signal,
        })
        .then((response) => {
            events.value = response.data.data;
        });
};

const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    locale: ruLocale,

    events: events,

    datesSet: (dateInfo) => {
        requestEvents(dateInfo.startStr, dateInfo.endStr);
    },
});
</script>

<template>
    <FullCalendar :options="calendarOptions" />
</template>
