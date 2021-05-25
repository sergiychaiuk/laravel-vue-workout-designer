<template>
    <v-container>
        <v-row>
            <v-col>
                <v-form @submit.prevent="submit" method="post">
                    <v-container>
                        <v-row
                            align="center"
                        >
                            <v-col
                                cols="6"
                                sm="8"
                                md="8"
                                lg="8"
                                xl="8"
                            >
                                <div class="text-h5 text-sm-h4 text-md-h3 text-lg-h3 text-xl-h3 font-weight-bold blue--text text--darken-2">
                                    {{ userName }}
                                </div>
                            </v-col>
                            <v-col
                                class="text-end"
                                cols="6"
                                sm="4"
                                md="4"
                                lg="4"
                                xl="4"
                            >
                                <v-btn
                                    color="primary"
                                    type="submit"
                                    :loading="loading"
                                >
                                    Зберегти
                                    <v-icon>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card
                        :loading="loading"
                        :disabled="loading"
                    >
                        <v-tabs v-model="tab">
                            <v-tab>Інформація</v-tab>
                            <v-tab>Фізичні дані</v-tab>
                        </v-tabs>
                        <v-tabs-items v-model="tab">
                            <v-tab-item>
                                <v-card flat>
                                    <v-container>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                md="12"
                                                lg="8"
                                                xl="8"
                                            >
                                                <v-card
                                                    outlined
                                                >
                                                    <v-container>
                                                        <v-row>
                                                            <v-col>
                                                                <v-alert
                                                                    border="left"
                                                                    color="blue"
                                                                    text
                                                                    type="info"
                                                                >Ці значення важливі для розрахунку норм калорій і БЖУ</v-alert>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-select
                                                                    :items="sex"
                                                                    label="Стать"
                                                                    v-model="user.sex"
                                                                    outlined
                                                                    append-outer-icon="mdi-human-male-female"
                                                                    hide-details="auto"
                                                                    :error-messages="sexErrors"
                                                                    @input="$v.user.sex.$touch()"
                                                                    @blur="$v.user.sex.$touch()"
                                                                ></v-select>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-select
                                                                    :items="actives"
                                                                    item-text="name"
                                                                    item-value="value"
                                                                    v-model="user.active"
                                                                    label="Активність"
                                                                    outlined
                                                                    append-outer-icon="mdi-handball"
                                                                    hide-details="auto"
                                                                    :error-messages="activeErrors"
                                                                    @input="$v.user.active.$touch()"
                                                                    @blur="$v.user.active.$touch()"
                                                                ></v-select>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-select
                                                                    :items="goals"
                                                                    item-text="name"
                                                                    item-value="value"
                                                                    v-model="user.goal"
                                                                    label="Ціль"
                                                                    outlined
                                                                    append-outer-icon="mdi-flag-checkered"
                                                                    hide-details="auto"
                                                                    :error-messages="goalErrors"
                                                                    @input="$v.user.goal.$touch()"
                                                                    @blur="$v.user.goal.$touch()"
                                                                ></v-select>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-text-field
                                                                    label="Вік"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.age"
                                                                    min="0"
                                                                    append-icon="mdi-human-male-boy"
                                                                    hide-details="auto"
                                                                    :error-messages="ageErrors"
                                                                    @input="$v.user.age.$touch()"
                                                                    @blur="$v.user.age.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-text-field
                                                                    label="Вага"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.weight"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-weight-kilogram"
                                                                    hide-details="auto"
                                                                    :error-messages="weightErrors"
                                                                    @input="$v.user.weight.$touch()"
                                                                    @blur="$v.user.weight.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="4"
                                                                lg="4"
                                                                xl="4"
                                                            >
                                                                <v-text-field
                                                                    label="Зріст"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.height"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-human-male-height"
                                                                    hide-details="auto"
                                                                    :error-messages="heightErrors"
                                                                    @input="$v.user.height.$touch()"
                                                                    @blur="$v.user.height.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                    <v-divider></v-divider>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col
                                                                cols="6"
                                                            >
                                                                <v-text-field
                                                                    label="Ім`я"
                                                                    v-model="user.name"
                                                                    outlined
                                                                    hide-details="auto"
                                                                    :error-messages="nameErrors"
                                                                    @input="$v.user.name.$touch()"
                                                                    @blur="$v.user.name.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="6"
                                                            >
                                                                <v-text-field
                                                                    label="Прізвище"
                                                                    v-model="user.surname"
                                                                    outlined
                                                                    hide-details="auto"
                                                                    :error-messages="surnameErrors"
                                                                    @input="$v.user.surname.$touch()"
                                                                    @blur="$v.user.surname.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                md="12"
                                                lg="4"
                                                xl="4"
                                            >
                                                <v-card
                                                    outlined
                                                >
                                                     <v-container>
                                                         <v-row>
                                                             <v-col
                                                                cols="8"
                                                             >Норма калорій в день</v-col>
                                                             <v-col
                                                                 cols="4"
                                                                 class="font-weight-bold">{{ Math.round(calories) }}</v-col>
                                                         </v-row>
                                                     </v-container>
                                                    <v-divider></v-divider>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col
                                                                cols="8"
                                                            >Норма білків в день</v-col>
                                                            <v-col
                                                                cols="4"
                                                                class="font-weight-bold">{{ Math.round(proteins) }} g</v-col>
                                                        </v-row>
                                                    </v-container>
                                                    <v-divider></v-divider>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col
                                                                cols="8"
                                                            >Норма жирів в день</v-col>
                                                            <v-col
                                                                cols="4"
                                                                class="font-weight-bold">{{ Math.round(fats) }} g</v-col>
                                                        </v-row>
                                                    </v-container>
                                                    <v-divider></v-divider>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col
                                                                cols="8"
                                                            >Норма вуглеводів в день</v-col>
                                                            <v-col
                                                                cols="4"
                                                                class="font-weight-bold">{{ Math.round(carbohydrates) }} g</v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card>
                            </v-tab-item>
                            <v-tab-item>
                                <v-card flat>
                                    <v-container>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                md="12"
                                                lg="8"
                                                xl="8"
                                            >
                                                <v-card
                                                    outlined
                                                >
                                                    <v-container>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват шиї"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.neck"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="neckErrors"
                                                                    @input="$v.user.neck.$touch()"
                                                                    @blur="$v.user.neck.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват біцепса"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.biceps"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="bicepsErrors"
                                                                    @input="$v.user.biceps.$touch()"
                                                                    @blur="$v.user.biceps.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват грудей"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.breast"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="breastErrors"
                                                                    @input="$v.user.breast.$touch()"
                                                                    @blur="$v.user.breast.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват талії"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.waist"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="waistErrors"
                                                                    @input="$v.user.waist.$touch()"
                                                                    @blur="$v.user.waist.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват таза"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.pelvis"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="pelvisErrors"
                                                                    @input="$v.user.pelvis.$touch()"
                                                                    @blur="$v.user.pelvis.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват гомілки"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.shins"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="shinsErrors"
                                                                    @input="$v.user.shins.$touch()"
                                                                    @blur="$v.user.shins.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват стегна"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.tightened"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="tightenedErrors"
                                                                    @input="$v.user.tightened.$touch()"
                                                                    @blur="$v.user.tightened.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                cols="12"
                                                                sm="12"
                                                                md="6"
                                                                lg="6"
                                                                xl="6"
                                                            >
                                                                <v-text-field
                                                                    label="Обхват передпліччя"
                                                                    outlined
                                                                    type="number"
                                                                    v-model="user.forearm"
                                                                    min="0"
                                                                    step="0.1"
                                                                    append-icon="mdi-axis-z-rotate-counterclockwise"
                                                                    hide-details="auto"
                                                                    :error-messages="forearmErrors"
                                                                    @input="$v.user.forearm.$touch()"
                                                                    @blur="$v.user.forearm.$touch()"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { validationMixin } from 'vuelidate';
