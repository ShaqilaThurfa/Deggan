<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import apiConfig from '@/config/api.config';
import NewsCard from '@/components/NewsCard.vue';


const data = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await axios.get(`${apiConfig.baseURL}/news`);
    data.value = response.data;
  
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
});

</script>

<template>
  <main>
    <h1 class="text-md md:text-xl lg:text-2xl text-black font-bold flex justify-center py-5">Today News!</h1>
    <div v-if="loading">Loading...</div>
    <div v-else class="mx-4 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3 grid-auto-rows-[1fr] py-5 flex-grow place-items-center">
      <NewsCard v-for="news in data" :key="news.id" :news="news" />
    </div>
  </main>
</template>
