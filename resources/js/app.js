require('./bootstrap');

window.Vue = require('vue');

import 'materialize-css/dist/css/materialize.min.css';
import 'material-design-icons/iconfont/material-icons.css';

Vue.component('app', require('./components/App.vue').default);

import VueRouter from 'vue-router';

import Home from "./components/Home/Home";

Vue.use(VueRouter);

const routes = [
    { path: '/', component: Home, name: 'Home' },
];
const router = new VueRouter({
    routes
});

const app = new Vue({
    el: '#app',
    router,
});
