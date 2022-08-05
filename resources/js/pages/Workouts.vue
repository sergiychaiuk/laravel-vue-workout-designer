<template>
    <v-container>
        <v-row>
            <v-col>
                <v-row
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="6"
                        md="6"
                        lg="6"
                    >
                        <v-row
                            align="center"
                        >
                            <v-col
                                cols="10"
                            >
                                <v-text-field
                                    solo
                                    rounded
                                    label="Пошук"
                                    prepend-inner-icon="mdi-magnify"
                                    hide-details="auto"
                                    v-model="nameWorkout"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="2"
                            >
                                <v-dialog
                                    v-model="dialog"
                                    persistent
                                    max-width="500px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            text
                                            icon
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                            <v-icon>
                                                mdi-filter-variant
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-title class="title text-h5">
                                            Фільтр
                                        </v-card-title>
                                        <v-card-text class="pb-0 px-2">
                                            <v-container>
                                                <v-row
                                                    align="center"
                                                >
                                                    <v-col
                                                        class="pb-0"
                                                        cols="7"
                                                        sm="8"
                                                        md="9"
                                                        lg="9"
                                                    >
                                                        <v-select
                                                            label="Сортувати за"
                                                            outlined
                                                            dense
                                                            hide-details="auto"
                                                            v-model="sortBy"
                                                            :items="sortByItems"
                                                            item-text="name"
                                                            item-value="value"
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col
                                                        cols="5"
                                                        sm="4"
                                                        md="3"
                                                        lg="3"
                                                        class="text-center pb-0"
                                                    >
                                                        <v-btn-toggle
                                                            v-model="sortDesc"
                                                            dense
                                                            color="primary"
                                                            rounded
                                                        >
                                                            <v-btn
                                                                :value="false"
                                                                x-small
                                                            >
                                                                <v-icon small>mdi-arrow-up</v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                :value="true"
                                                                x-small
                                                            >
                                                                <v-icon small>mdi-arrow-down</v-icon>
                                                            </v-btn>
                                                        </v-btn-toggle>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="pb-0">
                                                        <v-select
                                                            label="Стиль"
                                                            outlined
                                                            dense
                                                            hide-details="auto"
                                                            :items="workoutStyleItems"
                                                            item-text="name"
                                                            item-value="id"
                                                            v-model="workoutStyles"
                                                        >
                                                        </v-select>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                color="primary"
                                                text
                                                @click="dialog = false"
                                            >Закрити</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row v-if="$auth.check(2)" justify="center">
                    <v-dialog
                        v-model="createDialog"
                        persistent
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                small
                                color="cyan"
                                dark
                                rounded
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon small>
                                    mdi-plus
                                </v-icon>
                                Створити
                            </v-btn>
                        </template>
                        <v-card
                            :loading="createDialogLoading"
                            :disabled="createDialogLoading"
                        >
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                                @submit.prevent="create" method="post"
                            >
                                <v-card-title class="title text-h5">
                                    Створення тренування
                                </v-card-title>
                                <v-card-text class="pb-0 px-2">
                                    <v-container>
                                        <v-row>
                                            <v-col class="pb-0">
                                                <v-text-field
                                                    label="Назва"
                                                    v-model.trim="createWorkout.name"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="nameErrors"
                                                    @input="$v.createWorkout.name.$touch()"
                                                    @blur="$v.createWorkout.name.$touch()"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col class="pb-0">
                                                <v-select
                                                    label="Стиль"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    v-model="createWorkout.workout_style_id"
                                                    :items="workoutStyleItems"
                                                    item-text="name"
                                                    item-value="id"
                                                    :error-messages="styleErrors"
                                                    @input="$v.createWorkout.workout_style_id.$touch()"
                                                    @blur="$v.createWorkout.workout_style_id.$touch()"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col class="pb-0">
                                                <v-select
                                                    label="Статус"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    v-model="createWorkout.is_published"
                                                    :items="publishItems"
                                                    item-text="name"
                                                    item-value="id"
                                                    :error-messages="publishErrors"
                                                    @input="$v.createWorkout.is_published.$touch()"
                                                    @blur="$v.createWorkout.is_published.$touch()"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-card>
                                                    <v-container class="groups-exercises mb-4">
                                                        <v-row align="center">
                                                            <v-card-subtitle>
                                                                Групи вправ
                                                                <v-btn
                                                                    color="white"
                                                                    icon
                                                                    small
                                                                    @click="addExercisesGroup"
                                                                >
                                                                    <v-icon small>
                                                                        mdi-plus
                                                                    </v-icon>
                                                                </v-btn>
                                                            </v-card-subtitle>
                                                            <v-spacer></v-spacer>
                                                            <v-card-actions class="pa-4">
                                                                <v-btn-toggle
                                                                    v-model="swapExercisesGroup"
                                                                    color="primary"
                                                                    rounded
                                                                >
                                                                    <v-btn
                                                                        :value="true"
                                                                        x-small
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-swap-vertical
                                                                        </v-icon>
                                                                    </v-btn>
                                                                    <v-btn
                                                                        :value="false"
                                                                        x-small
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-delete
                                                                        </v-icon>
                                                                    </v-btn>
                                                                </v-btn-toggle>
                                                            </v-card-actions>
                                                        </v-row>
                                                    </v-container>
                                                    <v-container>
                                                        <draggable
                                                            v-model="createWorkout.exercises_groups"
                                                            animation="200"
                                                            handle=".my-handle"
                                                            @change="updateOrder"
                                                        >
                                                            <v-row
                                                                v-for="(exercises_group, index) in createWorkout.exercises_groups"
                                                                :key="index"
                                                            >
                                                                <v-col class="d-flex align-center pt-0">
                                                                    <v-text-field
                                                                        class="mr-2"
                                                                        v-model.trim="exercises_group.name"
                                                                        outlined
                                                                        dense
                                                                        elevation="0"
                                                                        hide-details="auto"
                                                                        :rules="[v => !!v]"
                                                                    ></v-text-field>
                                                                    <v-btn
                                                                        class="my-handle"
                                                                        v-if="swapExercisesGroup"
                                                                        color="primary"
                                                                        fab
                                                                        x-small
                                                                        elevation="0"
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-arrow-all
                                                                        </v-icon>
                                                                    </v-btn>
                                                                    <v-btn
                                                                        v-if="!swapExercisesGroup"
                                                                        color="error"
                                                                        fab
                                                                        x-small
                                                                        elevation="0"
                                                                        :disabled="createWorkout.exercises_groups.length===1"
                                                                        @click="deleteExercisesGroup(index)"
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-delete
                                                                        </v-icon>
                                                                    </v-btn>
                                                                </v-col>
                                                            </v-row>
                                                        </draggable>
                                                    </v-container>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="primary"
                                        text
                                        @click="closeCreateDialog"
                                    >Закрити</v-btn>
                                    <v-btn
                                        color="success"
                                        text
                                        :loading="createDialogLoading"
                                        type="submit"
                                    >Створити</v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-row>
                <v-row
                    v-if="$auth.check(2)"
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="12"
                        md="10"
                        lg="10"
                    >
                        <div>
                            <v-icon
                                color="error"
                            >
                                mdi-lock
                            </v-icon>
                             - не опубліковано
                        </div>
                        <div>
                            <v-icon
                                color="success"
                            >
                                mdi-lock-open
                            </v-icon>
                            - опубліковано
                        </div>
                    </v-col>
                </v-row>
                <v-row
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="12"
                        md="10"
                        lg="10"
                    >
                        <v-dialog
                            v-model="deleteDialog"
                            persistent
                            max-width="330"
                        >
                            <v-card
                                :loading="loadingDeleteDialog"
                                :disabled="loadingDeleteDialog"
                            >
                                <v-card-title class="text-h5">
                                    Видалити тренування з Вашого списку?
                                </v-card-title>
                                <v-card-text>Тренування не можна буде відновити.</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="primary"
                                        text
                                        @click="deleteDialog = false"
                                    >
                                        Скасувати
                                    </v-btn>
                                    <v-btn
                                        color="error"
                                        text
                                        :loading="loadingDeleteDialog"
                                        @click="deleteWorkoutConfirm"
                                    >
                                        Видалити
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog
                            v-model="addDialog"
                            persistent
                            max-width="290"
                        >
                            <v-card
                                :loading="loadingAddDialog"
                                :disabled="loadingAddDialog"
                            >
                                <v-card-title class="text-h5">
                                    Додати тренування у Ваш список?
                                </v-card-title>
                                <v-card-text>Тренування можна буде переглянути на сторінці "Мої тренування".</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="primary"
                                        text
                                        @click="addDialog = false"
                                    >
                                        Скасувати
                                    </v-btn>
                                    <v-btn
                                        color="indigo"
                                        text
                                        :loading="loadingAddDialog"
                                        @click="addWorkoutConfirm"
                                    >
                                        Додати
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-data-iterator
                            :items="workoutsFilter"
                            hide-default-footer
                            :sort-by="sortBy"
                            :sort-desc="sortDesc"
                            :items-per-page.sync="itemsPerPage"
                            :page.sync="page"
                            :loading="loading"
                        >
                            <template v-slot:default="props">
                                <v-row>
                                    <v-col
                                        v-for="item in props.items"
                                        :key="item.id"
                                        cols="12"
                                    >
                                        <v-card hover>
                                            <v-container>
                                                <v-row
                                                    align="center"
                                                >
                                                    <v-col
                                                        cols="12"
                                                        sm="5"
                                                        md="5"
                                                        lg="5"
                                                    >
                                                        <v-skeleton-loader :loading="loading" type="card-heading">
                                                            <v-card-title>
                                                                {{ item.name }}
                                                                <v-icon
                                                                    :color="publishedColor(item)"
                                                                >
                                                                    {{ publishedIcon(item) }}
                                                                </v-icon>
                                                            </v-card-title>
                                                            <v-card-subtitle  class="pb-0 pb-sm-4 pb-md-4 pb-lg-4">{{ item.workout_style.name }}</v-card-subtitle>
                                                        </v-skeleton-loader>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="4"
                                                        md="4"
                                                        lg="4"
                                                    >
                                                        <v-card-text  class="py-0 py-sm-4 py-md-4 py-lg-4">
                                                            <v-skeleton-loader :loading="loading" type="chip">
                                                                <v-chip v-for="exercises_group in item.exercises_groups" :key="exercises_group.id"
                                                                        color="primary"
                                                                        outlined
                                                                        pill
                                                                        class="ma-1"
                                                                        small
                                                                >
                                                                    {{ exercises_group.name }}
                                                                </v-chip>
                                                            </v-skeleton-loader>
                                                        </v-card-text>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="3"
                                                        md="3"
                                                        lg="3"
                                                    >
                                                        <v-container>
                                                            <v-row
                                                                class="pr-4"
                                                            >
                                                                <v-col
                                                                    class="text-center text-sm-end text-md-end text-lg-end pa-0"
                                                                >
                                                                    <v-btn
                                                                            x-small
                                                                            color="primary"
                                                                            dark
                                                                            rounded
                                                                            @click="$router.push({ name: 'workout', params: { id: item.id } })"
                                                                        >
                                                                            <v-icon small>
                                                                                mdi-eye
                                                                            </v-icon>
                                                                        </v-btn>
                                                                    <v-btn
                                                                        v-if="$auth.check(1)"
                                                                        x-small
                                                                        color="indigo"
                                                                        dark
                                                                        rounded
                                                                        @click="addWorkout(item.id)"
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-plus
                                                                        </v-icon>
                                                                    </v-btn>
                                                                    <v-btn
                                                                        v-if="$auth.check(2)"
                                                                        x-small
                                                                        color="success"
                                                                        dark
                                                                        rounded
                                                                        @click="$router.push({ name: 'workout', params: { id: item.id, edit: true }})"
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-pencil
                                                                        </v-icon>
                                                                    </v-btn>
                                                                    <v-btn
                                                                        v-if="$auth.check(2)"
                                                                        x-small
                                                                        color="error"
                                                                        dark
                                                                        rounded
                                                                        @click="deleteWorkout(item.id)"
                                                                    >
                                                                        <v-icon small>
                                                                            mdi-delete
                                                                        </v-icon>
                                                                    </v-btn>
                                                                </v-col>
                                                            </v-row>
                                                        </v-container>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </template>
                            <template v-slot:loading>
                                <v-container>
                                    <v-row
                                        justify="center"
                                        align="center"
                                    >
                                        <div class="justify-center">
                                            <v-progress-circular
                                                indeterminate
                                                color="primary"
                                            >
                                            </v-progress-circular>
                                        </div>
                                    </v-row>
                                </v-container>
                            </template>
                            <template v-slot:no-data>
                                <v-alert v-if="!loading"
                                         border="top"
                                         colored-border
                                         type="info"
                                         elevation="2"
                                >
                                    Дані не знайдено
                                </v-alert>
                            </template>
                        </v-data-iterator>
                    </v-col>
                </v-row>
                <v-row v-if="pageCount > 1">
                    <v-col>
                        <v-pagination
                            v-model="page"
                            :length="pageCount"
                            circle
                        ></v-pagination>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import draggable from 'vuedraggable';
