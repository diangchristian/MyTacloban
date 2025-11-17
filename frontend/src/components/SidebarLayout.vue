<script setup>
import { computed } from "vue"
import { useRoute } from "vue-router";
import Header from './Header.vue';
import Sidebar from './AppSidebar.vue';
import { SidebarProvider, SidebarTrigger } from "@/components/ui/sidebar"
import AppSidebar from "@/components/AppSidebar.vue"

const route = useRoute();

const pageTitle = computed(() => {
  return route.meta?.title || 'Dashboard';
});


</script>

<template>
    <SidebarProvider>
      <div class="flex h-screen w-screen">
  
    
        <AppSidebar class="w-64 shrink-0" />
  
        <!-- Main area -->
        <main class="flex-1 flex flex-col min-h-0">
          <!-- header fills main -->
          <header class="h-12 w-full flex items-center border-b px-4">
            <SidebarTrigger />
            <h1 class="ml-4">{{ pageTitle }}</h1>
          </header>
  
          <section class="flex-1 overflow-y-auto p-4 min-h-0">
            <slot />
          </section>
        </main>
  
      </div>
    </SidebarProvider>
  </template>
  