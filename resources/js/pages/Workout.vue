<template>
    <v-container>
        <v-row
            justify="center"
        >
            <v-col
                cols="12"
                sm="12"
                md="12"
                lg="10"
            >
                <v-dialog
                    v-model="exercisesDialog"
                    persistent
                    max-width="500px"
                    :retain-focus="false"
                >
                    <v-card :loading="createExerciseLoading" :disabled="createExerciseLoading">
                        <v-form
                            @submit.prevent="createExerciseConfirm" method="post"
                        >
                            <v-card-title class="title-exercises text-h5">
                                Створення вправи
                            </v-card-title>
                            <v-card-text class="pb-0 px-2">
                                <v-container>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-autocomplete
                                                label="Вправа"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                v-model="exercise.id"
                                                :items="exercisesList"
                                                :search-input.sync="search"
                                                item-text="name"
                                                item-value="id"
                                                @change="selectExercise"
                                                :error-messages="idErrors"
                                                @input="$v.exercise.id.$touch()"
                                                @blur="$v.exercise.id.$touch()"
                                            >
                                                <template v-slot:no-data>
                                                    <v-list-item>
                                                        <v-list-item-content>
                                                            <v-list-item-title>
                                                                Результатів немає за "<strong>{{ search }}</strong>".
                                                            </v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </template>
                                            </v-autocomplete>
                                        </v-col>
                                    </v-row>
                                    <v-row align="center">
                                        <v-col
                                            order="2"
                                            order-sm="1"
                                            order-md="1"
                                            order-lg="1"
                                            cols="12"
                                            sm="6"
                                            md="6"
                                            lg="6"
                                        >
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="К-сть повторень"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        hide-details="auto"
                                                        v-model="exercise.repetition"
                                                        :error-messages="repetitionErrors"
                                                        @input="$v.exercise.repetition.$touch()"
                                                        @blur="$v.exercise.repetition.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="К-сть підходів"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        hide-details="auto"
                                                        v-model="exercise.approaches"
                                                        :error-messages="approachesErrors"
                                                        @input="$v.exercise.approaches.$touch()"
                                                        @blur="$v.exercise.approaches.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="Вага (кг)"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        step="0.1"
                                                        hide-details="auto"
                                                        v-model="exercise.weight"
                                                        :error-messages="weightErrors"
                                                        @input="$v.exercise.weight.$touch()"
                                                        @blur="$v.exercise.weight.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col
                                            v-if="exercise.image !== '' && exercise.id !== null"
                                            order="1"
                                            order-sm="2"
                                            order-md="2"
                                            order-lg="2"
                                            cols="12"
                                            sm="6"
                                            md="6"
                                            lg="6"
                                        >
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-img
                                                        contain
                                                        height="120"
                                                        :src="`/storage/exercises/${exercise.image}`"
                                                    ></v-img>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    text
                                    @click="closeExerciseDialog"
                                >Закрити</v-btn>
                                <v-btn
                                    color="success"
                                    text
                                    type="submit"
                                    :loading="createExerciseLoading"
                                >Створити</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="editWorkoutDialog"
                    persistent
                    max-width="500px"
                >
                    <v-card
                        :loading="editWorkoutLoading"
                        :disabled="editWorkoutLoading"
                    >
                        <v-form
                            ref="form"
                            v-model="valid"
                            lazy-validation
                            @submit.prevent="editWorkoutConfirm" method="post"
                        >
                            <v-card-title class="title-workout text-h5">
                                Редагування тренування
                            </v-card-title>
                            <v-card-text class="pb-0 px-2">
                                <v-container>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-text-field
                                                label="Назва"
                                                v-model.trim="workoutEdit.name"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="nameWorkoutErrors"
                                                @input="$v.workoutEdit.name.$touch()"
                                                @blur="$v.workoutEdit.name.$touch()"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-select
                                                label="Стиль"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                v-model="workoutEdit.workout_style_id"
                                                :items="workoutStyleItems"
                                                item-text="name"
                                                item-value="id"
                                                :error-messages="styleWorkoutErrors"
                                                @input="$v.workoutEdit.workout_style_id.$touch()"
                                                @blur="$v.workoutEdit.workout_style_id.$touch()"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row
                                        v-if="$auth.check(2)"
                                    >
                                        <v-col class="pb-0">
                                            <v-select
                                                label="Статус"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                v-model="workoutEdit.is_published"
                                                :items="publishItems"
                                                item-text="name"
                                                item-value="id"
                                                :error-messages="publishErrors"
                                                @input="$v.workoutEdit.is_published.$touch()"
                                                @blur="$v.workoutEdit.is_published.$touch()"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-card>
                                                <v-container class="workout-groups-exercises mb-4">
                                                    <v-row align="center">
                                                        <v-card-subtitle>
                                                            Групи вправ
                                                            <v-btn
                                                                color="white"
                                                                icon
                                                                small
                                                                @click="addExercisesGroup"
                                                            >
                                                                <v-icon small>
                                                                    mdi-plus
                                                                </v-icon>
                                                            </v-btn>
                                                        </v-card-subtitle>
                                                        <v-spacer></v-spacer>
                                                        <v-card-actions class="pa-4">
                                                            <v-btn-toggle
                                                                v-model="swapExercisesGroup"
                                                                color="primary"
                                                                rounded
                                                            >
                                                                <v-btn
                                                                    :value="true"
                                                                    x-small
                                                                >
                                                                    <v-icon small>
                                                                        mdi-swap-vertical
                                                                    </v-icon>
                                                                </v-btn>
                                                                <v-btn
                                                                    :value="false"
                                                                    x-small
                                                                >
                                                                    <v-icon small>
                                                                        mdi-delete
                                                                    </v-icon>
                                                                </v-btn>
                                                            </v-btn-toggle>
                                                        </v-card-actions>
                                                    </v-row>
                                                </v-container>
                                                <v-container>
                                                    <draggable
                                                        v-model="workoutEdit.exercises_groups"
                                                        animation="200"
                                                        handle=".my-handle"
                                                        @change="updateOrder"
                                                    >
                                                        <v-row
                                                            v-for="(exercises_group, index) in workoutEdit.exercises_groups"
                                                            :key="index"
                                                        >
                                                            <v-col class="d-flex align-center pt-0">
                                                                <v-text-field
                                                                    class="mr-2"
                                                                    v-model.trim="exercises_group.name"
                                                                    outlined
                                                                    dense
                                                                    elevation="0"
                                                                    hide-details="auto"
                                                                    :rules="[v => !!v]"
                                                                ></v-text-field>
                                                                <v-btn
                                                                    class="my-handle"
                                                                    v-if="swapExercisesGroup"
                                                                    color="primary"
                                                                    fab
                                                                    x-small
                                                                    elevation="0"
                                                                >
                                                                    <v-icon small>
                                                                        mdi-arrow-all
                                                                    </v-icon>
                                                                </v-btn>
                                                                <v-btn
                                                                    v-if="!swapExercisesGroup"
                                                                    color="error"
                                                                    fab
                                                                    x-small
                                                                    elevation="0"
                                                                    :disabled="workoutEdit.exercises_groups.length===1"
                                                                    @click="deleteExercisesGroup(index)"
                                                                >
                                                                    <v-icon small>
                                                                        mdi-delete
                                                                    </v-icon>
                                                                </v-btn>
                                                            </v-col>
                                                        </v-row>
                                                    </draggable>
                                                </v-container>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    text
                                    @click="closeEditWorkoutDialog"
                                >Закрити</v-btn>
                                <v-btn
                                    color="success"
                                    text
                                    :loading="editWorkoutLoading"
                                    type="submit"
                                >Редагувати</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="editExerciseDialog"
                    persistent
                    max-width="500px"
                >
                    <v-card :loading="editExerciseLoading" :disabled="editExerciseLoading">
                        <v-form
                            @submit.prevent="editExerciseConfirm" method="post"
                        >
                            <v-card-title class="title-exercises text-h5">
                                Редагування вправи
                            </v-card-title>
                            <v-card-text class="pb-0 px-2">
                                <v-container>
                                    <v-row>
                                        <v-col class="pb-0">
                                            <v-autocomplete
                                                label="Вправа"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                v-model="exercisesEdit.item.id"
                                                :items="exercisesList"
                                                :search-input.sync="search"
                                                item-text="name"
                                                item-value="id"
                                                @change="selectEditExercise"
                                                :error-messages="editIdErrors"
                                                @input="$v.exercisesEdit.item.id.$touch()"
                                                @blur="$v.exercisesEdit.item.id.$touch()"
                                            >
                                                <template v-slot:no-data>
                                                    <v-list-item>
                                                        <v-list-item-content>
                                                            <v-list-item-title>
                                                                Результатів немає за "<strong>{{ search }}</strong>".
                                                            </v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </template>
                                            </v-autocomplete>
                                        </v-col>
                                    </v-row>
                                    <v-row align="center">
                                        <v-col
                                            order="2"
                                            order-sm="1"
                                            order-md="1"
                                            order-lg="1"
                                            cols="12"
                                            sm="6"
                                            md="6"
                                            lg="6"
                                        >
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="К-сть повторень"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        hide-details="auto"
                                                        v-model="exercisesEdit.item.pivot.repetition"
                                                        :error-messages="editRepetitionErrors"
                                                        @input="$v.exercisesEdit.item.pivot.repetition.$touch()"
                                                        @blur="$v.exercisesEdit.item.pivot.repetition.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="К-сть підходів"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        hide-details="auto"
                                                        v-model="exercisesEdit.item.pivot.approaches"
                                                        :error-messages="editApproachesErrors"
                                                        @input="$v.exercisesEdit.item.pivot.approaches.$touch()"
                                                        @blur="$v.exercisesEdit.item.pivot.approaches.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-text-field
                                                        label="Вага (кг)"
                                                        outlined
                                                        dense
                                                        type="number"
                                                        min="0"
                                                        step="0.1"
                                                        hide-details="auto"
                                                        v-model="exercisesEdit.item.pivot.weight"
                                                        :error-messages="editWeightErrors"
                                                        @input="$v.exercisesEdit.item.pivot.weight.$touch()"
                                                        @blur="$v.exercisesEdit.item.pivot.weight.$touch()"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col
                                            v-if="exercisesEdit.item.image !== '' && exercisesEdit.item.id !== null"
                                            order="1"
                                            order-sm="2"
                                            order-md="2"
                                            order-lg="2"
                                            cols="12"
                                            sm="6"
                                            md="6"
                                            lg="6"
                                        >
                                            <v-row>
                                                <v-col class="pb-0">
                                                    <v-img
                                                        contain
                                                        height="120"
                                                        :src="`/storage/exercises/${exercisesEdit.item.image}`"
                                                    ></v-img>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    text
                                    @click="closeEditExerciseDialog"
                                >Закрити</v-btn>
                                <v-btn
                                    color="success"
                                    text
                                    type="submit"
                                    :loading="editExerciseLoading"
                                >Редагувати</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="deleteWorkoutDialog"
                    persistent
                    max-width="330"
                >
                    <v-card
                        :loading="deleteWorkoutLoading"
                        :disabled="deleteWorkoutLoading"
                    >
                        <v-card-title class="text-h5">
                            Видалити тренування з Вашого списку?
                        </v-card-title>
                        <v-card-text>Тренування не можна буде відновити.</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="deleteWorkoutDialog = false"
                            >
                                Скасувати
                            </v-btn>
                            <v-btn
                                color="error"
                                text
                                :loading="deleteWorkoutLoading"
                                @click="deleteWorkoutConfirm"
                            >
                                Видалити
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="deleteExerciseDialog"
                    persistent
                    max-width="330"
                >
                    <v-card
                        :loading="deleteExerciseLoading"
                        :disabled="deleteExerciseLoading"
                    >
                        <v-card-title class="text-h5">
                            Видалити вправу з Вашого списку?
                        </v-card-title>
                        <v-card-text>Вправу не можна буде відновити.</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="deleteExerciseDialog = false"
                            >
                                Скасувати
                            </v-btn>
                            <v-btn
                                color="error"
                                text
                                :loading="deleteExerciseLoading"
                                @click="deleteExerciseConfirm"
                            >
                                Видалити
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-card :loading="loading || loadingUpdate" :disabled="loadingUpdate">
                    <v-skeleton-loader :loading="loading" type="article">
                        <v-card-title class="title text-h4">
                            {{ workoutTitle }}
                            <v-btn
                                v-if="this.$route.name === 'my-workout' || $auth.check(2)"
                                :color="!activeSetting ? 'indigo' : 'purple'"
                                icon
                                @click="activeSetting = !activeSetting"
                            >
                                <v-icon>
                                    mdi-cogs
                                </v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-subtitle class="subtitle">
                            {{ workoutSubtitle }}
                            <v-btn
                                v-if="activeSetting"
                                x-small
                                color="success"
                                icon
                                @click="editWorkout"
                            >
                                <v-icon small>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                            <v-btn
                                v-if="activeSetting"
                                x-small
                                color="error"
                                icon
                                @click="deleteWorkoutDialog = true"
                            >
                                <v-icon small>
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </v-card-subtitle>
                        <v-tabs
                            background-color="blue lighten-5"
                            show-arrows
                            fixed-tabs
                            centered
                            slider-color="blue darken-4"
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
                                v-for="(exercises_group, index) in workout.exercises_groups"
                                :key="exercises_group.id"
                            >
                                <v-container>
                                    <v-row
                                        v-if="activeSetting"
                                    >
                                        <v-col class="d-flex justify-center">

                                            <v-btn
                                                small
                                                color="cyan"
                                                dark
                                                rounded
                                                @click="createExercise(exercises_group.id, index)"
                                            >
                                                <v-icon small>
                                                    mdi-plus
                                                </v-icon>
                                                Створити
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-data-iterator
                                        :items="exercises_group.exercises"
                                        hide-default-footer
                                    >
                                        <template v-slot:default="props">
                                            <draggable
                                                v-model="exercises_group.exercises"
                                                animation="200"
                                                handle=".my-handle"
                                                @change="updateOrderExercises(index)"
                                            >
                                                <v-row
                                                    v-for="item in props.items"
                                                    :key="item.pivot.id"
                                                >
                                                    <v-col
                                                        cols="12"
                                                    >
                                                        <v-card
                                                            outlined
                                                        >
                                                            <v-container>
                                                                <v-row
                                                                    align="center"
                                                                >
                                                                    <v-col
                                                                        order="1"
                                                                        cols="6"
                                                                        sm="2"
                                                                        md="2"
                                                                        lg="2"
                                                                    >
                                                                        <v-img
                                                                            contain
                                                                            height="100"
                                                                            :src="`/storage/exercises/${item.image}`"
                                                                        ></v-img>
                                                                    </v-col>
                                                                    <v-col
                                                                        order="3"
                                                                        order-sm="2"
                                                                        order-md="2"
                                                                        order-lg="2"
                                                                        cols="12"
                                                                        sm="6"
                                                                        md="7"
                                                                        lg="7"
                                                                    >
                                                                        <v-card-title
                                                                            class="pa-0"
                                                                        >
                                                                            <a class="name-exercise" @click="$router.push({ name: 'exercise', params: { id: item.id } })">
                                                                                {{ item.name }}
                                                                            </a>
                                                                        </v-card-title>
                                                                        <v-card-text class="pt-2 px-0 pb-0">
                                                                            <v-chip v-for="muscle in item.muscles" :key="muscle.id"
                                                                                    color="primary"
                                                                                    outlined
                                                                                    pill
                                                                                    class="ma-1"
                                                                                    small
                                                                                    link
                                                                                    :to="{name: 'muscle', params: {id: muscle.id}}"
                                                                            >
                                                                                {{ muscle.name }}
                                                                            </v-chip>
                                                                        </v-card-text>
                                                                    </v-col>
                                                                    <v-col
                                                                        order="2"
                                                                        order-sm="3"
                                                                        order-md="3"
                                                                        order-lg="3"
                                                                        cols="6"
                                                                        sm="4"
                                                                        md="3"
                                                                        lg="3"
                                                                        class="d-flex justify-end"
                                                                    >
                                                                        <div class="pa-0">
                                                                            <div class="caption">К-сть повторень: {{ item.pivot.repetition }}</div>
                                                                            <div class="caption">К-сть підходів: {{ item.pivot.approaches }}</div>
                                                                            <div class="caption">Вага (кг): {{ item.pivot.weight }}</div>
                                                                        </div>
                                                                    </v-col>
                                                                </v-row>
                                                                <v-row v-if="activeSetting">
                                                                    <v-col class="pa-2">
                                                                        <v-btn
                                                                            class="my-handle"
                                                                            elevation="0"
                                                                            block
                                                                            x-small
                                                                            color="primary"
                                                                            dark
                                                                            rounded
                                                                        >
                                                                            <v-icon small>
                                                                                mdi-arrow-all
                                                                            </v-icon>
                                                                            <div class="pl-1 d-none d-sm-block d-md-block d-lg-block">Перетягнути</div>
                                                                        </v-btn>
                                                                    </v-col>
                                                                    <v-col class="pa-2">
                                                                        <v-btn
                                                                            elevation="0"
                                                                            block
                                                                            x-small
                                                                            color="success"
                                                                            dark
                                                                            rounded
                                                                            @click="editExercise(item, exercises_group.id)"
                                                                        >
                                                                            <v-icon small>
                                                                                mdi-pencil
                                                                            </v-icon>
                                                                            <div class="pl-1 d-none d-sm-block d-md-block d-lg-block">Редагувати</div>
                                                                        </v-btn>
                                                                    </v-col>
                                                                    <v-col class="pa-2">
                                                                        <v-btn
                                                                            elevation="0"
                                                                            block
                                                                            x-small
                                                                            color="error"
                                                                            dark
                                                                            rounded
                                                                            @click="deleteExercise(item.pivot.id, index)"
                                                                        >
                                                                            <v-icon small>
                                                                                mdi-delete
                                                                            </v-icon>
                                                                            <div class="pl-1 d-none d-sm-block d-md-block d-lg-block">Видалити</div>
                                                                        </v-btn>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-container>
                                                        </v-card>
                                                    </v-col>
                                                </v-row>
                                            </draggable>
                                        </template>
                                        <template v-slot:no-data>
                                            <v-row>
                                                <v-col>
                                                    <v-alert v-if="!loading"
                                                             type="info"
                                                             outlined
                                                    >
                                                        Дані не знайдено
                                                    </v-alert>
                                                </v-col>
                                            </v-row>
                                        </template>
                                    </v-data-iterator>
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
import draggable from 'vuedraggable';
import { validationMixin } from 'vuelidate';
import { required } from 'vuelidate/lib/validators';

