import VueRouter from 'vue-router';

import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import Account from "./pages/user/Account";

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
            auth: true
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
})
export default router
