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
                        Користувач
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
                        <v-list-item link>
                            <v-list-item-icon>
                                <v-icon>mdi-login-variant</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Авторизація</v-list-item-title>
                        </v-list-item>
                        <v-list-item link>
                            <v-list-item-icon>
                                <v-icon>mdi-account-plus</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Реєстрація</v-list-item-title>
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
                    <v-list-item link>
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
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon>mdi-kettlebell</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Вправи</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <v-main>

        </v-main>
    </v-app>
</template>

<script>

export default {
    name: "Index",
    data() {
        return {
            drawer: null,
        };
    },
}
</script>

<style scoped>

</style>
