<template>
    <v-container>
        <v-row
            justify="center"
        >
            <v-col
                cols="12"
                sm="12"
                md="10"
                lg="8"
            >
                <v-card>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                            >
                                <v-skeleton-loader :loading="loading" type="card-heading">
                                    <v-card-title class="text-break justify-center blue--text text--darken-2">{{ muscle.name }}</v-card-title>
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="6"
                                lg="6"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="image">
                                    <v-img
                                        contain
                                        height="200"
                                        :src="`/storage/muscles/${muscle.image}`"
                                    ></v-img>
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="6"
                                lg="6"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Опис м'язу</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ muscle.description }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Функції м'язу</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ muscle.functions }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Вправи для тренування</v-card-subtitle>
                                </v-skeleton-loader>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            v-for="exercise in exercises"
                                            :key="exercise.id"
                                            cols="12"
                                            sm="6"
                                            md="4"
                                            lg="4"
                                        >
                                            <v-skeleton-loader :loading="loading" type="card">
                                                <v-card
                                                    outlined
                                                    link
                                                    :to="{name: 'exercise', params: {id: exercise.id}}"
                                                >
                                                    <v-container>
                                                        <v-img
                                                            contain
                                                            height="100"
                                                            :src="`/storage/exercises/${exercise.image}`"
                                                        ></v-img>
                                                    </v-container>
                                                    <v-card-text class="text-center blue--text text--darken-2" style="height: 70px">{{ exercise.name }}</v-card-text>
                                                </v-card>
                                            </v-skeleton-loader>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Muscle",
    props: ['loadingPage'],
    data() {
        return {
            muscle: {},
            exercises: [],
            loading: true,
        };
    },
    created() {
        if (!Number(this.$route.params.id)) {
            this.loading = false;
            this.$router.push({ name: 'muscles' });
        } else {
            this.setMuscles();
        }
    },
    methods: {
        setMuscles: async function () {
            try {
                const { data } = await axios.get(`/muscles/${Number(this.$route.params.id)}`);
                this.muscle = data.muscle;
                this.exercises = data.exercises;
            }
            catch (error) {
                await this.$router.push({name: 'muscles'});
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
</style>
