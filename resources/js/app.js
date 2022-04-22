require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes';


window.Vue = require('vue').default;

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes,
});

new Vue({
    el: '#app',
    router
});
