<template>
    <v-app app>
        <v-container v-if="loadingPage">
            <v-row
                justify="center"
                align="center"
                style="height: 100vh"
            >
                <div>
                    <v-img src="/images/bf_gray.png" height="60" contain></v-img>
                    <div class="d-flex justify-center">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                        >
                        </v-progress-circular>
                    </div>
                </div>
            </v-row>
        </v-container>
        <v-app-bar
            v-if="!loadingPage"
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
                    v-if="!$auth.check(2)"
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
                        <v-list-item v-if="$auth.check(1)"
                                     :to="{name: 'account'}"
                                     link
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-account-circle</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Акаунт</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="$auth.check(1)"
                                     :to="{name: 'my-workouts'}"
                                     link
                        >
                            <v-list-item-icon>
                                <v-icon>mdi-weight-lifter</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Мої тренування</v-list-item-title>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
                <v-divider v-if="$auth.check(1)" class="ma-0"></v-divider>
                <v-list
                    dense
                    v-if="$auth.check()"
                >
                    <v-list-item v-if="$auth.check()"
                                 link
                                 active-class="logout"
                                 @click.prevent="$auth.logout();"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-exit-to-app</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Вихід</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-navigation-drawer
            v-if="!loadingPage"
            v-model="drawer"
            app
        >
            <v-list
                dense
            >
                <v-list-item>
                    <v-list-item-avatar size="60" tile>
                        <v-img src="/images/bf_gray.png" contain></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <div class="text-h4 font-weight-bold blue--text text--darken-2">
                            FreeBody
                        </div>
                    </v-list-item-content>
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
                    <v-list-item
                        :to="{name: 'workouts'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-weight-lifter</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Тренування</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        :to="{name: 'exercises'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-dumbbell</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Вправи</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        :to="{name: 'muscles'}"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-arm-flex</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>М`язи</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <v-main>
            <router-view :loadingPage.sync="loadingPage"></router-view>
        </v-main>
    </v-app>
</template>

<script>

export default {
    name: "Index",
    data() {
        return {
            loadingPage: true,
            drawer: null,
        };
    },
    beforeCreate() {
        this.$Progress.start();
    },
    computed: {
        userName: function () {
            if (this.$auth.check()) {
                if (this.$auth.user().name === undefined) {
                    this.$auth.logout();
                }
                return this.$auth.user().name + ' ' + this.$auth.user().surname;
            } else {
                return 'Користувач';
            }
        },
    },
}
</script>

<style scoped>
    a {  text-decoration: none;}
    .v-progress-circular {
        margin: 1rem;
    }
</style>
