<script setup>
import axios from "axios";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../../stores/modelChanges";
import AlertDangerDialog from "../../alerts/AlertDangerDialog.vue";
import { VFileUpload } from "vuetify/labs/VFileUpload";

const modelChangesStore = useModelChangesStore();

const { t } = useI18n();

const props = defineProps({
    userId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const fileFormats = ref(["docx", "pdf"]);

const formData = reactive({
    template_id: null,
    user_id: null,
    format: null,
});

const formDataErrors = reactive({});

const close = function (startFileGenerating = false) {
    clearFields(formData);
    clearFields(formDataErrors);
    emit("closeDialog", startFileGenerating);
};

const loadingUsers = ref(false);
const templates = ref([]);

const requestStartData = function () {
    loadingUsers.value = true;
    axios
        .get(`/api/files/templates`)
        .then((response) => {
            console.log(response);
            templates.value = response.data.data;
        })
        .finally(() => {
            loadingUsers.value = false;
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            requestStartData();
        }
    }
);

const requestResultFile = function (id) {
    formData.user_id = id;

    axios
        .post("/api/files/templates/generate-document", formData)
        .then((response) => {
            console.log(response);
            close(true);
        });
};

const clearFields = function (obj) {
    Object.keys(obj).forEach((key) => {
        obj[key] = null;
    });
};

const showAlertDialog = ref(false);
const alertText = ref("");
</script>

<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>
    <v-dialog v-model="props.isOpen" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline flex justify-between">
                    {{ t("users.file_templates.generate") }}
                    <v-btn
                        icon
                        variant="text"
                        size="large"
                        @click="close(false)"
                    >
                        <v-icon size="30" class="text-gray-900">
                            ri-close-line
                        </v-icon>
                    </v-btn>
                </span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="requestResultFile">
                    <v-skeleton-loader
                        v-if="loadingUsers"
                        type="list-item"
                    ></v-skeleton-loader>
                    <v-select
                        v-else
                        :label="t('file_template.template')"
                        v-model="formData.template_id"
                        :items="templates"
                        item-title="name"
                        variant="underlined"
                        item-value="id"
                        density="comfortable"
                    />
                    <v-select
                        class="mt-2"
                        :label="t('file_template.format')"
                        v-model="formData.format"
                        :items="fileFormats"
                        variant="underlined"
                        density="comfortable"
                    />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close()">
                    {{ t("main.close_button") }}
                </v-btn>
                <v-btn
                    variant="elevated"
                    text
                    color="office-word"
                    @click="requestResultFile(props.userId)"
                    style="text-transform: none"
                >
                    {{ t("file_template.get_template") }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
