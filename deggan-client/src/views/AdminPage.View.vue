<template>
  <div>
    <h1 class="text-2xl font-bold text-center mt-5">Admin Page</h1>
  </div>
  <div class="overflow-x-auto mx-10 mt-5">
    <table class="table table-zebra w-full">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td colspan="5" class="text-center">
            <LoadingSpinner />
          </td>
        </tr>
        <tr v-else-if="data.length === 0">
          <td colspan="5" class="text-center">No data available</td>
        </tr>
        <tr v-for="(user, index) in data" :key="user.id">
          <td>{{ index + 1 }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.role }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script setup>
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import apiClient from '@/config/axios';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';


const data = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await apiClient.get(`/all-user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    
    data.value = response.data
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.message || 'Failed to fetch posts.',
    })
  } finally {
    loading.value = false
  }
})
</script>