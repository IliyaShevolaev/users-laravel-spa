<script setup>
import { useTheme } from "vuetify";
import { onMounted } from "vue";

const props = defineProps({
    themes: {
        type: Array,
        required: true,
    },
    defaultTheme: {
        type: String,
        default: "dark",
    },
});

const { name: themeName, global: globalTheme } = useTheme();

const {
    state: currentThemeName,
    next: getNextThemeName,
    index: currentThemeIndex,
} = useCycleList(
    props.themes.map((t) => t.name),
    {
        initialValue: themeName,
    }
);

const initTheme = () => {
    const savedTheme = localStorage.getItem("vuetifyTheme");
    const initialTheme = savedTheme || props.defaultTheme;
    globalTheme.name.value = initialTheme;
    currentThemeName.value = initialTheme;
};

const changeTheme = () => {
    const newTheme = getNextThemeName();
    globalTheme.name.value = newTheme;
    localStorage.setItem("vuetifyTheme", newTheme);
};

watch(
    () => globalTheme.name.value,
    (val) => {
        currentThemeName.value = val;
        localStorage.setItem("vuetifyTheme", val);
    }
);

onMounted(() => {
    initTheme();
});
</script>

<template>
    <IconBtn @click="changeTheme">
        <VIcon :icon="props.themes[currentThemeIndex].icon" />
        <VTooltip activator="parent" open-delay="1000" scroll-strategy="close">
            <span class="text-capitalize">{{ currentThemeName }}</span>
        </VTooltip>
    </IconBtn>
</template>
