require('./bootstrap');

import Vue from 'vue';

import http from './services/http-common';


const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import router from './router';
const store = require('./vuex-store').default;
import { BootstrapVue } from 'bootstrap-vue'
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    router,
    store,
    created () {
      const userInfo = localStorage.getItem('user')
      if (userInfo) {
        const userData = JSON.parse(userInfo)
        this.$store.commit('setUserData', userData)
      }
      http.interceptors.response.use(
        response => response,
        error => {
          if (error.response.status === 401) {
            this.$store.dispatch('logout')
          }
          return Promise.reject(error)
        }
      )
    }
});

