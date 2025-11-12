<script setup>
import { RouterLink, RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue';
</script>

<template>
  <header>
  
    <div class="wrapper">

      <!-- <nav>
        <RouterLink to="/" class="bg-green-500/20 px-2 py-1 rounded-md mt-12 ml-12">Home</RouterLink>
        <RouterLink to="/login" class="bg-green-500/20 px-2 py-1 rounded-md mt-12 ml-12">Login</RouterLink>
        <RouterLink to="/register" class="bg-green-500/20 px-2 py-1 rounded-md mt-12 ml-12">Register</RouterLink>
      
      </nav> -->

      <NavBar/>
    </div>
  </header>

  <RouterView />
</template>

