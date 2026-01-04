<script setup lang="ts">


defineProps<{
    stepNumber: number; 
    title: string;
    description: string;
}>();
</script>

<template>
    <div class="border-3 border-primary rounded-lg p-4 flex space-x-4 max-w-sm">
        <div class="max-w-34 flex items-center justify-center">
            <div class="flex font-bold items-center justify-center bg-primary text-white size-12 rounded-full text-xl">
                {{ stepNumber }}
            </div>
        </div>
        <div class="">
            <h2 class="text-xs font-semibold">Step {{ stepNumber }}</h2>
            <h1 class=" text-lg md:text-md mt-2 font-semibold">{{ title }}</h1>
            <p class="text-sm text-gray-600">{{ description }}</p>
        </div>
    </div>
</template>