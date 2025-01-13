import apiConfig from '@/config/api.config';
import axios from 'axios';
import { defineStore } from 'pinia';


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null, 
    token: null, 
    refreshToken: null,
  }),
  actions: {
    login(response) {
    
      this.user = response.user;
      this.refreshToken = response.authorization.refreshToken;
      this.token = response.authorization.token;
      localStorage.setItem('token', this.token);
      localStorage.setItem('refreshToken', this.refreshToken);
      localStorage.setItem('user', JSON.stringify(this.user));
      
    },
    logout() {
   
      this.user = null;
      this.token = null;
      this.refreshToken = null;

      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('refreshToken');
    },
    initializeAuth() {
      
      const storedToken = localStorage.getItem('token');
      const storedRefreshToken = localStorage.getItem('refreshToken');
      if (storedToken) {
        this.token = storedToken; 
        this.refreshToken = storedRefreshToken;
      } else {
        this.token = null; 
        this.refreshToken = null; 
      }
    },
    async refreshToken() {
      try {
        const response = await axios.post(`${apiConfig.baseURL}/refresh`, {
          refreshToken: this.refreshToken,
        });
        this.token = response.data.token;
        localStorage.setItem('token', this.refreshToken);
      } catch (error) {
        console.error('Error refreshing token', error);
        this.logout();
      }
      
    },
  },
});