import {validationMixin} from "vuelidate";
import {required} from "vuelidate/lib/validators";

export default {
    name: "Workout",
    mixins: [validationMixin],

    validations: {
        createWorkout: {
            name: {
                required,
            },
            workout_style_id: {
                required,
            },
            is_published: {
                required,
            },
        },
    },
    components: {
        draggable,
    },
    props: ['loadingPage'],
    data() {
        return {
            publishItems: [
                {id: 1, name: 'Опубліковано',},
                {id: 0, name: 'Не опубліковано',}
            ],

            valid: true,
            swapExercisesGroup: true,

            createDialog: false,
            createDialogLoading: false,
            createWorkout: {
                name: '',
                workout_style_id: '',
                exercises_groups: [
                    { name: 'Понеділок', order: 1, },
                    { name: 'Середа', order: 2, },
                    { name: 'П`ятниця', order: 3, },
                ],
                is_published: '',
            },

            deleteDialog: false,
            deleteWorkoutId: '',
            loadingDeleteDialog: false,

            addDialog: false,
            dialog: false,
            loading: true,
            loadingAddDialog: false,
            addWorkoutId: '',

            sortDesc: false,
            sortBy: 'name',
            sortByItems: [
                { name: 'Назва', value: 'name' },
                { name: 'Стиль', value: 'workout_style.name' },
            ],

            page: 1,
            itemsPerPage: 6,

            workouts: [],

            workoutStyleItems: [],

            workoutStyles: '',
            nameWorkout: '',
        };
    },
    created() {
        this.setWorkouts();
        this.setWorkoutStyles();
    },
    computed: {
        workoutsFilter: function () {
            return this.workoutsFilterByName(
                this.workoutsFilterByStyle(
                    this.workouts
                )
            );
        },
        pageCount: function () {
            return Math.ceil(this.workoutsFilter.length / this.itemsPerPage);
        },
        nameErrors: function () {
            const errors = [];
            if (!this.$v.createWorkout.name.$dirty) return errors;
            !this.$v.createWorkout.name.required && errors.push('Вкажіть назву');
            return errors;
        },
        styleErrors: function () {
            const errors = [];
            if (!this.$v.createWorkout.workout_style_id.$dirty) return errors;
            !this.$v.createWorkout.workout_style_id.required && errors.push('Виберіть стиль');
            return errors;
        },
        publishErrors: function () {
            const errors = [];
            if (!this.$v.createWorkout.is_published.$dirty) return errors;
            !this.$v.createWorkout.is_published.required && errors.push('Виберіть статус');
            return errors;
        },
    },
    methods: {
        publishedIcon: function (item) {
            if (this.$auth.check(2)) {
                if (!item.is_published) {
                    return 'mdi-lock';
                } else {
                    return 'mdi-lock-open';
                }
            }
        },
        publishedColor: function (item) {
            if (this.$auth.check(2)) {
                if (!item.is_published) {
                    return 'error';
                } else {
                    return 'success';
                }
            }
        },
        setWorkouts: async function () {
            try {
                const { data } = await axios.get('workouts');
                if (this.$auth.check(2)) {
                    this.workouts = data;
                } else {
                    this.workouts = data.filter(d => d.is_published);
                }
            }
            catch(error) {
                await this.$router.push({name: 'workouts'});
            }
        },
        setWorkoutStyles: async function () {
            try {
                const { data } = await axios.get('workout_styles');
                this.workoutStyleItems = data;
            } catch (err) {
                //
            }
            finally {
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                setTimeout(() => this.loading = false, 1000);
            }
        },
        workoutsFilterByName: function (workouts) {
            return workouts.filter(e => e.name.indexOf(this.nameWorkout) !== -1);
        },
        workoutsFilterByStyle: function (workouts) {
            return workouts.filter(e => this.workoutStyles.length < 1 || this.workoutStyles === e.workout_style_id);
        },
        addWorkoutConfirm: async function () {
            this.loadingAddDialog = true;

            try {
                const { data } = await axios.get(`/account/workouts/add/${this.addWorkoutId}`);
                await this.$router.push({ name: 'my-workout', params: { id: data } });
            }
            catch(error) {
                //
            }
            finally {
                this.loadingAddDialog = false;
                this.addDialog = false;
            }
        },
        addWorkout: function (id) {
            this.addWorkoutId = id;
            this.addDialog = true;
        },

        deleteWorkout: function (id) {
            this.deleteWorkoutId = id;
            this.deleteDialog = true;
        },
        deleteWorkoutConfirm: async function () {
            this.loadingDeleteDialog = true;

            try {
                const { data } = await axios.delete(`/account/workouts/${this.deleteWorkoutId}`);
                await this.setWorkouts();
            }
            catch(error) {
                //
            }
            finally {
                this.loadingDeleteDialog = false;
                this.deleteDialog = false;
            }
        },

        addExercisesGroup: function () {
            this.createWorkout.exercises_groups.push({ name: '', order: this.createWorkout.exercises_groups.length + 1, });
        },
        closeCreateDialog: function () {
            this.createDialog = false;
            this.$v.$reset();
            this.createWorkout.name = '';
            this.createWorkout.workout_style_id = '';
            this.createWorkout.is_published = '';
            this.createWorkout.exercises_groups.splice(0, this.createWorkout.exercises_groups.length);
            this.createWorkout.exercises_groups.push(
                { name: 'Понеділок', },
                { name: 'Середа', },
                { name: 'П`ятниця', },
            );
            this.swapExercisesGroup = true;
        },
        updateOrder: function () {
            this.createWorkout.exercises_groups.map((element, index) => {
                element.order = index + 1;
            });
        },
        deleteExercisesGroup: function (index) {
            this.createWorkout.exercises_groups.splice(index, 1);
        },
        create: async function () {
            this.$v.$touch();
            this.$refs.form.validate();
            if (!this.$v.$invalid && this.valid) {
                if (!this.createWorkout.exercises_groups.some(e => e.name === '')) {
                    this.createDialogLoading = true;
                    try {
                        const { data } = await axios.post('/account/workouts', this.createWorkout);
                        await this.$router.push({ name: 'workout', params: { id: data } });
                    }
                    catch (error) {
                        //
                    }
                    finally {
                        this.createDialogLoading = false;
                        this.createDialog = false;
                    }
                }
            }
        },
    },
}
</script>

<style scoped>
    .v-alert {
        margin: 0 !important;
    }
    a {  text-decoration: none; }
    .v-card__title {
        word-break: normal !important;
    }
    .v-card >>> .v-card__title.title {
        font-weight: bold;
        background: #1976D2 !important;
        color: white !important;
    }
    .groups-exercises {
        background: #1976D2 !important;
    }
    .groups-exercises >>> .v-card__subtitle {
        font-weight: bold;
        color: white !important;
    }
</style>
