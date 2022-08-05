<template>
    <v-container>
        <v-row>
            <v-col>
                <v-dialog
                    v-model="exerciseDialog"
                    persistent
                    max-width="500px"
                >
                    <v-card
                        :loading="exerciseDialogLoading"
                        :disabled="exerciseDialogLoading"
                    >
                        <v-form>
                            <v-card-title v-if="exercise.id === 0" class="title text-h5">
                                Створення вправи
                            </v-card-title>
                            <v-card-title v-if="exercise.id > 0" class="title text-h5">
                                Редагування вправи
                            </v-card-title>
                            <v-card-text class="pb-0 px-2">
                                <v-container>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-text-field
                                                v-model.trim="exercise.name"
                                                label="Назва"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="nameErrors"
                                                @input="$v.exercise.name.$touch()"
                                                @blur="$v.exercise.name.$touch()"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                v-model="exercise.muscle_group_id"
                                                label="Виберіть групу м`яз"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="muscleGroupsItems"
                                                item-text="name"
                                                item-value="id"
                                                small-chips
                                                autocomplete="off"
                                                :error-messages="muscleGroupErrors"
                                                @input="$v.exercise.muscle_group_id.$touch()"
                                                @blur="$v.exercise.muscle_group_id.$touch()"
                                            >
                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip small color="primary">
                                                        {{ item.name }}
                                                    </v-chip>
                                                </template>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-file-input
                                                v-model="exercise.image"
                                                accept="image/png, image/jpeg, image/bmp"
                                                prepend-icon="mdi-camera"
                                                label="Зображення"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="imageErrors"
                                                :clearable="false"
                                                @change="setImageURL"
                                                @input="$v.exercise.image.$touch()"
                                                @blur="$v.exercise.image.$touch()"
                                            ></v-file-input>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="imageURL">
                                        <v-col>
                                            <v-img :src="imageURL" height="200" contain></v-img>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="exercise.description"
                                                label="Опис"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="descriptionErrors"
                                                @input="$v.exercise.description.$touch()"
                                                @blur="$v.exercise.description.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="exercise.starting_position"
                                                label="Вихідне положення"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="startingPositionErrors"
                                                @input="$v.exercise.starting_position.$touch()"
                                                @blur="$v.exercise.starting_position.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="exercise.execution_trajectory"
                                                label="Траєкторія руху"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="executionTrajectoryErrors"
                                                @input="$v.exercise.execution_trajectory.$touch()"
                                                @blur="$v.exercise.execution_trajectory.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="exercise.technique_execution"
                                                label="Техніка виконання"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="techniqueExecutionErrors"
                                                @input="$v.exercise.technique_execution.$touch()"
                                                @blur="$v.exercise.technique_execution.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                v-model="exercise.sports_projectile_id"
                                                label="Виберіть спортивний інвентарь"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="sportsProjectilesItems"
                                                item-text="name"
                                                item-value="id"
                                                small-chips
                                                autocomplete="off"
                                                :error-messages="sportsProjectileErrors"
                                                @input="$v.exercise.sports_projectile_id.$touch()"
                                                @blur="$v.exercise.sports_projectile_id.$touch()"
                                            >
                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip small color="primary">
                                                        {{ item.name }}
                                                    </v-chip>
                                                </template>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                v-model="exercise.muscles"
                                                label="Виберіть м`язи"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="musclesItems"
                                                item-text="name"
                                                item-value="id"
                                                no-data-text="Дані не знайдено"
                                                multiple
                                                small-chips
                                                autocomplete="off"
                                                :error-messages="musclesErrors"
                                                @input="$v.exercise.muscles.$touch()"
                                                @blur="$v.exercise.muscles.$touch()"
                                            >
                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip v-if="index === 0" small color="primary">
                                                        {{ item.name }}
                                                    </v-chip>
                                                    <span
                                                        v-if="index === 1"
                                                        class="grey--text caption"
                                                    >
                                                        (+{{ exercise.muscles.length - 1 }} інших)
                                                    </span>
                                                </template>
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
                                    @click="closeExerciseDialog"
                                >Закрити</v-btn>
                                <v-btn
                                    v-if="exercise.id === 0"
                                    color="success"
                                    text
                                    @click="saveExercise"
                                    :loading="exerciseDialogLoading"
                                >Створити</v-btn>
                                <v-btn
                                    v-if="exercise.id > 0"
                                    color="success"
                                    text
                                    @click="saveExercise"
                                    :loading="exerciseDialogLoading"
                                >Редагувати</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
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
                            ><v-text-field
                                solo
                                rounded
                                label="Пошук"
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                                v-model="nameExercise"
                            ></v-text-field></v-col>
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
                                                            autocomplete="off"
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
                                                            label="Виберіть групи м`яз"
                                                            outlined
                                                            dense
                                                            hide-details="auto"
                                                            :items="muscleGroupsItems"
                                                            item-text="name"
                                                            item-value="id"
                                                            v-model="muscleGroups"
                                                            multiple
                                                            small-chips
                                                            autocomplete="off"
                                                        >
                                                            <template v-slot:selection="{ item, index }">
                                                                <v-chip v-if="index === 0" small color="primary">
                                                                    {{ item.name }}
                                                                </v-chip>
                                                                <span
                                                                    v-if="index === 1"
                                                                    class="grey--text caption"
                                                                >
                                                              (+{{ muscleGroups.length - 1 }} інших)
                                                            </span>
                                                            </template>
                                                        </v-select>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="pb-0">
                                                        <v-select
                                                            label="Виберіть спортивний інвентарь"
                                                            outlined
                                                            dense
                                                            hide-details="auto"
                                                            :items="sportsProjectilesItems"
                                                            item-text="name"
                                                            item-value="id"
                                                            v-model="sportsProjectiles"
                                                            multiple
                                                            small-chips
                                                            autocomplete="off"
                                                        >
                                                            <template v-slot:selection="{ item, index }">
                                                                <v-chip v-if="index === 0" small color="primary">
                                                                    {{ item.name }}
                                                                </v-chip>
                                                                <span
                                                                    v-if="index === 1"
                                                                    class="grey--text caption"
                                                                >
                                                              (+{{ sportsProjectiles.length - 1 }} інших)
                                                            </span>
                                                            </template>
                                                        </v-select>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="pb-0">
                                                        <v-select
                                                            label="Виберіть м`язи"
                                                            outlined
                                                            dense
                                                            hide-details="auto"
                                                            :items="musclesFilter"
                                                            item-text="name"
                                                            item-value="id"
                                                            no-data-text="Дані не знайдено"
                                                            v-model="muscles"
                                                            multiple
                                                            small-chips
                                                            autocomplete="off"
                                                        >
                                                            <template v-slot:selection="{ item, index }">
                                                                <v-chip v-if="index === 0" small color="primary">
                                                                    {{ item.name }}
                                                                </v-chip>
                                                                <span
                                                                    v-if="index === 1"
                                                                    class="grey--text caption"
                                                                >
                                                              (+{{ muscles.length - 1 }} інших)
                                                            </span>
                                                            </template>
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
                    <v-btn
                        small
                        color="cyan"
                        dark
                        rounded
                        @click="exerciseDialog = true"
                    >
                        <v-icon small>
                            mdi-plus
                        </v-icon>
                        Створити
                    </v-btn>
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
                        <v-data-iterator
                            :items="exercisesFilter"
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
                                        sm="6"
                                        md="4"
                                        lg="4"
                                    >
                                        <v-skeleton-loader :loading="loading" type="card">
                                            <v-card
                                                hover
                                                link
                                            >
                                                <a @click="$router.push({name: 'exercise', params: {id: item.id}})">
                                                    <v-container>
                                                        <v-img
                                                            contain
                                                            height="200"
                                                            :src="`/storage/exercises/${item.image}`"
                                                        ></v-img>
                                                    </v-container>
                                                    <v-card-title class="blue--text text--darken-2">{{ item.muscle_group.name }}</v-card-title>
                                                    <v-card-subtitle height="60">{{ item.name }}</v-card-subtitle>
                                                </a>
                                                <v-card-text v-if="$auth.check(2)">
                                                    <v-btn
                                                        elevation="0"
                                                        block
                                                        x-small
                                                        color="success"
                                                        dark
                                                        rounded
                                                        @click="editExercise(item)"
                                                    >
                                                        <v-icon small>
                                                            mdi-pencil
                                                        </v-icon>
                                                        <div class="pl-1 d-none d-sm-block d-md-block d-lg-block">Редагувати</div>
                                                    </v-btn>
                                                </v-card-text>
                                            </v-card>
                                        </v-skeleton-loader>
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
                                <v-alert
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
import { validationMixin } from 'vuelidate';
import { required } from 'vuelidate/lib/validators';

