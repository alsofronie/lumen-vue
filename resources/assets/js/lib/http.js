import axios from 'axios';
import store from './store';
import bus from './bus';
import setup from './setup';

const http = axios.create({
    baseURL: setup.api.url,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
});

http.interceptors.request.use(function (config) {
    let tok = store.getters.token;
    if (tok !== null) {
        config.headers['Authorization'] = 'Bearer ' + tok;
    }
    config.headers['X-Api-Token'] = setup.api.token;

    return config;
});

http.interceptors.response.use((response) => {

    return response;
}, (error) => {
    if (error.response && (error.response.status === 401 || error.response.status === 403)) {
        bus.$emit('server-auth', error);
    }
    return Promise.reject(error);
});

export default http;
