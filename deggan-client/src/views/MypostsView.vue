<template>
  <div class="flex justify-center items-center py-8">
    <button
      @click="router.push({ name: 'create' })"
      class="flex items-center gap-2 bg-black text-white px-4 py-2 rounded hover:bg-gray-500 focus:outline-none"
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

    <div
      v-else
      class="mx-4 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3 auto-rows-fr py-5 place-items-center"
    >
      <NewsCard
        v-for="post in data"
        :key="post.id"
        :news="post"
        :isMyPostsPage="true"
        @delete="deletePost"
      />
    </div>
  </div>
</template>

<script setup>
import NewsCard from '@/components/NewsCard.vue'
import { ref, onMounted } from 'vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import apiClient from '@/config/axios'

const data = ref([])
const loading = ref(true)
const message = ref('')
const router = useRouter()
const imageURL = import.meta.env.VITE_BASE_IMAGE_URL

async function deletePost(id) {
  try {
    await apiClient.delete(`/news/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    data.value = data.value.filter((post) => post.id !== id)

    Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'Your post has been deleted.',
    })
  } catch (error) {
    console.error('Error deleting post:', error)

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to delete post.',
    })
  }
}


onMounted(async () => {
  try {
    const response = await apiClient.get(`/myposts`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    const mappedPosts = response.data.map((post) => ({
      ...post,
      image_url: `${imageURL}/${post.image}`,
    }))

    console.log(mappedPosts.map((post) => post.image_url))

    if (response.data.message) {
      message.value = response.data.message
    } else {
      data.value = mappedPosts
    }
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
})
</script>