export default {
    name: "Workout",

    mixins: [validationMixin],

    validations: {
        exercise: {
            id: {
                required,
            },
            approaches: {
                required,
            },
            repetition: {
                required,
            },
            weight: {
                required,
            },
        },
        exercisesEdit: {
            item: {
                id: {
                    required,
                },
                pivot: {
                    approaches: {
                        required,
                    },
                    repetition: {
                        required,
                    },
                    weight: {
                        required,
                    },
                },
            },
        },
        workoutEdit: {
            name: {
                required,
            },
            workout_style_id: {
                required,
            },
            is_published: {
                required,
            },
        },
    },

    components: {
        draggable,
    },
    data() {
        return {
            publishItems: [
                {id: 1, name: 'Опубліковано',},
                {id: 0, name: 'Не опубліковано',}
            ],

            valid: true,
            editWorkoutDialog: false,
            editWorkoutLoading: false,
            swapExercisesGroup: true,

            workoutStyleItems: [],

            deleteWorkoutDialog: false,
            deleteWorkoutLoading: false,

            deleteExerciseDialog: false,
            deleteExerciseLoading: false,
            deleteExerciseItem: {
                id: '',
                index: '',
            },

            editExerciseDialog: false,
            editExerciseLoading: false,

            search: null,
            exercisesDialog: false,
            activeSetting: false,
            createExerciseLoading: false,
            tabs: null,
            workout: {},
            exercisesList: [],
            loading: true,
            loadingUpdate: false,

            exercise: {
                id: '',
                image: '',
                approaches: '',
                repetition: '',
                weight: '',
                order_ege: '',
                id_group: '',
                index: '',
            },

            exercisesEdit: {
                item: {
                    id: '',
                    image: '',
                    pivot: {
                        approaches: '',
                        repetition: '',
                        weight: '',
                    }
                },
                index: '',
            },

            workoutEdit: {},
        };
    },
    created() {
        if (!Number(this.$route.params.id)) {
            this.loading = false;
            this.$router.push({ name: 'workouts' });
        } else {
            this.setExercises();
            this.setWorkoutStyles();
            this.setWorkout();
        }
    },
    computed: {
        workoutTitle: function () {
            return `${this.workout?.name}`;
        },
        workoutSubtitle: function () {
            return `${this.workout?.workout_style?.name}`;
        },

        idErrors: function () {
            const errors = [];
            if (!this.$v.exercise.id.$dirty) return errors;
            !this.$v.exercise.id.required && errors.push('Вкажіть вправу');
            return errors;
        },
        approachesErrors: function () {
            const errors = [];
            if (!this.$v.exercise.approaches.$dirty) return errors;
            !this.$v.exercise.approaches.required && errors.push('Вкажіть к-сть підходів');
            return errors;
        },
        repetitionErrors: function () {
            const errors = [];
            if (!this.$v.exercise.repetition.$dirty) return errors;
            !this.$v.exercise.repetition.required && errors.push('Вкажіть к-сть повторень');
            return errors;
        },
        weightErrors: function () {
            const errors = [];
            if (!this.$v.exercise.weight.$dirty) return errors;
            !this.$v.exercise.weight.required && errors.push('Вкажіть вагу');
            return errors;
        },

        editIdErrors: function () {
            const errors = [];
            if (!this.$v.exercisesEdit.item.id.$dirty) return errors;
            !this.$v.exercisesEdit.item.id.required && errors.push('Вкажіть вправу');
            return errors;
        },
        editApproachesErrors: function () {
            const errors = [];
            if (!this.$v.exercisesEdit.item.pivot.approaches.$dirty) return errors;
            !this.$v.exercisesEdit.item.pivot.approaches.required && errors.push('Вкажіть к-сть підходів');
            return errors;
        },
        editRepetitionErrors: function () {
            const errors = [];
            if (!this.$v.exercisesEdit.item.pivot.repetition.$dirty) return errors;
            !this.$v.exercisesEdit.item.pivot.repetition.required && errors.push('Вкажіть к-сть повторень');
            return errors;
        },
        editWeightErrors: function () {
            const errors = [];
            if (!this.$v.exercisesEdit.item.pivot.weight.$dirty) return errors;
            !this.$v.exercisesEdit.item.pivot.weight.required && errors.push('Вкажіть вагу');
            return errors;
        },

        nameWorkoutErrors: function () {
            const errors = [];
            if (!this.$v.workoutEdit.name.$dirty) return errors;
            !this.$v.workoutEdit.name.required && errors.push('Вкажіть назву');
            return errors;
        },
        styleWorkoutErrors: function () {
            const errors = [];
            if (!this.$v.workoutEdit.workout_style_id.$dirty) return errors;
            !this.$v.workoutEdit.workout_style_id.required && errors.push('Виберіть стиль');
            return errors;
        },
        publishErrors: function () {
            const errors = [];
            if (!this.$v.workoutEdit.is_published.$dirty) return errors;
            !this.$v.workoutEdit.is_published.required && errors.push('Виберіть статус');
            return errors;
        },
    },
    methods: {
        setExercises: async function () {
            try {
                const { data } = await axios.get('exercises');
                this.exercisesList = data;
            } catch (err) {}
        },
        setWorkoutStyles: async function () {
            try {
                const { data } = await axios.get('workout_styles');
                this.workoutStyleItems = data;
            } catch (err) {
                //
            }
        },
        setWorkout: async function () {
            try {
                const { data } = await axios.get(`/workouts/${Number(this.$route.params.id)}`);

                if (this.$route.name === 'workout') {
                    if ((this.$auth.check(1) && this.$auth.user().id === data.workout.user_id) || (this.$auth.check(1) && data.workout.user.role === 2 && data.workout.is_published) || (this.$auth.check(2) && data.workout.user.role === 2) || (!this.$auth.check() && data.workout.user.role === 2 && data.workout.is_published)) {
                        this.workout = data.workout;
                    } else {
                        await this.$router.push({name: 'workouts'});
                    }
                }

                if (this.$route.name === 'my-workout') {
                    if (this.$auth.user().id === data.workout.user_id) {
                        this.workout = data.workout;
                    } else {
                        await this.$router.push({name: 'my-workouts'});
                    }
                }
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
                if (this.$route.params.edit) {
                    this.$route.params.edit = false;
                    this.activeSetting = true;
                    this.editWorkout();
                }
            }
        },
        updateOrderExercises: async function (id) {
            this.workout.exercises_groups[id].exercises.map((element, index) => {
                element.pivot.order_ege = index + 1;
            });
            this.loadingUpdate = true;
            try {
                const { data } = await axios.put('/workouts/update_order_exercises', this.workout.exercises_groups[id]);
            }
            catch (error) {
                //
            }
            finally {
                this.loadingUpdate = false;
            }
        },
        selectExercise: function () {
            if (this.exercise.id) {
                this.exercise.image = this.exercisesList.filter(e => e.id === this.exercise.id)[0].image;
            }
        },
        closeExerciseDialog: function () {
            this.exercisesDialog = false;
            this.$v.$reset();
            this.exercise.id = '';
            this.exercise.image = '';
            this.exercise.approaches = '';
            this.exercise.repetition = '';
            this.exercise.weight = '';
        },
        createExercise: function (id, index) {
            this.exercisesDialog = true;
            this.exercise.id_group = id;
            this.exercise.index = index;
        },
        createExerciseConfirm: async function () {
            this.$v.$touch();
            if (!this.$v.exercise.$invalid) {
                this.createExerciseLoading = true;
                this.exercise.order_ege = this.workout.exercises_groups[this.exercise.index].exercises.length + 1;
                try {
                    const { data } = await axios.post(`/workouts/${this.exercise.id_group}/exercises`, this.exercise);
                    await this.setWorkout();
                    this.exercise.id = '';
                    this.exercise.image = '';
                    this.exercise.approaches = '';
                    this.exercise.repetition = '';
                    this.exercise.weight = '';
                    this.exercise.id_group = '';
                    this.exercise.index = '';
                    this.$v.exercise.$reset();
                }
                catch (error) {
                    //
                }
                finally {
                    this.$nextTick(() => {
                        window.scrollTo(0, document.body.scrollHeight);
                    });
                    this.createExerciseLoading = false;
                    this.exercisesDialog = false;
                }
            }
        },

        deleteExercise: function (id, index) {
            this.deleteExerciseDialog = true;
            this.deleteExerciseItem.id = id;
            this.deleteExerciseItem.index = index;
        },
        deleteExerciseConfirm: async function () {
            this.deleteExerciseLoading = true;

            try {
                const { data } = await axios.delete(`/workouts/${this.deleteExerciseItem.id}`);
                await this.setWorkout();
                await this.updateOrderExercises(this.deleteExerciseItem.index);
            }
            catch(error) {
                //
            }
            finally {
                this.deleteExerciseLoading = false;
                this.deleteExerciseDialog = false;
            }
        },

        deleteWorkoutConfirm: async function () {
            this.deleteWorkoutLoading = true;

            try {
                const { data } = await axios.delete(`/account/workouts/${this.workout.id}`);
                await this.$router.push({name: 'my-workouts'});
            }
            catch(error) {
                //
            }
            finally {
                this.deleteWorkoutLoading = false;
                this.deleteWorkoutDialog = false;
            }
        },

        closeEditExerciseDialog: function () {
            this.editExerciseDialog = false;
            this.$v.$reset();
            this.exercisesEdit.item.id = '';
            this.exercisesEdit.item.image = '';
            this.exercisesEdit.item.pivot.approaches = '';
            this.exercisesEdit.item.pivot.repetition = '';
            this.exercisesEdit.item.pivot.weight = '';
            this.exercisesEdit.index = '';
        },
        editExercise: function (item, index) {
            this.editExerciseDialog = true;
            this.exercisesEdit.item = _.cloneDeep(item);
            this.exercisesEdit.index = index;
        },
        editExerciseConfirm: async function () {
            this.$v.$touch();
            if (!this.$v.exercisesEdit.$invalid) {
                this.editExerciseLoading = true;
                try {
                    const { data } = await axios.put('/workouts/exercise', this.exercisesEdit);
                    await this.setWorkout();
                    this.exercisesEdit.item.id = '';
                    this.exercisesEdit.item.image = '';
                    this.exercisesEdit.item.pivot.approaches = '';
                    this.exercisesEdit.item.pivot.repetition = '';
                    this.exercisesEdit.item.pivot.weight = '';
                    this.exercisesEdit.index = '';
                    this.$v.$reset();
                }
                catch (error) {
                    //
                }
                finally {
                    this.editExerciseLoading = false;
                    this.editExerciseDialog = false;
                }
            }
        },
        selectEditExercise: function () {
            if (this.exercisesEdit.item.id) {
                this.exercisesEdit.item.image = this.exercisesList.filter(e => e.id === this.exercisesEdit.item.id)[0].image;
            }
        },

        closeEditWorkoutDialog: function () {
            this.editWorkoutDialog = false;
            this.$v.$reset();
            this.workoutEdit = '';
            this.swapExercisesGroup = true;
        },
        editWorkout: function () {
            this.editWorkoutDialog = true;
            this.workoutEdit = _.cloneDeep(this.workout);
        },
        updateOrder: function () {
            this.workoutEdit.exercises_groups.map((element, index) => {
                element.order_eg = index + 1;
            });
        },
        addExercisesGroup: function () {
            this.workoutEdit.exercises_groups.push({
                exercises: [],
                id: '',
                name: '',
                order_eg: this.workoutEdit.exercises_groups.length + 1,
                workout_id: this.workoutEdit.id,
            });
        },
        deleteExercisesGroup: function (index) {
            this.workoutEdit.exercises_groups.splice(index, 1);
        },
        editWorkoutConfirm: async function () {
            this.$v.workoutEdit.$touch();
            this.$refs.form.validate();
            if (!this.$v.workoutEdit.$invalid && this.valid) {
                if (!this.workoutEdit.exercises_groups.some(e => e.name === '')) {
                    this.editWorkoutLoading = true;
                    try {
                        const { data } = await axios.put(`/account/workouts/${this.workoutEdit.id}`, this.workoutEdit);
                        await this.setWorkout();
                        this.workoutEdit = '';
                        this.swapExercisesGroup = true;
                        this.$v.$reset();
                    }
                    catch (error) {
                        //
                    }
                    finally {
                        this.editWorkoutLoading = false;
                        this.editWorkoutDialog = false;
                    }
                }
            }
        },
    },
}
</script>

