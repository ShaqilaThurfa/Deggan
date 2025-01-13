<template>
  <div class="flex flex-col items-center justify-center h-screen gap-y-5 px-3 sm:px-0">
    <h1 class="text-2xl font-bold mb-5">Login</h1>

    <label class="w-full max-w-sm">
      <input 
        type="text" 
        class="input input-bordered rounded-lg w-full p-2" 
        placeholder="Email" 
        v-model="email" 
      />
    </label>
    <label class="w-full max-w-sm">
      <input 
        type="password" 
        class="input input-bordered rounded-lg w-full p-2" 
        placeholder="Password" 
        v-model="password" 
      />
    </label>
    <button 
      class="btn btn-outline rounded-lg w-full max-w-sm p-2 mt-3" 
      @click="login"
    >
      Login
    </button>

    <h3 class="text-sm text-center mt-5">
      Don't have an account?
      <router-link to="/register" class="text-blue-500">Register</router-link>
    </h3>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import apiClient from '@/config/axios'

const email = ref('')
const password = ref('')
const router = useRouter()
const authStore = useAuthStore()

const login = async () => {
  try {
    const response = await apiClient.post(`login`, {
      email: email.value,
      password: password.value,
    })

    authStore.login(response.data)

    Swal.fire({
      title: 'Login Successful',
      text: 'You have successfully login',
      icon: 'success',
    })

    email.value = ''
    password.value = ''

    router.push('/')

  } catch (error) {
    
    const { message, errors } = error.response.data

    
    const errorDetails = Object.values(errors).flat().join('<br>') 

    Swal.fire({
      title: 'Login Failed',
      html: `<b>${message}</b><br>${errorDetails}`,
      icon: 'error',
    })
  }
}
</script>
