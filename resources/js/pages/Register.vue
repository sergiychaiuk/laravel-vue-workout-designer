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
                        Реєстрація
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
                                label="Ім'я"
                                filled
                                hide-details="auto"
                                append-icon="mdi-account"
                                v-model.trim="name"
                                :error-messages="nameErrors"
                                @input="$v.name.$touch()"
                                @blur="$v.name.$touch()"
                            ></v-text-field>
                        </v-card-actions>
                        <v-card-actions>
                            <v-text-field
                                label="Прізвище"
                                filled
                                hide-details="auto"
                                append-icon="mdi-account"
                                v-model.trim="surname"
                                :error-messages="surnameErrors"
                                @input="$v.surname.$touch()"
                                @blur="$v.surname.$touch()"
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
                                hint="Принаймні 8 символів"
                                :error-messages="passwordErrors"
                                @input="$v.password.$touch()"
                                @blur="$v.password.$touch()"
                            ></v-text-field>
                        </v-card-actions>
                        <v-card-actions>
                            <v-text-field
                                label="Підтвердіть пароль"
                                filled
                                hide-details="auto"
                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show ? 'text' : 'password'"
                                @click:append="show = !show"
                                v-model.trim="verify"
                                hint="Пароль повинен збігатися"
                                :error-messages="verifyErrors"
                                @input="$v.verify.$touch()"
                                @blur="$v.verify.$touch()"
                            ></v-text-field>
                        </v-card-actions>
                        <v-card-actions>
                            <v-btn
                                text
                                @click="clear"
                            >
                                Очистити
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="submit"
                            >
                                Зареєструватись
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
import { required, sameAs, email, minLength } from 'vuelidate/lib/validators';

export default {
    name: "Register",

    mixins: [validationMixin],

    validations: {
        email: {
            required,
            email,
        },
        name: {
            required,
        },
        surname: {
            required,
        },
        password: {
            required,
            minLength: minLength(8),
        },
        verify: {
            required,
            sameAsPassword: sameAs('password'),
        },
    },

    data () {
        return {
            email: '',
            name: '',
            surname: '',
            password: '',
            verify: '',
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
        nameErrors() {
            const errors = [];
            if (!this.$v.name.$dirty) return errors;
            !this.$v.name.required && errors.push('Потрібно вказати ім\'я');
            return errors;
        },
        surnameErrors() {
            const errors = [];
            if (!this.$v.surname.$dirty) return errors;
            !this.$v.surname.required && errors.push('Потрібно вказати прізвище');
            return errors;
        },
        passwordErrors() {
            const errors = [];
            if (!this.$v.password.$dirty) return errors;
            !this.$v.password.minLength && errors.push('Пароль повинен бути більше 8 символів');
            !this.$v.password.required && errors.push('Потрібен пароль');
            return errors;
        },
        verifyErrors() {
            const errors = [];
            if (!this.$v.verify.$dirty) return errors;
            !this.$v.verify.sameAsPassword && errors.push('Паролі повинні бути однаковими');
            !this.$v.verify.required && errors.push('Потрібно підтвердити пароль');
            return errors;
        },
    },

    methods: {
        submit() {
            this.$v.$touch()
        },
        clear() {
            this.$v.$reset();
            this.email = '';
            this.name = '';
            this.password = '';
            this.verify = '';
        },
    },
}
</script>

<style scoped>

</style>
