<script setup>
import { computed, ref } from "vue";
import { useEventNotifyStore } from "../../stores/eventNotifies";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const eventNotifyStore = useEventNotifyStore();

const hasNotifications = computed(() => eventNotifyStore.badge > 0);
</script>

<template>
    <div class="text-center">
        <v-menu location="bottom">
            <template v-slot:activator="{ props }">
                <v-badge
                    v-bind="props"
                    bordered
                    location="bottom right"
                    offset-x="-5"
                    color="error"
                    :content="eventNotifyStore.badge"
                    :model-value="hasNotifications"
                >
                    <v-icon>ri-notification-line</v-icon>
                </v-badge>
            </template>

            <v-list>
                <v-list-item
                    v-for="(item, index) in eventNotifyStore.getNotifyRecords"
                    :key="index"
                    class="hover:bg-gray-100 rounded-lg mb-1"
                >
                    <v-list-item-content>
                        <v-list-item-title class="font-medium">
                            {{
                                `${t("calendar.yours_new_event")}: ${
                                    item.title
                                } `
                            }}
                        </v-list-item-title>
                        <v-list-item-subtitle class="text-sm">
                            {{
                                `${t("calendar.event_creator")}: ${
                                    item.creator
                                } `
                            }}

                            Создатель: {{ item.creator }}
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item
                    v-if="eventNotifyStore.getNotifyRecords.length === 0"
                    class="text-center"
                >
                    {{ t("calendar.no_new_events") }}
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>
