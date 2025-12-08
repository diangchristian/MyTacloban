<script setup>
    defineProps({
        cards: { type: Array, required: true }
    })
</script>



<template>
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
        v-for="card in cards"
        :key="card.title"
        class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 flex items-center justify-between hover:shadow-xl"
        >
        <div>
            <p class="text-sm font-medium text-gray-500">{{ card.title }}</p>
            <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ card.value }}</p>
        </div>
        <div :class="[card.color, card.bg]" class="p-3 rounded-full">
            <component :is="card.icon" class="size-6" />
        </div>
        </div>
    </section>
</template>

