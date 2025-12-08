<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    stepNumber: number; 
    title: string;
    description: string;
}>();
</script>

<template>
    <div class="border-3 border-primary rounded-lg p-4 flex space-x-4 max-w-sm">
        <div class="max-w-34 flex items-center justify-center">
            <div class="flex items-center justify-center bg-primary text-white size-12 rounded-full text-xl">
                {{ stepNumber }}
            </div>
        </div>
        <div class="">
            <h2 class="text-xs font-semibold">Step {{ stepNumber }}</h2>
            <h1 class=" text-lg md:text-2xl">{{ title }}</h1>
            <p class="mt-2 text-sm">{{ description }}</p>
        </div>
    </div>
</template>