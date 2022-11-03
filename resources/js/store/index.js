import axios from 'axios';
import { createStore } from 'vuex'

export const store = createStore({
  state() {
    return {
      isLogin: false,
      user: '',
    }
  },

  getters: {
    login(state) {
      return state.isLogin
    },
    user(state) {
      return state.user
    }
  },
  
  mutations: {
    setLoginState(state, value) {
      state.isLogin = value;
    },

    setUser(state, value) {
      state.user = value
    }
  },

  actions: {
    async getUser({commit}) {
      try {
        await axios.get('/api/user')
        .then((res) => {
          commit('setLoginState', true)
          commit('setUser', res.data)
        })
        .catch(() => {
          commit('setLoginState', false)
          commit('setUser', null)
        })
      }catch(e) {

      }
    }
  }
})