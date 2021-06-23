import Vue from 'vue';
import VueRouter from "vue-router";

import Home from './components/Home';
import Panel from './components/Panel';
import Login from './components/Login';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [{
        path: '',
        name: 'home',
        component: Home,
    }, {
        path: '/panel',
        name: 'panel',
        component: Panel,
        meta: {
            forAuth: true,
        }
    }, {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            forVisitors: true,
        }
    }],
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
});

export default router;
