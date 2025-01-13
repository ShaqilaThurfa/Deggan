import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import { useAuthStore } from '@/stores/auth'
import MypostsView from '@/views/MypostsView.vue'
import Swal from 'sweetalert2'
import DetailPostView from '@/views/DetailPostView.vue'
import CreateNewsView from '@/views/CreateNewsView.vue'




const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { showNavbar: true, requiresAuth: false },
    },
    {
      path: '/myposts',
      name: 'myposts',
      component: MypostsView,
      meta: { showNavbar: true, requiresAuth: true },
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { showNavbar: false, requiresAuth: false },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { showNavbar: false, requiresAuth: false },
    },
    {
      path: '/detail/:slug',
      name: 'detail',
      component: DetailPostView,
      meta: { showNavbar: true, requiresAuth: false },
    },
    {
      path: '/create',
      name: 'create',
      component: CreateNewsView,
      meta: { showNavbar: true, requiresAuth: true },
    },

  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.token) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'You need to login to access this page',
    });

    next('/login'); 
  } else if (to.name === 'login' && authStore.token) {
    next('/'); 
  } else {
    next(); 
  }
});

export default router
