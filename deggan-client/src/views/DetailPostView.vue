<template>
  <div class="px-24 py-10">
    <div v-if="loading">
      <LoadingSpinner />
    </div>

    <div v-else-if="data" class="card bg-base-100 shadow-xl gap-5 py-5 px-5">
      <figure>
        <img :src="data.imageUrl || defaultImage" :alt="data.title || 'No image available'" />
      </figure>
      <div class="card-body bg-black bg-opacity-50">
        <h2 class="card-title text-gray-200">{{ data.title }}</h2>
        <p class="text-gray-100">{{ data.content }}</p>
      </div>
    </div>
    <div v-else class="text-center py-5">
      <p>No news found</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import Swal from 'sweetalert2'
import apiClient from '@/config/axios'

const data = ref(null)
const loading = ref(true)
const defaultImage =
  'https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg'
const route = useRoute()
const imageURL = import.meta.env.VITE_BASE_IMAGE_URL

onMounted(async () => {
  const slug = route.params.slug
  try {
    const response = await apiClient.get(`/news/${slug}`)
    data.value = {
      ...response.data,
      imageUrl: response.data.image ? `${imageURL}/${response.data.image}` : defaultImage,
    }
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
