<template>
  <NewsForm 
    :isCreatePage="false" 
    :initialData="selectedPost" 
  />
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import apiConfig from '@/config/api.config';
import NewsForm from '@/components/NewsForm.vue';

const route = useRoute();
const selectedPost = ref(null);

onMounted(async () => {
  try {
    const slug = route.params.slug; 
    const response = await axios.get(`${apiConfig.baseURL}/news/${slug}`);
    selectedPost.value = response.data; 
    
  } catch (error) {
    console.error('Failed to fetch post:', error);
  }
});
</script>
