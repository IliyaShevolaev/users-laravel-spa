<script setup>
import axios from "axios";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useDisplay } from "vuetify";
import { useModelChangesStore } from "../stores/modelChanges";
import { useAuthStore } from "../stores/auth";
import AlertDangerDialog from "../components/alerts/AlertDangerDialog.vue";

const { mobile } = useDisplay();
const router = useRouter();
const { t } = useI18n();
const modelChangesStore = useModelChangesStore();

const props = defineProps({
    id: {
        type: Number,
        default: null,
    },
});

const formData = reactive({
    display_name: null,
});

const formDataErrors = reactive({});

const permissionGroups = ref([
    {
        key: "users",
        name: t("users.roles.entities.users"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
            find: false,
            logs: false
        },
    },
    {
        key: "departments",
        name: t("users.roles.entities.departments"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
        },
    },
    {
        key: "positions",
        name: t("users.roles.entities.positions"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
        },
    },
    {
        key: "roles",
        name: t("users.roles.entities.roles"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
        },
    },
    {
        key: "tasks",
        name: t("users.roles.entities.tasks"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
            stats: false,
        },
    },
    {
    key: "cities",
        name: t("users.roles.entities.cities"),
        permissions: {
            read: false,
            create: false,
            update: false,
            delete: false,
        },
    }
]);

const editRequest = function (id) {
    axios
        .get(`/api/roles/${id}/edit`)
        .then((response) => {
            console.log(response);
            formData.display_name = response.data.data.name;

            permissionGroups.value.forEach((group) => {
                for (let perm in group.permissions) {
                    group.permissions[perm] = false;
                }
            });

            response.data.data.permissions.forEach((permission) => {
                const [entity, action] = permission.name.split("-");
                const group = permissionGroups.value.find(
                    (group) => group.key === entity
                );
                if (group && action in group.permissions) {
                    group.permissions[action] = true;
                }
            });

            lastSelect.value = allSelectCheck();
        })
        .catch((error) => {
            if (error.status === 404) {
                router.push("/roles");
            }
        });
};

if (props.id !== null) {
    console.log("edit mode");
    editRequest(props.id);
}

const processPermissionsBeforeRequest = function () {
    const permissionsArray = [];

    for (let group of permissionGroups.value) {
        for (let permission in group.permissions) {
            if (group.permissions[permission]) {
                permissionsArray.push(`${group.key}-${permission}`);
            }
        }
    }

    return permissionsArray;
};

const addRole = function () {
    const bodyData = {
        display_name: formData.display_name,
        permissions: processPermissionsBeforeRequest(),
    };
    console.log(bodyData);

    axios
        .post("/api/roles", bodyData)
        .then((response) => {
            console.log(response);
            modelChangesStore.addRole(formData.display_name);
            modelChangesStore.setRoleBetweenPagesMethod("create");
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
                if (errors.name) {
                    formDataErrors.display_name = errors.name;
                }
                console.log(formDataErrors);
            }
        });
};

const updateRole = function () {
    const bodyData = {
        display_name: formData.display_name,
        permissions: processPermissionsBeforeRequest(),
    };

    axios
        .patch(`/api/roles/${props.id}`, bodyData)
        .then((response) => {
            modelChangesStore.editRole(formData.display_name);
            modelChangesStore.setRoleBetweenPagesMethod("update");
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
                if (errors.name) {
                    formDataErrors.display_name = errors.name;
                }
                console.log(formDataErrors);
            }
        });
};

const cancel = function () {
    router.push("/roles");
};

const showAlertDialog = ref(false);
const alertText = ref("");

const allSelectCheck = function () {
    return permissionGroups.value.some((group) =>
        Object.values(group.permissions).some((val) => !val)
    );
};

const lastSelect = ref(true);

const selectAll = function () {
    permissionGroups.value.forEach((group) => {
        Object.keys(group.permissions).forEach((key) => {
            group.permissions[key] = lastSelect.value;
        });
    });

    lastSelect.value = !lastSelect.value;
};
</script>
<template>
    <AlertDangerDialog
        @close-dialog="showAlertDialog = false"
        :is-open="showAlertDialog"
        :message="alertText"
    ></AlertDangerDialog>
    <v-row>
        <v-col cols="10" class="mx-auto">
            <v-card class="pa-5">
                <h2 class="text-2xl font-bold mb-4">
                    {{
                        props.id ? t("users.roles.edit") : t("users.roles.add")
                    }}
                </h2>

                <v-text-field
                    v-model="formData.display_name"
                    :label="t('users.roles.name_placeholder')"
                    :error="!!formDataErrors.display_name"
                    :error-messages="formDataErrors.display_name"
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
                    <v-col
                        v-for="(value, permissionKey) in group.permissions"
                        :key="permissionKey"
                        cols="6"
                        lg="3"
                    >
                        <v-checkbox
                            v-model="group.permissions[permissionKey]"
                            :label="t(`users.roles.${permissionKey}`)"
                            density="compact"
                            hide-details
                        />
                    </v-col>
                </v-row>
            </div>

                <div>
                    <span
                        @click="selectAll(true)"
                        class="text-l font-bold cursor-pointer select-none"
                    >
                        {{
                            lastSelect
                                ? t("users.roles.choice_all")
                                : t("users.roles.unchoice_all")
                        }}
                    </span>
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
                        v-if="props.id"
                        color="warning"
                        :size="mobile ? 'small' : 'default'"
                        @click="updateRole"
                    >
                        {{ t("main.edit_button") }}
                    </v-btn>

                    <v-btn
                        v-else
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
