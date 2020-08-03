require('./bootstrap');

import VueRouter from 'vue-router';

window.Vue = require('vue');

Vue.use(VueRouter);

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
});
