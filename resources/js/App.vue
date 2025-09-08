<script setup>
import { useAuthStore } from './stores/auth';
import { listenRolePermissionChangesEvent } from './composables/events/rolePermissionsChanges';
import { listenUserRoleChangesEvent } from './composables/events/roleChanges';
import { listenCalendarNotifications } from './composables/events/calendarEventsChange';
import { listenExportFileReady } from './composables/events/exportFileReady';
import { checkMissDownloadedFiles } from './composables/files/exportFilesCheck';


const authStore = useAuthStore();
authStore.requestAuth();

listenRolePermissionChangesEvent();
listenUserRoleChangesEvent();

listenCalendarNotifications();

listenExportFileReady();
checkMissDownloadedFiles();
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
