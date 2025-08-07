<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import VerticalNavGroup from '@layouts/components/VerticalNavGroup.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
</script>

<template>

  <!-- 👉 User Interface -->
  <VerticalNavSectionTitle
    :item="{
      heading: 'Пользователи',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Пользователи',
      icon: 'ri-group-line',
      to: '/users',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Отделы',
      icon: 'ri-building-line',
      to: '/departments',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Должности',
      icon: 'ri-briefcase-line',
      to: '/positions',
    }"
  />
</template>
