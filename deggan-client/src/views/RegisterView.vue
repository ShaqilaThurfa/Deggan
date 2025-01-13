<template>
  <div class="flex flex-col items-center justify-center h-screen gap-y-5 px-3 sm:px-0">
    <h1 class="text-2xl font-bold mb-5">Register</h1>

    <label class="w-full max-w-sm">
      <input 
        type="text" 
        class="input input-bordered rounded-lg w-full p-2" 
        placeholder="Name" 
        v-model="name" 
      />
    </label>
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
      @click="register"
    >
      Register
    </button>

    <h3 class="text-sm text-center mt-5">
      Already have an account? 
      <router-link to="/login" class="text-blue-500">Login</router-link>
    </h3>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Swal from 'sweetalert2'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import apiClient from '@/config/axios'

const name = ref('')
const email = ref('')
const password = ref('')
const authStore = useAuthStore()
const router = useRouter()

const register = async () => {
  try {
    const response = await apiClient.post(`/register`, {
      name: name.value,
      email: email.value,
      password: password.value,
    })
    
    authStore.login(response.data)

    Swal.fire({
      title: 'Registration Successful',
      text: 'You have successfully registered',
      icon: 'success',
    })

    name.value = ''
    email.value = ''
    password.value = ''

    router.push('/')
    
  } catch (error) {
    
    const { message, errors } = error.response.data

    
    const errorDetails = Object.values(errors).flat().join('<br>') 

    Swal.fire({
      title: 'Registration Failed',
      html: `<b>${message}</b><br>${errorDetails}`,
      icon: 'error',
    })
  }
}
</script>
