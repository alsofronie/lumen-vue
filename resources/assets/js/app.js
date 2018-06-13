import Vue from 'vue';

import prefs from './lib/preferences';

import store from './lib/store';
import http from './lib/http';
import router from './lib/router';
import bus from './lib/bus';
import i18n from './lib/i18n';

Vue.component('navigation', require('./components/Navigation'));

Vue.prototype.$http = http;
Vue.prototype.bus = bus;

const init = () => setTimeout(() => {
    new Vue({
        el: '#app',
        store,
        router,
        i18n,
        mounted() {
            this.bus.$on('server-auth', (err) => {
                this.bus.$emit('notify', {theme:'is-danger', text: this.$t('login_expired')});
                prefs.token = null;
                this.$store.commit('logout');
                if (this.$route.meta.public !== true) {
                    this.$nextTick(() => {
                        this.$router.replace('/');
                    });
                }
            });
        },
        computed: {
            logged() {
                return this.$store.getters.logged;
            }
        },
        components: {
            'system-messages': require('./components/SystemMessages'),
        }
    });
}, 5);

if (prefs.token) {
    store.commit('token', prefs.token);
    http.get('/profile')
        .then(response => {
            store.commit('user', response.data.user);
            init();
        })
        .catch (() => {
            prefs.token = null;
            store.commit('logout');
            init();
        })
    ;
} else {
    init();
}