import { required, email } from 'vuelidate/lib/validators';

export default {
    name: "Account",
    mixins: [validationMixin],
    validations: {
        user: {
            name: { required, },
            surname: { required, },
            sex: { required, },
            active: { required, },
            goal: { required, },
            age: { required, },
            weight: { required, },
            height: { required, },
            neck: { required, },
            biceps: { required, },
            breast: { required, },
            waist: { required, },
            pelvis: { required, },
            shins: { required, },
            tightened: { required, },
            forearm: { required, },
        },
    },
    data() {
        return {
            tab: null,
            user: {
                name: this.$auth.user().name,
                surname: this.$auth.user().surname,
                sex: this.$auth.user().sex,
                active: this.$auth.user().active,
                goal: this.$auth.user().goal,
                age: this.$auth.user().age,
                weight: this.$auth.user().weight,
                height: this.$auth.user().height,
                neck: this.$auth.user().neck,
                biceps: this.$auth.user().biceps,
                breast: this.$auth.user().breast,
                waist: this.$auth.user().waist,
                pelvis: this.$auth.user().pelvis,
                shins: this.$auth.user().shins,
                tightened: this.$auth.user().tightened,
                forearm: this.$auth.user().forearm,
            },
            sex: ['Чоловік', 'Жінка'],
            actives: [
                { name: 'Майже немає активності', value: 1.2 },
                { name: 'Помірні навантаження', value: 1.375 },
                { name: 'Тренування 3-5 разів на тиждень', value: 1.55 },
                { name: 'Інтенсивні навантаження', value: 1.725 },
                { name: 'Професіональний спортсмен', value: 1.9 },
            ],
            goals: [
                { name: 'Скинути вагу', value: 0 },
                { name: 'Набрати м\'язову масу', value: 1 },
                { name: 'Підтримувати вагу', value: 2 },
            ],
            loading: false,
            calories: 0,
            proteins: 0,
            fats: 0,
            carbohydrates: 0,
        };
    },
    computed: {
        nameErrors() {
            const errors = [];
            if (!this.$v.user.name.$dirty) return errors;
            !this.$v.user.name.required && errors.push('Потрібно вказати ім\`я');
            return errors;
        },
        surnameErrors() {
            const errors = [];
            if (!this.$v.user.surname.$dirty) return errors;
            !this.$v.user.surname.required && errors.push('Потрібно вказати прізвище');
            return errors;
        },
        sexErrors() {
            const errors = [];
            if (!this.$v.user.sex.$dirty) return errors;
            !this.$v.user.sex.required && errors.push('Потрібно вказати стать');
            return errors;
        },
        activeErrors() {
            const errors = [];
            if (!this.$v.user.active.$dirty) return errors;
            !this.$v.user.active.required && errors.push('Потрібно вказати активність');
            return errors;
        },
        goalErrors() {
            const errors = [];
            if (!this.$v.user.goal.$dirty) return errors;
            !this.$v.user.goal.required && errors.push('Потрібно вказати ціль');
            return errors;
        },
        ageErrors() {
            const errors = [];
            if (!this.$v.user.age.$dirty) return errors;
            !this.$v.user.age.required && errors.push('Потрібно вказати вік');
            return errors;
        },
        weightErrors() {
            const errors = [];
            if (!this.$v.user.weight.$dirty) return errors;
            !this.$v.user.weight.required && errors.push('Потрібно вказати вагу');
            return errors;
        },
        heightErrors() {
            const errors = [];
            if (!this.$v.user.height.$dirty) return errors;
            !this.$v.user.height.required && errors.push('Потрібно вказати зріст');
            return errors;
        },
        neckErrors() {
            const errors = [];
            if (!this.$v.user.neck.$dirty) return errors;
            !this.$v.user.neck.required && errors.push('Потрібно вказати обхват шиї');
            return errors;
        },
        bicepsErrors() {
            const errors = [];
            if (!this.$v.user.biceps.$dirty) return errors;
            !this.$v.user.biceps.required && errors.push('Потрібно вказати обхват біцепса');
            return errors;
        },
        breastErrors() {
            const errors = [];
            if (!this.$v.user.breast.$dirty) return errors;
            !this.$v.user.breast.required && errors.push('Потрібно вказати обхват грудей');
            return errors;
        },
        waistErrors() {
            const errors = [];
            if (!this.$v.user.waist.$dirty) return errors;
            !this.$v.user.waist.required && errors.push('Потрібно вказати обхват талії');
            return errors;
        },
        pelvisErrors() {
            const errors = [];
            if (!this.$v.user.pelvis.$dirty) return errors;
            !this.$v.user.pelvis.required && errors.push('Потрібно вказати обхват таза');
            return errors;
        },
        shinsErrors() {
            const errors = [];
            if (!this.$v.user.shins.$dirty) return errors;
            !this.$v.user.shins.required && errors.push('Потрібно вказати обхват гомілки');
            return errors;
        },
        tightenedErrors() {
            const errors = [];
            if (!this.$v.user.tightened.$dirty) return errors;
            !this.$v.user.tightened.required && errors.push('Потрібно вказати обхват стегна');
            return errors;
        },
        forearmErrors() {
            const errors = [];
            if (!this.$v.user.forearm.$dirty) return errors;
            !this.$v.user.forearm.required && errors.push('Потрібно вказати обхват передпліччя');
            return errors;
        },
        userName: function () {
            if (this.$auth.check()) {
                return this.$auth.user().name + ' ' + this.$auth.user().surname;
            } else {
                return 'Користувач';
            }
        },
    },
    created() {
        this.CPFC();
        this.$Progress.finish();
    },
    methods: {
        CPFC() {
            if (this.user.sex === 'Чоловік') {
                this.calories = 66.5 + 13.75 * Number(this.user.weight) + 5 * Number(this.user.height) - 6.775 * Number(this.user.age);
            } else if (this.user.sex === 'Жінка') {
                this.calories = 655.1 + 9.563 * Number(this.user.weight) + 1.85 * Number(this.user.height) - 4.676 * Number(this.user.age);
            }

            this.calories = this.calories * Number(this.user.active);

            switch(Number(this.user.goal)) {
                case 0:
                    this.calories = this.calories - (this.calories * 10) / 100;
                    break;
                case 1:
                    this.calories = this.calories + (this.calories * 10) / 100;
                    break;
                default: break;
            }

            this.proteins = ((this.calories * 30) / 100) / 4;
            this.fats = ((this.calories * 30) / 100) / 9;
            this.carbohydrates = ((this.calories * 40) / 100) / 4;
        },
        submit() {
            this.$v.$touch();
            if (!this.$v.$invalid) {
                this.loading = true;
                this.$Progress.start();
                axios.post('account/update', this.user)
                    .then((res) => {
                        this.$auth.user(res.data.user);
                        this.CPFC();
                        this.$Progress.finish();
                        this.loading = false;
                    })
                    .catch(err => {
                        this.$Progress.fail();
                        this.loading = false;
                    });
            }
        },
    },
}
</script>

<style scoped>
    .v-divider, .v-alert {
        margin: 0 !important;
    }
</style>
