<template>
  <div class="navbar bg-black">
    <div class="navbar-start">
      <router-link to="/" class="btn btn-ghost text-xl text-white">News</router-link>
    </div>

    <div class="navbar-end">
      <router-link v-if="authStore.token" to="/myposts" class="btn btn-ghost text-white"
        >My Posts</router-link
      >
      <router-link
        v-if="authStore.token"
        to="/login"
        class="btn btn-ghost text-white"
        @click="handleLogout"
      >
        Logout
      </router-link>
      <router-link v-else to="/login" class="btn btn-ghost text-white">Login</router-link>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const handleLogout = () => {
  authStore.logout()
}

onMounted(() => {
  authStore.initializeAuth();
});
</script>
