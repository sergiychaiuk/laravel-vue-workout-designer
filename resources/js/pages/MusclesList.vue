<template>
    <v-container>
        <v-row>
            <v-col>
                <v-dialog
                    v-model="muscleDialog"
                    persistent
                    max-width="500px"
                >
                    <v-card
                        :loading="muscleDialogLoading"
                        :disabled="muscleDialogLoading"
                    >
                        <v-form>
                            <v-card-title v-if="muscle.id === 0" class="title text-h5">
                                Створення вправи
                            </v-card-title>
                            <v-card-title v-if="muscle.id > 0" class="title text-h5">
                                Редагування вправи
                            </v-card-title>
                            <v-card-text class="pb-0 px-2">
                                <v-container>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-text-field
                                                v-model.trim="muscle.name"
                                                label="Назва"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="nameErrors"
                                                @input="$v.muscle.name.$touch()"
                                                @blur="$v.muscle.name.$touch()"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                v-model="muscle.muscle_group_id"
                                                label="Виберіть групу м`яз"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="muscleGroupsItems"
                                                item-text="name"
                                                item-value="id"
                                                small-chips
                                                autocomplete="off"
                                                :error-messages="muscleGroupErrors"
                                                @input="$v.muscle.muscle_group_id.$touch()"
                                                @blur="$v.muscle.muscle_group_id.$touch()"
                                            >
                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip small color="primary">
                                                        {{ item.name }}
                                                    </v-chip>
                                                </template>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-file-input
                                                v-model="muscle.image"
                                                accept="image/png, image/jpeg, image/bmp"
                                                prepend-icon="mdi-camera"
                                                label="Зображення"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="imageErrors"
                                                :clearable="false"
                                                @change="setImageURL"
                                                @input="$v.muscle.image.$touch()"
                                                @blur="$v.muscle.image.$touch()"
                                            ></v-file-input>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="imageURL">
                                        <v-col>
                                            <v-img :src="imageURL" height="200" contain></v-img>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="muscle.description"
                                                label="Опис"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="descriptionErrors"
                                                @input="$v.muscle.description.$touch()"
                                                @blur="$v.muscle.description.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-textarea
                                                v-model.trim="muscle.functions"
                                                label="Функції"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                autocomplete="off"
                                                :error-messages="functionsErrors"
                                                @input="$v.muscle.functions.$touch()"
                                                @blur="$v.muscle.functions.$touch()"
                                            ></v-textarea>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                v-model="muscle.exercises"
                                                label="Виберіть вправи"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="exercisesItems"
                                                item-text="name"
                                                item-value="id"
                                                no-data-text="Дані не знайдено"
                                                multiple
                                                small-chips
                                                autocomplete="off"
                                                :error-messages="exercisesErrors"
                                                @input="$v.muscle.exercises.$touch()"
                                                @blur="$v.muscle.exercises.$touch()"
                                            >
                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip v-if="index === 0" small color="primary">
                                                        {{ item.name }}
                                                    </v-chip>
                                                    <span
                                                        v-if="index === 1"
                                                        class="grey--text caption"
                                                    >
                                                        (+{{ muscle.exercises.length - 1 }} інших)
                                                    </span>
                                                </template>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    text
                                    @click="closeMuscleDialog"
                                >Закрити</v-btn>
                                <v-btn
                                    v-if="muscle.id === 0"
                                    color="success"
                                    text
                                    @click="saveMuscle"
                                    :loading="muscleDialogLoading"
                                >Створити</v-btn>
                                <v-btn
                                    v-if="muscle.id > 0"
                                    color="success"
                                    text
                                    @click="saveMuscle"
                                    :loading="muscleDialogLoading"
                                >Редагувати</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
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
                                        <v-card-title class="title text-h5">
                                            Фільтр
                                        </v-card-title>
                                        <v-card-text class="pb-0 px-2">
                                            <v-container>
                                                <v-row
                                                    align="center"
                                                >
                                                    <v-col
                                                        class="pb-0"
                                                        cols="7"
                                                        sm="8"
                                                        md="9"
                                                        lg="9"
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
                                                        cols="5"
                                                        sm="4"
                                                        md="3"
                                                        lg="3"
                                                        class="text-center pb-0"
                                                    >
                                                        <v-btn-toggle
                                                            v-model="sortDesc"
                                                            dense
                                                            color="primary"
                                                        >
                                                            <v-btn
                                                                :value="false"
                                                                x-small
                                                            >
                                                                <v-icon small>mdi-arrow-up</v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                :value="true"
                                                                x-small
                                                            >
                                                                <v-icon small>mdi-arrow-down</v-icon>
                                                            </v-btn>
                                                        </v-btn-toggle>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="pb-0">
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
                                            </v-container>
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
                <v-row v-if="$auth.check(2)" justify="center">
                    <v-btn
                        small
                        color="cyan"
                        dark
                        rounded
                        @click="muscleDialog = true"
                    >
                        <v-icon small>
                            mdi-plus
                        </v-icon>
                        Створити
                    </v-btn>
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
                                            >
                                                <a @click="$router.push({name: 'muscle', params: {id: item.id}})">
                                                    <v-container>
                                                        <v-img
                                                            contain
                                                            height="200"
                                                            :src="`/storage/muscles/${item.image}`"
                                                        ></v-img>
                                                    </v-container>
                                                    <v-card-title class="blue--text text--darken-2">{{ item.muscle_group.name }}</v-card-title>
                                                    <v-card-subtitle height="60">{{ item.name }}</v-card-subtitle>
                                                </a>
                                                <v-card-text v-if="$auth.check(2)">
                                                    <v-btn
                                                        elevation="0"
                                                        block
                                                        x-small
                                                        color="success"
                                                        dark
                                                        rounded
                                                        @click="editMuscle(item)"
                                                    >
                                                        <v-icon small>
                                                            mdi-pencil
                                                        </v-icon>
                                                        <div class="pl-1 d-none d-sm-block d-md-block d-lg-block">Редагувати</div>
                                                    </v-btn>
                                                </v-card-text>
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
                                <v-alert
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
import {validationMixin} from "vuelidate";
import {required} from "vuelidate/lib/validators";

