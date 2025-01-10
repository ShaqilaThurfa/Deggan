<script setup>
import NavBar from './components/NavBar.vue'
</script>


<template>
  <div id="app">
   
    <NavBar v-if="$route.meta.showNavbar" />


    <router-view />

    
  </div>
</template>
