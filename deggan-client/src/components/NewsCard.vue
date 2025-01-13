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
          <button class="btn btn-error">Delete</button>
        </template>
        <template v-else>
          <button class="btn btn-primary" @click="goToDetail(news.slug)">Read More</button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

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

function goToDetail(slug) {
  router.push({ name: 'detail', params: { slug } })
}

function handleEdit(news) {
  router.push({ name: 'edit', params: { slug: news.slug } })
}


const defaultImage =
  'https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg'
</script>
