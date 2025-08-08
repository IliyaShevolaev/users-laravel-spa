<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import VerticalNavGroup from '@layouts/components/VerticalNavGroup.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
import { useI18n } from "vue-i18n";

const { t } = useI18n();
</script>

<template>

  <!-- 👉 User Interface -->
  <VerticalNavSectionTitle
    :item="{
      heading: t('nav.users'),
    }"
  />
  <VerticalNavLink
    :item="{
      title: t('nav.users'),
      icon: 'ri-group-line',
      to: '/users',
    }"
  />
  <VerticalNavLink
    :item="{
      title: t('nav.departments'),
      icon: 'ri-building-line',
      to: '/departments',
    }"
  />
  <VerticalNavLink
    :item="{
      title: t('nav.positions'),
      icon: 'ri-briefcase-line',
      to: '/positions',
    }"
  />
</template>
