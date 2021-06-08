import VueRouter from 'vue-router';

import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import Account from "./pages/user/Account";
import MusclesList from "./pages/MusclesList";
import ExercisesList from "./pages/ExercisesList";
import Exercise from "./pages/Exercise";
import Muscle from "./pages/Muscle";
import Workouts from "./pages/Workouts";
import Workout from "./pages/Workout";
import MyWorkouts from "./pages/user/Workouts";

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        },
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/account',
        name: 'account',
        component: Account,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}}
        },
    },
    {
        path: '/muscles',
        name: 'muscles',
        component: MusclesList,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/exercises',
        name: 'exercises',
        component: ExercisesList,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/exercises/:id',
        name: 'exercise',
        component: Exercise,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/muscles/:id',
        name: 'muscle',
        component: Muscle,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/workouts',
        name: 'workouts',
        component: Workouts,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/workouts/:id',
        name: 'workout',
        component: Workout,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/my-workouts',
        name: 'my-workouts',
        component: MyWorkouts,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}}
        },
    },
    {
        path: '/my-workouts/:id',
        name: 'my-workout',
        component: Workout,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}}
        },
    },
    {
        path: '*',
        redirect: '/home',
    },
];

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    },
});
export default router;
