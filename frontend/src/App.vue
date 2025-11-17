<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useRoute } from 'vue-router';
import DefaultLayout from './components/DefaultLayout.vue';
import SidebarLayout from './components/SidebarLayout.vue';

const route = useRoute();


const layouts = {
  default: DefaultLayout,
  sidebar: SidebarLayout
}


</script>

<template>
  <component :is="layouts[route.meta.layout || 'default']">
    <RouterView />
  </component>
</template>

