import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './store';

const routes = [
    {
        path: '/',
        exact: true,
        component: require('../pages/Welcome'),
        meta: { login: false, },
    },
    {
        path: '/login',
        exact: true,
        component: require('./../pages/Login'),
        meta: { login: false, }
    },
    {
        path: '/register',
        exact: true,
        component: require('./../pages/Register'),
        meta: { login: false, }
    },
    {
        path: '/dashboard',
        exact: true,
        component: require('../pages/Dashboard'),
        meta: { login: true, }
    },
    {
        path: '/profile', exact: true, component: require('./../pages/Profile'),
        meta: { login: true, },
        children: [
            {
                path: '',
                exact: true,
                component: require('./../pages/ProfileAccount'),
                meta: { login: true, }
            },
            {
                path: 'security',
                exact: true,
                component: require('./../pages/ProfileSecurity'),
                meta: { login: true, }
            },
        ]
    },
];

const router = new VueRouter({
    routes,
    linkExactActiveClass: 'is-active',
});

router.beforeEach((to, from, next) => {
    if (to.meta.login && !store.getters.logged) {
        next({path: '/', replace: true});
        return;
    }
    next();
});

export default router;
