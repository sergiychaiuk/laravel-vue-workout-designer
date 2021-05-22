import Vue from 'vue';
import VueRouter from 'vue-router';

import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import Account from "./pages/user/Account";

Vue.use(VueRouter);

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
    },
    {
        path: '/account',
        name: 'account',
        component: Account,
    },
    {
        path: '*',
        redirect: '/home',
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
});