export default {
    name: "ExercisesList",
    props: ['loadingPage'],

    mixins: [validationMixin],

    validations: {
        exercise: {
            name: {
                 required,
            },
            muscle_group_id: {
                 required,
            },
            image: {
                required,
            },
            description: {
                 required,
            },
            starting_position: {
                 required,
            },
            execution_trajectory: {
                 required,
            },
            technique_execution: {
                 required,
            },
            sports_projectile_id: {
                 required,
            },
            muscles: {
                 required,
            },
        },
    },

    data() {
        return {
            exerciseDialogLoading: false,
            exerciseDialog: false,
            exercise: {
                id: 0,
                name: '',
                muscle_group_id: '',
                image: undefined,
                description: '',
                starting_position: '',
                execution_trajectory: '',
                technique_execution: '',
                sports_projectile_id: '',
                muscles: [],
            },

            dialog: false,
            loading: true,

            exercises: [],
            imageURL: '',

            sortDesc: false,
            sortBy: 'name',
            sortByItems: [
                { name: 'Назва', value: 'name' },
                { name: 'Група м`яз', value: 'muscle_group.name' },
            ],
            page: 1,
            itemsPerPage: 6,

            muscleGroupsItems: [],
            sportsProjectilesItems: [],
            musclesItems: [],

            sportsProjectiles: [],
            muscleGroups: [],
            muscles: [],
            nameExercise: '',
        };
    },
    created() {
        this.setMuscleGroups();
        this.setSportsProjectiles();
        this.setExercises();
        this.setMuscles();
    },
    computed: {
        // musclesFilterDialog: function () {
        //     if (this.exercise.id === 0) {
        //         this.exercise.muscles = [];
        //     }
        //     return this.musclesItems.filter(m => this.exercise.muscle_group_id === '' || this.exercise.muscle_group_id === m.muscle_group_id);
        // },
        musclesFilter: function () {
            this.muscles = '';
            return this.musclesItems.filter(m => this.muscleGroups.length < 1 || this.muscleGroups.includes(m.muscle_group_id));
        },
        exercisesFilter: function () {
            return this.filterExercisesByName(
                this.filterExercisesBySportsProjectiles(
                    this.filterExercisesByMuscleGroups(
                        this.filterExercisesByMuscles(
                            this.exercises
                        )
                    )
                )
            );
        },
        pageCount: function () {
            return Math.ceil(this.exercisesFilter.length / this.itemsPerPage);
        },

        nameErrors: function () {
            const errors = [];
            if (!this.$v.exercise.name.$dirty) return errors;
            !this.$v.exercise.name.required && errors.push('Вкажіть назву');
            return errors;
        },
        muscleGroupErrors: function () {
            const errors = [];
            if (!this.$v.exercise.muscle_group_id.$dirty) return errors;
            !this.$v.exercise.muscle_group_id.required && errors.push('Вкажіть групу м`яз');
            return errors;
        },
        imageErrors: function () {
            const errors = [];
            if (!this.$v.exercise.image.$dirty) return errors;
            if (this.exercise.id === 0) {
                !this.$v.exercise.image.required && errors.push('Виберіть зображення');
            }
            if (this.exercise.image !== undefined) {
                if (!this.exercise.image.type.match('image/png') && !this.exercise.image.type.match('image/jpeg') && !this.exercise.image.type.match('image/bmp')) errors.push('Виберіть зображення');
            }
            return errors;
        },
        descriptionErrors: function () {
            const errors = [];
            if (!this.$v.exercise.description.$dirty) return errors;
            !this.$v.exercise.description.required && errors.push('Вкажіть опис');
            return errors;
        },
        startingPositionErrors: function () {
            const errors = [];
            if (!this.$v.exercise.starting_position.$dirty) return errors;
            !this.$v.exercise.starting_position.required && errors.push('Вкажіть вихідне положення');
            return errors;
        },
        executionTrajectoryErrors: function () {
            const errors = [];
            if (!this.$v.exercise.execution_trajectory.$dirty) return errors;
            !this.$v.exercise.execution_trajectory.required && errors.push('Вкажіть траєкторію руху');
            return errors;
        },
        techniqueExecutionErrors: function () {
            const errors = [];
            if (!this.$v.exercise.technique_execution.$dirty) return errors;
            !this.$v.exercise.technique_execution.required && errors.push('Вкажіть техніку виконання');
            return errors;
        },
        sportsProjectileErrors: function () {
            const errors = [];
            if (!this.$v.exercise.sports_projectile_id.$dirty) return errors;
            !this.$v.exercise.sports_projectile_id.required && errors.push('Вкажіть спортивний інвентарь');
            return errors;
        },
        musclesErrors: function () {
            const errors = [];
            if (!this.$v.exercise.muscles.$dirty) return errors;
            !this.$v.exercise.muscles.required && errors.push('Виберіть м`язи');
            return errors;
        },
    },
    methods: {
        setMuscleGroups: async function () {
            try {
                const { data } = await axios.get('muscle_groups');
                this.muscleGroupsItems = data;
            } catch (err) {}
        },
        setSportsProjectiles: async function () {
            try {
                const { data } = await axios.get('sports_projectiles');
                this.sportsProjectilesItems = data;
            } catch (err) {}
        },
        setExercises: async function () {
            try {
                const { data } = await axios.get('exercises');
                this.exercises = data;
            } catch (err) {}
        },
        setMuscles: async function () {
            try {
                const { data } = await axios.get('muscles');
                this.musclesItems = data;
            } catch (err) {}
            finally {
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                setTimeout(() => this.loading = false, 1000);
            }
        },

        filterExercisesByName: function (exercises) {
            return exercises.filter(e => e.name.indexOf(this.nameExercise) !== -1);
        },
        filterExercisesBySportsProjectiles: function (exercises) {
            return exercises.filter(e => this.sportsProjectiles.length < 1 || this.sportsProjectiles.includes(e.sports_projectile_id));
        },
        filterExercisesByMuscleGroups: function (exercises) {
            return exercises.filter(e => this.muscleGroups.length < 1 || this.muscleGroups.includes(e.muscle_group_id));
        },
        filterExercisesByMuscles: function (exercises) {
            return exercises.filter(e => {
                return e.muscles.some(m => {
                    return this.muscles.length < 1 || this.muscles.includes(m.id);
                });
            });
        },

        closeExerciseDialog() {
            this.exerciseDialog = false;
            this.$v.$reset();
            this.exercise.id = 0;
            this.exercise.name = '';
            this.exercise.muscle_group_id = '';
            this.exercise.image = undefined;
            this.exercise.description = '';
            this.exercise.starting_position = '';
            this.exercise.execution_trajectory = '';
            this.exercise.technique_execution = '';
            this.exercise.sports_projectile_id = '';
            this.exercise.muscles = [];
            this.imageURL = '';
        },
        async saveExercise() {
            this.$v.$touch();
            if ((!this.$v.exercise.name.$invalid
                && !this.$v.exercise.muscle_group_id.$invalid
                && !this.$v.exercise.description.$invalid
                && !this.$v.exercise.starting_position.$invalid
                && !this.$v.exercise.execution_trajectory.$invalid
                && !this.$v.exercise.technique_execution.$invalid
                && !this.$v.exercise.sports_projectile_id.$invalid
                && !this.$v.exercise.muscles.$invalid
                && this.exercise.id > 0 && this.exercise.image === undefined && this.$v.exercise.image.$invalid)
                || !this.$v.$invalid) {
                this.exerciseDialogLoading = true;
                const form = new FormData();

                form.append("name", this.exercise.name);
                form.append("muscle_group_id", this.exercise.muscle_group_id);
                form.append("image", this.exercise.image);
                form.append("description", this.exercise.description);
                form.append("starting_position", this.exercise.starting_position);
                form.append("execution_trajectory", this.exercise.execution_trajectory);
                form.append("technique_execution", this.exercise.technique_execution);
                form.append("sports_projectile_id", this.exercise.sports_projectile_id);
                form.append("muscles", this.exercise.muscles);


                let url = '/exercises';
                if (this.exercise.id > 0) {
                    url = '/exercises/' + this.exercise.id;
                }

                try {
                    const { data } = await axios.post(url, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    await this.setExercises();
                } catch (err) {
                    //
                } finally {
                    this.exerciseDialogLoading = false;
                    this.closeExerciseDialog();
                }
            }
        },
        setImageURL() {
            if (!this.$v.exercise.image.$invalid) {
                if (this.exercise.image !== undefined) {
                    if (!this.exercise.image.type.match('image/png') && !this.exercise.image.type.match('image/jpeg') && !this.exercise.image.type.match('image/bmp')) {
                        this.imageURL = '';
                    } else {
                        this.imageURL = URL.createObjectURL(this.exercise.image);
                    }
                } else {
                    this.imageURL = '';
                }
            } else {
                this.imageURL = '';
            }
        },
        editExercise(item) {
            let app = this;
            this.exerciseDialog = true;
            this.exercise.id = item.id;
            this.exercise.name = item.name;
            this.exercise.muscle_group_id = item.muscle_group_id;
            this.exercise.description = item.description;
            this.exercise.starting_position = item.starting_position;
            this.exercise.execution_trajectory = item.execution_trajectory;
            this.exercise.technique_execution = item.technique_execution;
            this.exercise.sports_projectile_id = item.sports_projectile_id;
            this.exercise.muscles = item.muscles.map(i => i.id);
            this.imageURL = `/storage/exercises/${item.image}`;
        },
    },
}
</script>

<style scoped>
    a {  text-decoration: none; }
    .v-card__title {
        word-break: normal !important;
    }
    .v-card__subtitle {
        height: 60px;
    }
    .v-card >>> .v-card__title.title {
        font-weight: bold;
        background: #1976D2 !important;
        color: white !important;
    }
</style>
