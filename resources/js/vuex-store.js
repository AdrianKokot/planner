import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';

export default new Vuex.Store({
  state: {
    user: null
  },

  mutations: {
    setUserData(state, data) {
      state.user = data.user
      localStorage.setItem('user', JSON.stringify(data))
      axios.defaults.headers.common.Authorization = `Bearer ${data.token}`
    },
    clearUserData() {
      localStorage.removeItem('user')
      location.reload()
    },
    handleError(err) {
      throw err;
    }
  },

  actions: {
    login({ commit }, credentials) {
      return axios
        .post('/login', credentials)
        .then(({ data }) => {
          commit('setUserData', data)
        })
        .catch(err => {
          localStorage.removeItem('user');

          if (err.response.status === 422) {
            commit('handleError', err);
          }
        })
    },
    logout({ commit }) {
      commit('clearUserData')
    }
  },

  getters: {
    isLogged: state => !!state.user
  }
})