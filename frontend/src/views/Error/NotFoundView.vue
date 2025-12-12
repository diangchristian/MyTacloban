<script setup>
import {useAuthStore} from "@/stores/auth"
import { RouterLink } from 'vue-router';


const authStore = useAuthStore()
const returnRoute = authStore.role === 'admin' ? 'admin.dashboard' : 'user.dashboard'


</script>


<template>
<main class="grid h-screen place-items-center  px-6 py-24 sm:py-32 lg:px-8">
  <div class="text-center">
    <p class="text-base font-semibold text-green-500">404</p>
    <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-primary sm:text-7xl">Page not found</h1>
    <p class="mt-6 text-lg font-medium text-pretty text-gray-800 sm:text-xl/8">Sorry, we couldn’t find the page you’re looking for.</p>
    <div class="mt-10 flex items-center justify-center gap-x-6">
      <RouterLink :to="{name: returnRoute}" class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Go back home</RouterLink>
      <RouterLink href="#" class="text-sm font-semibold text-primary">Contact support <span aria-hidden="true">&rarr;</span></RouterLink>
    </div>
  </div>
</main>
</template>re