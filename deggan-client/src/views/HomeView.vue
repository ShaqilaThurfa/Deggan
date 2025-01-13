<template>
  <main>
    <h1 class="text-md md:text-xl lg:text-2xl text-black font-bold flex justify-center py-5">
      Today News!
    </h1>
    <LoadingSpinner v-if="loading" />
    <div
      v-else
      class="mx-4 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3 grid-auto-rows-[1fr] py-5 flex-grow place-items-center"
    >
      <NewsCard v-for="news in data" :key="news.id" :news="news" />
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import NewsCard from '@/components/NewsCard.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import Swal from 'sweetalert2'
import apiClient from '@/config/axios'

const data = ref([])
const loading = ref(true)
const imageURL = import.meta.env.VITE_BASE_IMAGE_URL

onMounted(async () => {
  try {
    const limit = 10
    const page = 1
    const response = await apiClient.get(`/news?limit=${limit}&page=${page}`)
    
    
    data.value = response.data.map((post) => ({
      ...post,
      image_url: `${imageURL}/${post.image}` , 
    }))
   
  } catch (error) {
    let title = 'Error'
    let details = 'Something went wrong. Please try again later.'

    if (error.response) {
      const { message, errors } = error.response.data
      title = message || 'Error'
      details = errors ? Object.values(errors).flat().join('<br>') : details
    } else if (error.message) {
      details = error.message
    }

    Swal.fire({
      title,
      html: `<b>${details}</b>`,
      icon: 'error',
    })
  } finally {
    loading.value = false
  }
})
</script>
