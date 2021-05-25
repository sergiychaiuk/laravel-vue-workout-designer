import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        muscleGroups: [],
        sportsProjectiles: [],
        exercises: [],
        muscles: [],
    },
    mutations: {
        SET_MUSCLE_GROUPS(state, muscleGroups) {
            state.muscleGroups = muscleGroups;
        },
        SET_SPORTS_PROJECTILES(state, sportsProjectiles) {
            state.sportsProjectiles = sportsProjectiles;
        },
        SET_EXERCISES(state, exercises) {
            state.exercises = exercises;
        },
        SET_MUSCLES(state, muscles) {
            state.muscles = muscles;
        },
    },
    actions: {
        //
    },
});
