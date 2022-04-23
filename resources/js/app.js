require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes';
import VueMeta from 'vue-meta';
import VueSweetalert2 from 'vue-sweetalert2';


import 'sweetalert2/dist/sweetalert2.min.css';

window.Vue = require('vue').default;

Vue.use(VueRouter);

Vue.use(VueMeta, {
  refreshOnceOnNavigation: true
});

Vue.use(VueSweetalert2);

const router = new VueRouter({
    mode: 'history',
    routes,
});

new Vue({
    el: '#app',
    router
});
