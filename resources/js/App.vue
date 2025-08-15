<script setup>
import { useAuthStore } from './stores/auth';
import { listenRoleEvent } from './composables/events/roleChanges';

const authStore = useAuthStore();
authStore.requestAuth();

listenRoleEvent();
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
