<template>
    <v-container>
        <v-row>
            <v-col>
                <v-card :loading="loading">
                    <v-skeleton-loader :loading="loading" type="article">
                        <v-card-title
                            class="justify-center text-h5 text-sm-h4 text-md-h3 text-lg-h3 text-xl-h3 font-weight-bold blue--text text--darken-2"
                        >
                            {{ workoutName }}
                        </v-card-title>
                        <v-tabs
                            show-arrows
                            fixed-tabs
                            centered
                            slider-color="primary"
                            v-model="tabs"
                        >
                            <v-tab
                                v-for="exercises_group in workout.exercises_groups"
                                :key="exercises_group.id"
                            >
                                {{ exercises_group.name }}
                            </v-tab>
                        </v-tabs>
                        <v-tabs-items v-model="tabs">
                            <v-tab-item
                                v-for="exercises_group in workout.exercises_groups"
                                :key="exercises_group.id"
                            >
                                <v-container>
                                    <v-card flat>
                                        <v-data-table
                                            :headers="headers"
                                            :items="exercises_group.exercises"
                                            hide-default-footer
                                            :header-props="headerProps"
                                            :loading="loading"
                                            item-key="pivot.id"
                                        >
                                            <template v-slot:item.muscles="{ item }">
                                                <div v-for="muscle in item.muscles" :key="muscle.id">
                                                    {{ muscle.name }}
                                                </div>
                                            </template>
                                            <template v-slot:item.actions="{ item }">
                                                <v-icon
                                                    small
                                                    class="mr-2"
                                                    @click="$router.push({ name: 'exercise', params: { id: item.id } })"
                                                >
                                                    mdi-eye
                                                </v-icon>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </v-container>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-skeleton-loader>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Workout",
    data() {
        return {
            tabs: null,
            workout: {},
            loading: true,

            headerProps: {
                sortByText: "Сортувати за"
            },
            headers: [
                { text: 'Назва', value: 'name' },
                { text: 'М`язи', value: 'muscles', sortable: false },
                { text: 'К-сть повторень', value: 'pivot.approaches' },
                { text: 'К-сть підходів', value: 'pivot.repetition' },
                { text: 'Вага (кг)', value: 'pivot.weight' },
                { text: 'Дії', value: 'actions', sortable: false, width: "10%" },
            ],
        };
    },
    created() {
        if (!Number(this.$route.params.id)) {
            this.loading = false;
            this.$router.push({ name: 'workouts' });
        } else {
            this.setWorkout();
        }
    },
    computed: {
        workoutName: function () {
            return `${this.workout?.name} (${this.workout?.workout_style?.name})`;
        },
    },
    methods: {
        setWorkout: async function () {
            try {
                const { data } = await axios.get(`/workouts/${Number(this.$route.params.id)}`);
                this.workout = data.workout;
            }
            catch(error) {
                // if(error.response && error.response.status === 404) {
                //     console.clear();
                // }
                await this.$router.push({name: 'workouts'});
            }
            finally {
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                this.loading = false;
            }
        },
    },
}
</script>

<style scoped>
    a {  text-decoration: none; }
    .v-card__title {
        word-break: normal !important;
    }
</style>
