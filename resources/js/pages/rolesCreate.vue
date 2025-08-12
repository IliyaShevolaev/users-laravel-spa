<script setup>
import axios from "axios";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useDisplay } from "vuetify";

const { mobile } = useDisplay();
const router = useRouter();
const { t } = useI18n();

const formData = reactive({
    name: null,
});

const formDataErrors = reactive({});

const permissionGroups = ref([
    {
        key: "users",
        name: t("users.roles.entities.users"),
        permissions: { read: false, create: false, update: false, delete: false },
    },
    {
        key: "departments",
        name: t("users.roles.entities.departments"),
        permissions: { read: false, create: false, update: false, delete: false },
    },
    {
        key: "positions",
        name: t("users.roles.entities.positions"),
        permissions: { read: false, create: false, update: false, delete: false },
    },
    {
        key: "roles",
        name: t("users.roles.entities.roles"),
        permissions: { read: false, create: false, update: false, delete: false },
    },
]);

function addRole() {
    const permissionsArray = [];

    for (let group of permissionGroups.value) {
        for (let permission in group.permissions) {
            if (group.permissions[permission]) {
                permissionsArray.push(`${group.key}-${permission}`);
            }
        }
    }

    const bodyData = {
        name: formData.name,
        permissions: permissionsArray,
    };

    console.log(bodyData);

    axios
        .post("/api/roles", bodyData)
        .then((response) => {
            console.log(response);
            router.push("/roles");
        })
        .catch((error) => {
            console.log(error);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            }
        });
}

const cancel = function () {
    router.back();
};
</script>
<template>
    <v-row>
        <v-col cols="10" class="mx-auto">
            <v-card class="pa-5">
                <h2 class="text-2xl font-bold mb-4">
                    {{ t("users.roles.add") }}
                </h2>

                <v-text-field
                    v-model="formData.name"
                    :label="t('users.roles.name_placeholder')"
                    :error="!!formDataErrors.name"
                    :error-messages="formDataErrors.name"
                    outlined
                    class="mb-6"
                />

                <div
                    v-for="group in permissionGroups"
                    :key="group.key"
                    class="border rounded-lg pa-2 mb-4"
                >
                    <h3 class="text-lg font-semibold mb-2">
                        {{ group.name }}
                    </h3>

                    <v-row>
                        <v-col cols="6" sm="3">
                            <v-checkbox
                                v-model="group.permissions.read"
                                label="Смотреть"
                                density="compact"
                                hide-details
                            />
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-checkbox
                                v-model="group.permissions.update"
                                label="Редактировать"
                                density="compact"
                                hide-details
                            />
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-checkbox
                                v-model="group.permissions.create"
                                label="Создавать"
                                density="compact"
                                hide-details
                            />
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-checkbox
                                v-model="group.permissions.delete"
                                label="Удалять"
                                density="compact"
                                hide-details
                            />
                        </v-col>
                    </v-row>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <v-btn
                        color="error"
                        :size="mobile ? 'small' : 'default'"
                        @click="cancel"
                    >
                        {{ t("main.cancel_button") }}
                    </v-btn>

                    <v-btn
                        color="success"
                        :size="mobile ? 'small' : 'default'"
                        @click="addRole"
                    >
                        {{ t("main.append_button") }}
                    </v-btn>
                </div>
            </v-card>
        </v-col>
    </v-row>
</template>
<style></style>
