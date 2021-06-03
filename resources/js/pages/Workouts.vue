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
                            justify="center"
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
                        </v-row>
                    </v-col>
                </v-row>
                <v-row
                    v-if="$auth.check(2)"
                    justify="center"
                >
                    <div>
                        <v-dialog
                            v-model="dialog"
                            max-width="500px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    color="primary"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    Створити
                                    <v-icon>
                                        mdi-plus-box
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>Test</v-card-title>
                            </v-card>
                        </v-dialog>
                    </div>
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
                        <v-data-table
                            :headers="headers"
                            :items="workoutsFilter"
                            hide-default-footer
                            :header-props="headerProps"
                            :loading="loading"
                        >
                            <template v-slot:item.exercises_groups="{ item }">
                                <div v-for="exercises_group in item.exercises_groups" :key="exercises_group.id">
                                        {{ exercises_group.name }}
                                    </div>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="$router.push({ name: 'workout', params: { id: item.id } })"
                                >
                                    mdi-eye
                                </v-icon>
                                <v-icon
                                    v-if="$auth.check(1)"
                                    small
                                    class="mr-2"
                                    @click="addWorkout"
                                >
                                    mdi-plus-circle
                                </v-icon>
                            </template>
                            <template v-slot:loading>
                                <v-container>
                                    <v-row
                                        justify="center"
                                        align="center"
                                    >
                                        <div class="justify-center">
                                            Завантаження даних
                                        </div>
                                    </v-row>
                                </v-container>
                            </template>
                            <template v-slot:no-data>
                                <v-container>
                                    <v-alert v-if="!loading"
                                             border="top"
                                             colored-border
                                             type="info"
                                             elevation="2"
                                             class="text-start"
                                    >
                                        Дані не знайдено
                                    </v-alert>
                                </v-container>
                            </template>
                        </v-data-table>
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
    name: "Workout",
    props: ['loadingPage'],
    data() {
        return {
            dialog: false,
            loading: true,
            headerProps: {
                sortByText: "Сортувати за"
            },
            headers: [
                { text: 'Назва', value: 'name' },
                { text: 'Стиль', value: 'workout_style.name' },
                { text: 'Групи вправ', value: 'exercises_groups', sortable: false },
                { text: 'Дії', value: 'actions', sortable: false, width: "10%" },
            ],

            page: 1,
            itemsPerPage: 6,

            workouts: [],

            nameWorkout: '',
        };
    },
    created() {
        this.setWorkouts();
    },
    computed: {
        workoutsFilter: function () {
            return this.workoutsFilterByName(this.workouts);
        },
        pageCount: function () {
            return Math.ceil(this.workoutsFilter.length / this.itemsPerPage);
        },
    },
    methods: {
        setWorkouts: async function () {
            try {
                const { data } = await axios.get('workouts');
                this.workouts = data;
            }
            catch(error) {
                await this.$router.push({name: 'workouts'});
            }
            finally {
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                this.loading = false;
            }
        },
        workoutsFilterByName: function (workouts) {
            return workouts.filter(e => e.name.indexOf(this.nameWorkout) !== -1);
        },
        addWorkout: function () {

        },
    },
}
</script>

<style scoped>

</style>
