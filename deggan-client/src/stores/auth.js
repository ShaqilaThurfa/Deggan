import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null, 
    token: null, 
  }),
  actions: {
    login(response) {
    
      this.user = response.user;
      this.token = response.authorization.token;
      localStorage.setItem('token', this.token);
      localStorage.setItem('user', JSON.stringify(this.user));
    },
    logout() {
   
      this.user = null;
      this.token = null;

      localStorage.removeItem('token');
      localStorage.removeItem('user');
    },
    initializeAuth() {
      
      const storedToken = localStorage.getItem('token');
      if (storedToken) {
        this.token = storedToken; 
      } else {
        this.token = null; 
      }
    },
  },
});
