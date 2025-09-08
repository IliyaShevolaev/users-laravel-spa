<script setup>
import { computed, ref } from "vue";
import { useEventNotifyStore } from "../../stores/eventNotifies";
import { useExportNotifyStore } from "../../stores/exportNotifies";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const eventNotifyStore = useEventNotifyStore();
const exportNotifyStore = useExportNotifyStore();

const hasNotifications = computed(
    () => eventNotifyStore.badge > 0 || exportNotifyStore.badge > 0
);
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
                    :content="eventNotifyStore.badge + exportNotifyStore.badge"
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
                    <v-list-item-title class="font-medium">
                        <v-icon>{{ item.icon }}</v-icon>
                        {{ item.title }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="text-sm">
                        {{ item.subtitle }}
                    </v-list-item-subtitle>
                </v-list-item>

                <v-list-item
                    v-for="(item, index) in exportNotifyStore.getNotifyRecords"
                    :key="index"
                    class="hover:bg-gray-100 rounded-lg mb-1"
                >
                    <v-list-item-title class="font-medium">
                        <v-icon>{{ item.icon }}</v-icon>
                        {{ item.title }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="text-sm">
                        {{ item.subtitle }}
                    </v-list-item-subtitle>

                    <template v-slot:append>
                        <v-list-item-action class="flex-column align-end">
                            <v-btn
                                icon
                                variant="text"
                                size="large"
                                @click="exportNotifyStore.downloadFile(item.fileName)"
                            >
                                <v-icon class="text-blue-500">
                                    ri-file-download-line
                                </v-icon>
                            </v-btn>
                        </v-list-item-action>
                    </template>
                </v-list-item>

                <v-list-item
                    v-if="
                        eventNotifyStore.getNotifyRecords.length === 0 &&
                        exportNotifyStore.getNotifyRecords.length === 0
                    "
                    class="text-center"
                >
                    {{ t("calendar.no_new_events") }}
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>
