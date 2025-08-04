<template>
    <v-row class="flex justify-center">
        <v-col cols="12" md="4">
            <v-card class="pa-3 ma-3" elevation="3">
                <v-card-title primary-title class="my-4 text-h4">
                    <span
                        class="flex-fill"
                        :class="$vuetify.display.mobile ? 'text-h6' : 'text-h4'"
                        >Регистрация</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" class="text-left" lazy-validation>
                        <v-text-field
                            v-model="username"
                            :error="error"
                            label="Имя пользователя"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="username"
                            outlined
                            validateOn="blur"
                        ></v-text-field>

                        <v-text-field
                            v-model="email"
                            :error="error"
                            label="Email"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="email"
                            outlined
                            validateOn="blur"
                        ></v-text-field>

                        <v-text-field
                            v-model="password"
                            :append-inner-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            :error="error"
                            label="Пароль"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="password"
                            outlined
                            validateOn="blur"
                            @click:append-inner="showPassword = !showPassword"
                        ></v-text-field>

                        <v-text-field
                            v-model="passwordConfirmation"
                            :append-inner-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            :error="error"
                            label="Подтвердите пароль"
                            density="default"
                            variant="underlined"
                            color="primary"
                            name="password"
                            outlined
                            validateOn="blur"
                            @click:append-inner="showPassword = !showPassword"
                        ></v-text-field>

                        <v-btn
                            block
                            size="x-large"
                            color="primary"
                            class="mt-2"
                            :class="
                                $vuetify.display.mobile
                                    ? 'text-body-2'
                                    : 'text-subtitle-1'
                            "
                            @click="handleSubmit"
                        >
                            Зарегистрироваться
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>

            <div class="text-center mt-6">
                Уже есть аккаунт?
                <router-link to="/login" class="text-primary font-weight-bold">
                    Войти
                </router-link>
            </div>
        </v-col>
    </v-row>
</template>

<script>
export default {
    data() {
        return {
            username: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            showPassword: false,
            error: false,
        };
    },
    methods: {
        handleSubmit() {},
    },
};
</script>
