import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const state = {
    user: null,
    token: null,
};

const mutations = {
    user (state, payload) {
        state.user = payload;
    },
    token (state, payload) {
        state.token = payload;
    },
    logout (state) {
        state.user = null;
        state.token = null;
    }
};

const getters = {
    user: state => {
        return state.user;
    },
    token: state => {
        return state.token;
    },
    logged: state => {
        return (state.user !== null && state.token !== null);
    },
};

export default new Vuex.Store({
    state,
    mutations,
    getters,
});
