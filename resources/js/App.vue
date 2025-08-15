<script setup>
import { useAuthStore } from './stores/auth';
import { listenRolePermissionChangesEvent } from './composables/events/rolePermissionsChanges';
import { listenUserRoleChangesEvent } from './composables/events/roleChanges';

const authStore = useAuthStore();
authStore.requestAuth();

listenRolePermissionChangesEvent();
listenUserRoleChangesEvent();
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
