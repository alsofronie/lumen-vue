import Vue from 'vue';
import VueI18n from 'vue-i18n';
import moment from 'moment';
import preferences from './preferences';
Vue.use(VueI18n);

import locale from './../locale/en';
locale.locale = preferences.lang;
moment.locale(preferences.lang);

export default new VueI18n(locale);
