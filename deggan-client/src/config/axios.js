import axios from 'axios';
import apiConfig from './api.config'; 
import { useAuthStore } from '@/stores/auth'; 


const apiClient = axios.create({
  baseURL: apiConfig.baseURL, 
  headers: {
    'Content-Type': 'application/json',
  },
});


apiClient.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);



apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    const authStore = useAuthStore();

    if (error.response && error.response.status === 401) {
      try {
        
        await authStore.refreshTokenIfNeeded();

        const originalRequest = error.config;
        originalRequest.headers.Authorization = `Bearer ${authStore.token}`;
        return apiClient.request(originalRequest);
      } catch (refreshError) {
        authStore.logout();
        return Promise.reject(refreshError);
      }
    }

    return Promise.reject(error);
  }
);

export default apiClient;
