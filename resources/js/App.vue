<script setup>
import axios from 'axios';
import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();
authStore.requestAuth();
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>
