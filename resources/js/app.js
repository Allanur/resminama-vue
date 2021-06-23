/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Router from './router';
import Auth from "./packages/auth/Auth";
import VueResource from "vue-resource";
import VueFeather from 'vue-feather';
import VModal from 'vue-js-modal';
import VueLoading from 'vue-simple-loading';

window.Vue = require('vue');

Vue.use(VueFeather);
Vue.use(VueResource);
Vue.use(VModal);
Vue.use(Auth);
Vue.component('VueLoading', VueLoading);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('home', require('./components/Home.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.http.options.root = 'http://127.0.0.1:8000';
Vue.http.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();

Router.beforeEach(
    (to, from, next) => {

        if (to.matched.some(record => record.meta.forVisitors)) {
            if (Vue.auth.isAuthenticated()) {
                next({
                    path: '/'
                })
            } else next()
        }

        else if (to.matched.some(record => record.meta.forAuth)) {
            if (! Vue.auth.isAuthenticated()) {
                next({
                    path: '/login'
                })
            } else {
                next()
            }
        }

        else next()
    }
);

const app = new Vue({
    el: '#app',
    router: Router,
});
