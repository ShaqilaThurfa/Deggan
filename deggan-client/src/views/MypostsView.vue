<template>
  <div class="flex justify-center items-center py-8">
    <button
      @click="modalRef.open()"
      class="flex items-center gap-2 bg-black text-white px-4 py-2 rounded hover:bg-gray-500 focus:outline-none "
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="w-6 h-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
        />
      </svg>
      <span>Create New Post</span>
    </button>
  </div>
  <div>
    <LoadingSpinner v-if="loading" />

    <p class="text-2xl font-bold text-center" v-else-if="message">{{ message }}</p>
    <NewsCard v-else v-for="post in data" :key="post.id" :news="post" :isMyPostsPage="true" />
  </div>
  <NewsModal ref="modalRef" />
</template>

<script setup>
import NewsCard from '@/components/NewsCard.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'
import apiConfig from '@/config/api.config'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import NewsModal from '@/components/NewsModal.vue'



const data = ref([])
const loading = ref(true)
const message = ref('')
const modalRef = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get(`${apiConfig.baseURL}/myposts`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    if (response.data.message) {
      message.value = response.data.message
    } else {
      data.value = response.data
    }
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
})
</script>