<style scoped>
    .v-alert {
        margin: 0 !important;
    }
    a {  text-decoration: none; }
    .v-card__title {
        word-break: normal !important;
    }
    .v-tab {
        color: rgba(13,71,161,.7) !important;
    }
    .v-tab.v-tab--active {
        font-weight: bold;
        color: rgba(13,71,161,1) !important;
    }
    .v-card >>> .v-card__title.title {
        font-weight: bold;
        background: #E3F2FD !important;
        color: rgba(13,71,161,1) !important;
    }
    .v-card >>> .v-card__subtitle.subtitle {
        background: #E3F2FD !important;
        color: rgba(13,71,161,.7) !important;
    }
    .name-exercise {
        color: black !important;
        transition: .2s;
    }
    .name-exercise:hover {
        color: #1976D2 !important;
    }
    .v-card >>> .v-card__title.title-exercises {
        font-weight: bold;
        background: #1976D2 !important;
        color: white !important;
    }
    .v-card >>> .v-card__title.title-workout {
        font-weight: bold;
        background: #1976D2 !important;
        color: white !important;
    }
    .workout-groups-exercises {
        background: #1976D2 !important;
    }
    .workout-groups-exercises >>> .v-card__subtitle {
        font-weight: bold;
        color: white !important;
    }
</style>
