<script setup>
import { useAuthStore } from "@/stores/auth"
import { RouterLink } from 'vue-router'
import { computed } from 'vue'

const authStore = useAuthStore()

const returnRoute = computed(() =>
    authStore.user.role === 'Admin' ? 'admin.dashboard' : 'user.dashboard'
)
</script>

<template>
<main class="grid h-screen place-items-center px-6 py-24 sm:py-32 lg:px-8">
    <div class="text-center">
    <p class="text-base font-semibold text-green-500">401</p>
    <h1 class="mt-4 text-5xl font-semibold tracking-tight text-primary sm:text-7xl">
        Unauthorized
    </h1>
    <p class="mt-6 text-lg font-medium text-gray-800">
        You are not authorized to access this page
    </p>

    <div class="mt-10 flex items-center justify-center gap-x-6">
        <RouterLink
        :to="{ name: returnRoute }"
        class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
        >
        Go back home
        </RouterLink>
    </div>
    </div>
</main>
</template>
