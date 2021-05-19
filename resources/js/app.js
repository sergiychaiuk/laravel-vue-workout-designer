require('./bootstrap');

window.Vue = require('vue').default;

import Vue from "vue";
import vuetify from './vuetify';

Vue.component('index', require('./Index').default);

const app = new Vue({
    el: '#app',
    vuetify
});
