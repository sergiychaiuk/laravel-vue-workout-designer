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
                                    <v-card-title class="text-break justify-center blue--text text--darken-2">{{ exercise.name }}</v-card-title>
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
                                            :src="`/storage/exercises/${exercise.image}`"
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
                                    <v-card-subtitle class="pt-0">Опис вправи</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ exercise.description }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Вихідне положення</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ exercise.starting_position }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Траєкторія руху</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ exercise.execution_trajectory }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Техніка виконання</v-card-subtitle>
                                    <nl2br tag="v-card-text" :text="`${ exercise.technique_execution }`" class-name="text-justify" />
                                </v-skeleton-loader>
                            </v-col>
                            <v-col
                                cols="12"
                                class="pt-0"
                            >
                                <v-skeleton-loader :loading="loading" type="article">
                                    <v-card-subtitle class="pt-0">Які м'язи використовуються</v-card-subtitle>
                                </v-skeleton-loader>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            v-for="muscle in muscles"
                                            :key="muscle.id"
                                            cols="12"
                                            sm="6"
                                            md="4"
                                            lg="4"
                                        >
                                            <v-skeleton-loader :loading="loading" type="card">
                                                <v-card
                                                    outlined
                                                    link
                                                    :to="{name: 'muscle', params: {id: muscle.id}}"
                                                >
                                                    <v-container>
                                                        <v-img
                                                            contain
                                                            height="100"
                                                            :src="`/storage/muscles/${muscle.image}`"
                                                        ></v-img>
                                                    </v-container>
                                                    <v-card-text class="text-center blue--text text--darken-2" style="height: 70px">{{ muscle.name }}</v-card-text>
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
    name: "Exercise",
    props: ['loadingPage'],
    data() {
        return {
            exercise: {},
            muscles: [],
            loading: true,
        };
    },
    created() {
        if (!Number(this.$route.params.id)) {
            this.loading = false;
            this.$router.push({ name: 'exercises' });
        } else {
            this.setExercises();
        }
    },
    methods: {
        setExercises: async function () {
            try {
                const { data } = await axios.get(`/exercises/${Number(this.$route.params.id)}`);
                this.exercise = data.exercise;
                this.muscles = data.muscles;
            }
            catch (error) {
                await this.$router.push({name: 'exercises'});
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
