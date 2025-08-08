<script setup>
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    isOpen: Boolean,
    message: String,
});

const emit = defineEmits(["closeDialog", "acceptAction"]);
</script>

<template>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card prepend-icon="ri-delete-bin-fill" :title="props.message">
            <template v-slot:actions>
                <v-spacer></v-spacer>

                <v-btn
                    @click="emit('closeDialog')"
                    class="px-5"
                    color="error"
                    variant="elevated"
                >
                    {{t('main.no')}}
                </v-btn>
                <v-btn
                    @click="emit('acceptAction')"
                    class="px-5"
                    color="success"
                    variant="elevated"
                >
                {{t('main.yes')}}

                </v-btn>
            </template>
        </v-card>
    </v-dialog>
</template>
