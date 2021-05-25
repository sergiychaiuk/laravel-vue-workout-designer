require('./bootstrap');

import 'es6-promise/auto';
import axios from 'axios';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import auth from './auth';
import vuetify from './vuetify';
import router from './router';
import store from './store';

window.Vue = require('vue').default;

import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    // height: '2px',
    thickness: '5px',
});

Vue.router = router;
Vue.use(VueRouter);

Vue.use(VueAxios, axios);
axios.defaults.baseURL = `http://127.0.0.1:8000/api`;

Vue.use(VueAuth, auth);

Vue.component('index', require('./Index').default);

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    store,
});
