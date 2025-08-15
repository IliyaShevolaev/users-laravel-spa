<script setup>
import axios from "axios";
import { computed, reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useModelChangesStore } from "../../stores/modelChanges";
import { useAuthStore } from "../../stores/auth";
import AlertDangerDialog from "../alerts/AlertDangerDialog.vue";

const authStore = useAuthStore();

const modelChangesStore = useModelChangesStore();
const { t } = useI18n();

const showPassword = ref(false);

const props = defineProps({
    editId: {
        Number,
        default: null,
    },
    isOpen: Boolean,
});
const emit = defineEmits(["closeDialog"]);

const formData = reactive({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: null,
    gender: null,
    status: null,
    department_id: null,
    position_id: null,
});

const formDataErrors = reactive({});

const userGenders = ref([]);
const userStatuses = ref([]);
const userDepartments = ref([]);
const userPositions = ref([]);
const userRoles = ref([]);

const requestCreateUserData = function () {
    axios.get("/api/users/create").then((response) => {
        console.log(response);
        userGenders.value = response.data.genders;
        userStatuses.value = response.data.statuses;
        userDepartments.value = response.data.departments;
        userPositions.value = response.data.positions;
        if (authStore.checkPermission("roles-update")) {
            userRoles.value = response.data.roles;
        }
    });
};
requestCreateUserData();

const userDepartmentsComputed = computed(() => {
    return userDepartments.value.map((department) => ({
        text: department.name,
        value: department.id,
    }));
});

const userPositionsComputed = computed(() => {
    return userPositions.value.map((position) => ({
        text: position.name,
        value: position.id,
    }));
});

const close = function (dataChanged, method) {
    clearFields(formData);
    clearFields(formDataErrors);
    console.log(formDataErrors);
    emit("closeDialog", dataChanged, method);
};

const add = function () {
    axios
        .post("/api/users", formData)
        .then((response) => {
            modelChangesStore.addUser(formData.name);
            close(true, "add");
        })
        .catch((error) => {
            console.log(error);
            clearFields(formDataErrors);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            } else {
                console.log(error);
            }
        });
};

const edit = function () {
    clearFields(formData);
    axios
        .get(`/api/users/${props.editId}/edit`, formData)
        .then((response) => {
            console.log("response.data");
            console.log(response.data);
            Object.keys(response.data.user).forEach((key) => {
                formData[key] = response.data.user[key];
            });

            if (authStore.checkPermission("roles-update")) {
                axios
                    .get(`/api/users/role/${response.data.user.id}`)
                    .then((res) => {
                        console.log(res.data);
                        if (res.data != null) {
                            formData.role = res.data.id;
                        }
                    });
            }
        })
        .catch((error) => {
            if (error.status === 404) {
                showAlertDialog.value = true;
                alertText.value = t("users.no_selected");
                close(false);
            }
            console.log(error);
        });
};

const update = function (id) {
    axios
        .patch(`/api/users/${id}`, formData)
        .then((response) => {
            modelChangesStore.editUser(formData.name);
            close(true, "edit");
        })
        .catch((error) => {
            clearFields(formDataErrors);
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);
                for (error in errors) {
                    formDataErrors[error] = errors[error][0];
                }
                console.log(formDataErrors);
            } else {
                console.log(error);
            }
        });
};

watch(
    () => props.isOpen,
    (newValue, oldValue) => {
        if (newValue === true && oldValue === false) {
            clearFields(formData);
            if (props.editId !== null) {
                edit();
            }
        }
    }
);

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
                    {{
                        props.editId
                            ? t("users.edit_button")
                            : t("users.add_button")
                    }}
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
                <v-form @submit.prevent="add">
                    <v-text-field
                        v-model="formData.name"
                        :error="!!formDataErrors.name"
                        :error-messages="formDataErrors.name"
                        :label="t('users.name')"
                        class="mt-2"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="name"
                        outlined
                        validateOn="blur"
                    ></v-text-field>

                    <v-text-field
                        v-model="formData.email"
                        :error="!!formDataErrors.email"
                        :error-messages="formDataErrors.email"
                        class="mt-2"
                        :label="t('users.email')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="email"
                        outlined
                        validateOn="blur"
                    ></v-text-field>

                    <v-text-field
                        v-model="formData.password"
                        :append-inner-icon="
                            showPassword ? 'mdi-eye' : 'mdi-eye-off'
                        "
                        :type="showPassword ? 'text' : 'password'"
                        :error="!!formDataErrors.password"
                        :error-messages="formDataErrors.password"
                        class="mt-2"
                        :label="t('users.password')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="password"
                        outlined
                        validateOn="blur"
                        @click:append-inner="showPassword = !showPassword"
                    ></v-text-field>

                    <v-text-field
                        v-model="formData.password_confirmation"
                        :append-inner-icon="
                            showPassword ? 'mdi-eye' : 'mdi-eye-off'
                        "
                        :type="showPassword ? 'text' : 'password'"
                        :error="!!formDataErrors.password"
                        :error-messages="formDataErrors.password"
                        class="mt-2"
                        :label="t('users.password')"
                        density="default"
                        variant="underlined"
                        color="primary"
                        name="password_confirmation"
                        outlined
                        validateOn="blur"
                        @click:append-inner="showPassword = !showPassword"
                    ></v-text-field>

                    <v-select
                        v-if="authStore.checkPermission('roles-update')"
                        v-model="formData.role"
                        :items="userRoles"
                        :error="!!formDataErrors.role"
                        :error-messages="formDataErrors.role"
                        class="mt-2"
                        item-title="name"
                        variant="underlined"
                        item-value="id"
                        :label="t('users.role')"
                        clearable
                    ></v-select>

                    <v-select
                        v-model="formData.gender"
                        :items="userGenders"
                        :error="!!formDataErrors.gender"
                        :error-messages="formDataErrors.gender"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('users.gender')"
                        required
                    ></v-select>

                    <v-select
                        v-model="formData.status"
                        :items="userStatuses"
                        :error="!!formDataErrors.status"
                        :error-messages="formDataErrors.status"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('users.status')"
                        required
                    ></v-select>

                    <v-select
                        v-model="formData.department_id"
                        :items="userDepartmentsComputed"
                        :error="!!formDataErrors.department_id"
                        :error-messages="formDataErrors.department_id"
                        class="mt-2"
                        item-title="text"
                        variant="underlined"
                        item-value="value"
                        :label="t('users.department')"
                        clearable
                    ></v-select>

                    <v-select
                        v-model="formData.position_id"
                        :items="userPositionsComputed"
                        item-title="text"
                        item-value="value"
                        :error="!!formDataErrors.position_id"
                        :error-messages="formDataErrors.position_id"
                        class="mt-2"
                        variant="underlined"
                        :label="t('users.position')"
                        clearable
                    ></v-select>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="error" variant="elevated" @click="close(false)">
                    {{ t("main.close_button") }}
                </v-btn>
                <v-btn
                    v-if="props.editId !== null"
                    variant="elevated"
                    color="warning"
                    text
                    @click="update(props.editId)"
                >
                    {{ t("main.edit_button") }}
                </v-btn>
                <v-btn
                    v-else
                    variant="elevated"
                    color="success"
                    text
                    @click="add"
                >
                    {{ t("main.append_button") }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
