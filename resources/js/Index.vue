<template>
    <v-app app>
        <v-app-bar
            color="primary"
            dense
            app
        >
            <v-app-bar-nav-icon color="white" @click="drawer =! drawer"></v-app-bar-nav-icon>

            <v-spacer></v-spacer>

            <v-menu
                open-on-hover
                offset-y
                transition="slide-y-transition"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        class="text-capitalize font-weight-regular"
                        color="white"
                        v-bind="attrs"
                        v-on="on"
                        text
                    >
                        {{ userName }}
                        <v-icon style="font-size: 16px; height: 16px; width: 16px;">mdi-chevron-down</v-icon>
                    </v-btn>
                </template>

                <v-list
                    nav
                    dense
                >
                    <v-list-item-group
                        color="primary"
                    >
                        <v-list-item v-if="!$auth.check()"
                            :to="{name: 'login'}"
                            link
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-login-variant</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Авторизація</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="!$auth.check()"
                            :to="{name: 'register'}"
                            link
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-account-plus</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Реєстрація</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="$auth.check()"
                                     :to="{name: 'account'}"
                                     link
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-account-circle</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Акаунт</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="$auth.check()"
                                     link
                                     @click.prevent="$auth.logout();"
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-exit-to-app</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Вихід</v-list-item-title>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-navigation-drawer
            v-model="drawer"
            app
        >
            <v-list
                dense
            >
                <v-list-item>
                    <div class="text-h4 font-weight-bold blue--text text--darken-2">
                        FreeBody
                    </div>
                </v-list-item>
            </v-list>

            <v-list
                nav
                dense
            >
                <v-list-item-group
                    color="primary"
                >
                    <v-list-item
                        :to="{name: 'home'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-apps</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Головна</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon>mdi-arm-flex</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Тренування</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        :to="{name: 'exercises'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-kettlebell</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Вправи</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        :to="{name: 'muscles'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-kettlebell</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>М`язи</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>

export default {
    name: "Index",
    data() {
        return {
            drawer: null,
            muscleGroups: [],
            sportsProjectiles: [],
            exercises: [],
            muscles: [],
        };
    },
    beforeCreate() {
        this.$Progress.start();
    },
    created() {
        this.setMuscleGroups();
        this.setSportsProjectiles();
        this.setExercises();
        this.setMuscles();
    },
    computed: {
        userName: function () {
            if (this.$auth.check()) {
                return this.$auth.user().name + ' ' + this.$auth.user().surname;
            } else {
                return 'Користувач';
            }
        },
    },
    methods: {
        setMuscleGroups: function () {
            axios.get('muscle_groups')
            .then(res => {
                this.muscleGroups = res.data;
                this.$store.commit('SET_MUSCLE_GROUPS', _.cloneDeep(this.muscleGroups));
            });
        },
        setSportsProjectiles: function () {
            axios.get('sports_projectiles')
                .then(res => {
                    this.sportsProjectiles = res.data;
                    this.$store.commit('SET_SPORTS_PROJECTILES', _.cloneDeep(this.sportsProjectiles));
                });
        },
        setExercises: function () {
            axios.get('exercises')
                .then(res => {
                    this.exercises = res.data;
                    this.$store.commit('SET_EXERCISES', _.cloneDeep(this.exercises));
                });
        },
        setMuscles: function () {
            axios.get('muscles')
                .then(res => {
                    this.muscles = res.data;
                    this.$store.commit('SET_MUSCLES', _.cloneDeep(this.muscles));
                });
        },
    },
}
</script>

<style scoped>
    a {  text-decoration: none;}
</style>
