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
                                        <v-card-title class="text-h5">
                                            Фільтр
                                        </v-card-title>
                                        <v-card-text class="pb-0">
                                            <v-row
                                                align="center"
                                            >
                                                <v-col
                                                    cols="12"
                                                    sm="12"
                                                    md="8"
                                                    lg="8"
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
                                                    cols="12"
                                                    sm="12"
                                                    md="4"
                                                    lg="4"
                                                    class="text-center"
                                                >
                                                    <v-btn-toggle
                                                        v-model="sortDesc"
                                                        dense
                                                        color="primary"
                                                    >
                                                        <v-btn
                                                            :value="false"
                                                        >
                                                            <v-icon>mdi-arrow-up</v-icon>
                                                        </v-btn>
                                                        <v-btn
                                                            :value="true"
                                                        >
                                                            <v-icon>mdi-arrow-down</v-icon>
                                                        </v-btn>
                                                    </v-btn-toggle>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
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
                                                <v-col>
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
                                                <v-col>
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
                                                :to="{name: 'exercise', params: {id: item.id}}"
                                            >
                                                <v-container>
                                                    <v-img
                                                        contain
                                                        height="200"
                                                        :src="`/storage/exercises/${item.image}`"
                                                    ></v-img>
                                                </v-container>
                                                <v-card-title class="blue--text text--darken-2">{{ item.muscle_group.name }}</v-card-title>
                                                <v-card-subtitle he>{{ item.name }}</v-card-subtitle>
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
export default {
    name: "ExercisesList",
    props: ['loadingPage'],
    data() {
        return {
            dialog: false,
            loading: true,

            exercises: [],

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
        }
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
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                this.loading = false;
            } catch (err) {}
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
</style>
