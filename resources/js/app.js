require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes';
import VueMeta from 'vue-meta';

window.Vue = require('vue').default;

Vue.use(VueRouter);

Vue.use(VueMeta, {
  refreshOnceOnNavigation: true
});

const router = new VueRouter({
    mode: 'history',
    routes,
});

new Vue({
    el: '#app',
    router
});