export default {
    name: "MusclesList",
    props: ['loadingPage'],

    mixins: [validationMixin],

    validations: {
        muscle: {
            name: {
                required,
            },
            muscle_group_id: {
                required,
            },
            image: {
                required,
            },
            description: {
                required,
            },
            functions: {
                required,
            },
            exercises: {
                required,
            },
        },
    },

    data() {
        return {
            muscleDialogLoading: false,
            muscleDialog: false,

            muscle: {
                id: 0,
                name: '',
                muscle_group_id: '',
                image: undefined,
                description: '',
                functions: '',
                exercises: [],
            },

            imageURL: '',

            exercisesItems: [],

            dialog: false,
            loading: true,

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
        this.setMuscles();
        this.setExercises();
        this.setMuscleGroups();
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
        },

        nameErrors: function () {
            const errors = [];
            if (!this.$v.muscle.name.$dirty) return errors;
            !this.$v.muscle.name.required && errors.push('Вкажіть назву');
            return errors;
        },
        muscleGroupErrors: function () {
            const errors = [];
            if (!this.$v.muscle.muscle_group_id.$dirty) return errors;
            !this.$v.muscle.muscle_group_id.required && errors.push('Вкажіть групу м`яз');
            return errors;
        },
        imageErrors: function () {
            const errors = [];
            if (!this.$v.muscle.image.$dirty) return errors;
            if (this.muscle.id === 0) {
                !this.$v.muscle.image.required && errors.push('Виберіть зображення');
            }
            if (this.muscle.image !== undefined) {
                if (!this.muscle.image.type.match('image/png') && !this.muscle.image.type.match('image/jpeg') && !this.muscle.image.type.match('image/bmp')) errors.push('Виберіть зображення');
            }
            return errors;
        },
        descriptionErrors: function () {
            const errors = [];
            if (!this.$v.muscle.description.$dirty) return errors;
            !this.$v.muscle.description.required && errors.push('Вкажіть опис');
            return errors;
        },
        functionsErrors: function () {
            const errors = [];
            if (!this.$v.muscle.functions.$dirty) return errors;
            !this.$v.muscle.functions.required && errors.push('Вкажіть функції');
            return errors;
        },
        exercisesErrors: function () {
            const errors = [];
            if (!this.$v.muscle.exercises.$dirty) return errors;
            !this.$v.muscle.exercises.required && errors.push('Виберіть вправи');
            return errors;
        },
    },
    methods: {
        setMuscles: async function () {
            try {
                const {data} = await axios.get('muscles');
                this.muscles = data;
            } catch (err) {}
        },
        setExercises: async function () {
            try {
                const { data } = await axios.get('exercises');
                this.exercisesItems = data;
            } catch (err) {}
        },
        setMuscleGroups: async function () {
            try {
                const { data } = await axios.get('muscle_groups');
                this.muscleGroupsItems = data;
            } catch (err) {}
            finally {
                this.$emit('update:loadingPage', false);
                this.$Progress.finish();
                setTimeout(() => this.loading = false, 1000);
            }
        },

        filterMusclesByName: function (muscles) {
            return muscles.filter(m => m.name.indexOf(this.nameMuscle) !== -1);
        },
        filterMusclesByMuscleGroups: function (muscles) {
            return muscles.filter(m => this.muscleGroups.length < 1 || this.muscleGroups.includes(m.muscle_group_id));
        },

        closeMuscleDialog() {
            this.muscleDialog = false;
            this.$v.$reset();
            this.muscle.id = 0;
            this.muscle.name = '';
            this.muscle.muscle_group_id = '';
            this.muscle.image = undefined;
            this.muscle.description = '';
            this.muscle.functions = '';
            this.muscle.exercises = [];
            this.imageURL = '';
        },
        async saveMuscle() {
            this.$v.$touch();
            if ((!this.$v.muscle.name.$invalid
                && !this.$v.muscle.muscle_group_id.$invalid
                && !this.$v.muscle.description.$invalid
                && !this.$v.muscle.functions.$invalid
                && !this.$v.muscle.exercises.$invalid
                && this.muscle.id > 0 && this.muscle.image === undefined && this.$v.muscle.image.$invalid)
                || !this.$v.$invalid) {
                this.muscleDialogLoading = true;
                const form = new FormData();

                form.append("name", this.muscle.name);
                form.append("muscle_group_id", this.muscle.muscle_group_id);
                form.append("image", this.muscle.image);
                form.append("description", this.muscle.description);
                form.append("functions", this.muscle.functions);
                form.append("exercises", this.muscle.exercises);


                let url = '/muscles';
                if (this.muscle.id > 0) {
                    url = '/muscles/' + this.muscle.id;
                }

                try {
                    const { data } = await axios.post(url, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    await this.setMuscles();
                } catch (err) {
                    //
                } finally {
                    this.muscleDialogLoading = false;
                    this.closeMuscleDialog();
                }
            }
        },
        setImageURL() {
            if (!this.$v.muscle.image.$invalid) {
                if (this.muscle.image !== undefined) {
                    if (!this.muscle.image.type.match('image/png') && !this.muscle.image.type.match('image/jpeg') && !this.muscle.image.type.match('image/bmp')) {
                        this.imageURL = '';
                    } else {
                        this.imageURL = URL.createObjectURL(this.muscle.image);
                    }
                } else {
                    this.imageURL = '';
                }
            } else {
                this.imageURL = '';
            }
        },
        editMuscle(item) {
            let app = this;
            this.muscleDialog = true;
            this.muscle.id = item.id;
            this.muscle.name = item.name;
            this.muscle.muscle_group_id = item.muscle_group_id;
            this.muscle.description = item.description;
            this.muscle.functions = item.functions;
            this.muscle.exercises = item.exercises.map(i => i.id);
            this.imageURL = `/storage/muscles/${item.image}`;
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
    .v-card >>> .v-card__title.title {
        font-weight: bold;
        background: #1976D2 !important;
        color: white !important;
    }
</style>
