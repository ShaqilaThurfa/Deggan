<template>
  <NewsForm 
    :isCreatePage="false" 
    :initialData="selectedPost" 
  />
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import NewsForm from '@/components/NewsForm.vue';
import apiClient from '@/config/axios';

const route = useRoute();
const selectedPost = ref(null);

onMounted(async () => {
  try {
    const slug = route.params.slug; 
    const response = await apiClient.get(`/news/${slug}`);
    selectedPost.value = response.data; 
    
  } catch (error) {
    console.error('Failed to fetch post:', error);
  }
});
</script>
