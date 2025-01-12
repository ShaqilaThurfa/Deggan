<template>
  <div>
    <h1>My Posts</h1>

    <LoadingSpinner v-if="loading" />

    <p v-else-if="message">{{ message }}</p>
    <NewsCard v-else v-for="post in data" :key="post.id" :news="post" :isMyPostsPage="true" />
  </div>
</template>

<script setup>
import NewsCard from '@/components/NewsCard.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'
import apiConfig from '@/config/api.config'
import LoadingSpinner from '@/components/LoadingSpinner.vue'


const data = ref([])
const loading = ref(true)
const message = ref('')

onMounted(async () => {
  try {
    const response = await axios.get(`${apiConfig.baseURL}/myposts`)
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
