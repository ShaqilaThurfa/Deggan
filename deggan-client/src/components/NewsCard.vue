<template>
  <div class="card bg-base-100 w-96 shadow-xl gap-3">
    <figure>
      <img :src="news.imageUrl || defaultImage" :alt="news.title || 'No image available'" />
    </figure>
    <div class="card-body">
      <h2 class="card-title">{{ news.title }}</h2>
      <p>{{ news.description }}</p>
      <div class="card-actions justify-end">
        <button class="btn btn-primary">Read More</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  news: {
    type: Object,
    required: true,
  },
});

const defaultImage = 'https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg';
</script>
