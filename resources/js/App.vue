<script setup>
import { useAuthStore } from './stores/auth';
import { listenRolePermissionChangesEvent } from './composables/events/rolePermissionsChanges';
import { listenUserRoleChangesEvent } from './composables/events/roleChanges';
import { listenCalendarNotifications } from './composables/events/calendarEventsChange';


const authStore = useAuthStore();
authStore.requestAuth();

listenRolePermissionChangesEvent();
listenUserRoleChangesEvent();

listenCalendarNotifications();
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
