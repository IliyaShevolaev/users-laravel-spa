<script setup>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import axios from "axios";

const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    locale: ruLocale,
    headerToolbar: {
        left: "dayGridMonth,dayGridWeek,dayGridDay",
        center: "title",
        right: "today prev,next",
    },

    events: (fetchInfo, successCallback, failureCallback) => {
        axios
            .get("/api/events", {
                params: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                },
            })
            .then((response) => {
                successCallback(response.data.data);
            })
            .catch((error) => {
                console.log(error);
            });
    },
});
</script>

<template>
    <FullCalendar :options="calendarOptions" />
</template>
