<template>
  <div class="card bg-base-100 w-96 shadow-xl gap-3 grid grid-cols-1">
    <figure>
      <img
        :src="news.image_url || defaultImage"
        :alt="news.title || 'No image available'"
        class="w-full h-48 object-cover"
      />
    </figure>
    <div class="card-body">
      <h2 class="card-title">{{ news.title }}</h2>
      <p>{{ news.content }}</p>
      <div class="card-actions justify-end">
        <template v-if="isMyPostsPage">
          <button @click="handleEdit(news)" class="btn btn-warning">Edit</button>
          <button class="btn btn-error" @click="handleDelete(news.id)">Delete</button>
        </template>
        <template v-else>
          <button class="btn btn-primary" @click="goToDetail(news.slug)">Read More</button>
        </template>
      </div>
    </div>
  </div>
</template>

<!-- <script setup>
import apiConfig from '@/config/api.config'
import axios from 'axios'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

defineProps({
  news: {
    type: Object,
    required: true,
  },
  isMyPostsPage: {
    type: Boolean,
    default: false,
  },
})

const router = useRouter()
const emit = defineEmits(['delete'])

function goToDetail(slug) {
  router.push({ name: 'detail', params: { slug } })
}

function handleEdit(news) {
  router.push({ name: 'edit', params: { slug: news.slug } })
}

async function handleDelete(id) {
  console.log('Delete post with id:', id)

  try {
    await axios.delete(`${apiConfig.baseURL}/news/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })

    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Post deleted successfully',
    })
  } catch (error) {
    console.error('Error deleting post:', error)

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to delete post',
    })
  }
}

const defaultImage =
  'https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg'
</script> -->

<script setup>
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

defineProps({
  news: {
    type: Object,
    required: true,
  },
  isMyPostsPage: {
    type: Boolean,
    default: false,
  },
})

const router = useRouter()
const emit = defineEmits(['delete'])

function goToDetail(slug) {
  router.push({ name: 'detail', params: { slug } })
}

function handleEdit(news) {
  router.push({ name: 'edit', params: { slug: news.slug } })
}

function handleDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
  }).then((result) => {
    if (result.isConfirmed) {
      emit('delete', id)
    }
  })
}

const defaultImage =
  'https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg'
</script>
