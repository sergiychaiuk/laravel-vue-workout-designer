<template>
    <v-container>
        <v-row
        justify="center">
            <v-col
                cols="12"
                sm="10"
                md="8"
                lg="6"
            >
                <v-card
                    :loading="false"
                    :disabled="false"
                    elevation="5"
                >
                    <v-card-title
                        color="primary"
                        class="text-h3 font-weight-bold blue--text text--darken-2"
                    >
                        FREEBODY
                    </v-card-title>
                    <v-card-subtitle>
                        Авторизація
                    </v-card-subtitle>
                    <v-form>
                        <v-card-actions>
                            <v-text-field
                                label="E-mail"
                                filled
                                hide-details="auto"
                                append-icon="mdi-email"
                                v-model="email"
                                :error-messages="emailErrors"
                                @input="$v.email.$touch()"
                                @blur="$v.email.$touch()"
                            ></v-text-field>
                        </v-card-actions>
                        <v-card-actions>
                            <v-text-field
                                label="Пароль"
                                filled
                                hide-details="auto"
                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show ? 'text' : 'password'"
                                @click:append="show = !show"
                                v-model.trim="password"
                                :error-messages="passwordErrors"
                                @input="$v.password.$touch()"
                                @blur="$v.password.$touch()"
                            ></v-text-field>
                        </v-card-actions>
                        <v-card-actions>
                            <v-btn
                                text
                            >
                                Головна
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="submit"
                            >
                                Увійти
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { validationMixin } from 'vuelidate';
import { required, email } from 'vuelidate/lib/validators';

export default {
    name: "Login",

    mixins: [validationMixin],

    validations: {
        email: {
            required,
            email,
        },
        password: {
            required,
        },
    },

    data () {
        return {
            email: '',
            password: '',
            show: false,
        };
    },

    computed: {
        emailErrors() {
            const errors = [];
            if (!this.$v.email.$dirty) return errors;
            !this.$v.email.email && errors.push('Має бути дійсний електронний лист');
            !this.$v.email.required && errors.push('Потрібна електронна пошта');
            return errors;
        },
        passwordErrors() {
            const errors = [];
            if (!this.$v.password.$dirty) return errors;
            !this.$v.password.required && errors.push('Потрібен пароль');
            return errors;
        },
    },

    methods: {
        submit() {
            this.$v.$touch()
        },
    },
}
</script>

<style scoped>

</style>
