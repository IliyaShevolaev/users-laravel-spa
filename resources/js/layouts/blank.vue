<script setup>
    import NavbarThemeSwitcher from './components/NavbarThemeSwitcher.vue';
</script>

<template>
    <div class="flex flex-row-reverse" style="display: none;">
        <NavbarThemeSwitcher></NavbarThemeSwitcher>
    </div>
  <div
    class="layout-wrapper layout-blank"
    data-allow-mismatch
  >
    <RouterView />
  </div>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
// @use "@layouts/styles/default-layout";
</style>

