<template>
    <v-container>
        <v-row
            justify="center">
            <v-col
                cols="12"
                sm="10"
                md="8"
                lg="6"
            >
                <v-card
                    elevation="5"
                >
                    <v-card-title
                        color="primary"
                        class="text-h3 font-weight-bold blue--text text--darken-2"
                    >
                        FREEBODY
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "home",
    props: ['loadingPage'],
    created() {
        this.$emit('update:loadingPage', false);
        this.$Progress.finish();
    },
}
</script>

<style scoped>

</style>
