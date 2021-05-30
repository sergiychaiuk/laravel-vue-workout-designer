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
                                    v-model="nameMuscle"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col
                                cols="2"
                            >
                                <v-dialog
                                    v-model="dialog"
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
                                        <v-card-title>Фільтр</v-card-title>
                                        <v-card-text>
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
                                        </v-card-text>
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
                            :items="musclesFilter"
                            hide-default-footer
                            :sort-by="sortBy"
                            :sort-desc="sortDesc"
                            :items-per-page.sync="itemsPerPage"
                            :page.sync="page"
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
                                        <v-card
                                            hover
                                            link
                                            :to="{name: 'muscle', params: {id: item.id}}"
                                        >
                                            <v-container>
                                                <v-img
                                                    contain
                                                    height="200"
                                                    :src="`/images/${item.image}`"
                                                ></v-img>
                                            </v-container>
                                            <v-card-title class="blue--text text--darken-2">{{ item.muscle_group.name }}</v-card-title>
                                            <v-card-subtitle he>{{ item.name }}</v-card-subtitle>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </template>
                        </v-data-iterator>
                    </v-col>
                </v-row>
                <v-row>
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
    name: "MusclesList",
    data() {
        return {
            dialog: false,

            muscles: [],

            sortDesc: false,
            sortBy: 'name',
            sortByItems: [
                { name: 'Назва', value: 'name' },
                { name: 'Група м`яз', value: 'muscle_group.name' },
            ],
            page: 1,
            itemsPerPage: 6,

            muscleGroupsItems: [],

            muscleGroups: [],
            nameMuscle: '',
        };
    },
    created() {
        this.muscles = this.$store.state.muscles;
        this.muscleGroupsItems = this.$store.state.muscleGroups;
        this.$Progress.finish();
    },
    computed: {
        musclesFilter: function () {
            return this.filterMusclesByName(
                this.filterMusclesByMuscleGroups(
                    this.muscles
                )
            );
        },
        pageCount: function () {
            return Math.ceil(this.musclesFilter.length / this.itemsPerPage);
        }
    },
    methods: {
        filterMusclesByName: function (muscles) {
            return muscles.filter(m => m.name.indexOf(this.nameMuscle) !== -1);
        },
        filterMusclesByMuscleGroups: function (muscles) {
            return muscles.filter(m => this.muscleGroups.length < 1 || this.muscleGroups.includes(m.muscle_group_id));
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
