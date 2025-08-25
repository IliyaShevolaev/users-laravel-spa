<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    modelValue: String,
    label: String,
});

const emit = defineEmits(["update:modelValue"]);

const showMenu = ref(false);
const parentValue = ref(props.modelValue);

watch(parentValue, (localValue) => emit("update:modelValue", localValue));
watch(
    () => props.modelValue,
    (localValue) => (parentValue.value = localValue)
);
</script>

<template>
    <v-menu
        v-model="showMenu"
        :close-on-content-click="false"
        transition="scale-transition"
    >
        <template #activator="{ props }">
            <v-text-field
                v-bind="props"
                v-model="parentValue"
                :label="label"
                :readonly="true"
            />
        </template>

        <v-time-picker
            v-model="parentValue"
            format="24hr"
            @click:hour="$nextTick(() => (showMenu = true))"
            @click:minute="$nextTick(() => (showMenu = true))"
        />
    </v-menu>
</template>
