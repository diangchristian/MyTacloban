<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useRoute } from 'vue-router';
import DefaultLayout from './components/layouts/DefaultLayout.vue';
import SidebarLayout from './components/layouts/SidebarLayout.vue';
import NoLayoutPage from './components/layouts/NoLayoutPage.vue';
import 'vue-sonner/style.css'
import { Toaster } from '@/components/ui/sonner'
const route = useRoute();


const layouts = {
  default: DefaultLayout,
  sidebar: SidebarLayout,
  noLayout: NoLayoutPage,
}


</script>

<template>
  <component :is="layouts[route.meta.layout || 'default']">
    <RouterView />  
  </component>
  <Toaster
    position="top-right"        
    richColors                  
    toastOptions="{ duration: 5000 }" 
  />
</template>

