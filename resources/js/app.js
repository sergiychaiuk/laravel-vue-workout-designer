require('./bootstrap');

window.Vue = require('vue').default;

import Vue from "vue";
import vuetify from './vuetify';
import router from './router';

import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    // height: '2px',
    thickness: '5px',
});

Vue.component('index', require('./Index').default);

const app = new Vue({
    el: '#app',
    router,
    vuetify,
});
