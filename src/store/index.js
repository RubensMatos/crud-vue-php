// store/index.js

import { createStore } from 'vuex';
import api from '@/services/api'; // Importe o módulo de chamadas AJAX

const store = createStore({
  state: {
    isAuthenticated: false, // Exemplo de estado de autenticação
  },
  mutations: {
    SET_AUTHENTICATED(state, isAuthenticated) {
      state.isAuthenticated = isAuthenticated;
    }
  },
  actions: {
    checkAuthentication({ commit }) {

      const authToken = localStorage.getItem('authToken');

      if (authToken == 1) {

        commit('SET_AUTHENTICATED', true);
      }
    },
    async performLogin({ commit }, credentials) {
      try {
        // Realize a chamada AJAX para o login
        const response = await api.login(credentials);

        if(response.data[0] === 1){
        
          localStorage.setItem('authToken', response.data[0]);

          commit('SET_AUTHENTICATED', true);
        }

        return response; // Você pode retornar a resposta se necessário
      } catch (error) {
        // Lide com erros, por exemplo, exiba uma mensagem de erro
        console.error('Erro de login:', error);
        throw error; // Propague o erro para o componente que chamou a ação
      }
    },
    performLogout({ commit }) {
      // Execute ações para fazer logout, se necessário
      commit('SET_AUTHENTICATED', false);

      localStorage.setItem('authToken', 0);
    },
  },
});

export default store;