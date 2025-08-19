<script setup>
import VerticalNavSectionTitle from "@/@layouts/components/VerticalNavSectionTitle.vue";
import VerticalNavGroup from "@layouts/components/VerticalNavGroup.vue";
import VerticalNavLink from "@layouts/components/VerticalNavLink.vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "../../stores/auth";

const authStore = useAuthStore();
const { t } = useI18n();
</script>

<template>
    <!-- 👉 User Interface -->
    <VerticalNavSectionTitle
        v-if="
            authStore.hasOneOfEachPermission(
                'users-read',
                'departments-read',
                'positions-read'
            )
        "
        :item="{
            heading: t('nav.users'),
        }"
    />
    <VerticalNavLink
        v-if="authStore.checkPermission('users-read')"
        :item="{
            title: t('nav.users'),
            icon: 'ri-group-line',
            to: '/users',
        }"
    />
    <VerticalNavLink
        v-if="authStore.checkPermission('departments-read')"
        :item="{
            title: t('nav.departments'),
            icon: 'ri-building-line',
            to: '/departments',
        }"
    />
    <VerticalNavLink
        v-if="authStore.checkPermission('positions-read')"
        :item="{
            title: t('nav.positions'),
            icon: 'ri-briefcase-line',
            to: '/positions',
        }"
    />

    <VerticalNavLink
        v-if="authStore.checkPermission('roles-read')"
        :item="{
            title: t('nav.roles'),
            icon: 'ri-admin-fill',
            to: '/roles',
        }"
    />

    <VerticalNavSectionTitle
        :item="{
            heading: t('nav.tasks'),
        }"
    />
    <VerticalNavLink
        :item="{
            title: t('nav.tasks_schedule'),
            icon: 'ri-calendar-2-line',
            to: '/tasks/calendar',
        }"
    />
</template>
