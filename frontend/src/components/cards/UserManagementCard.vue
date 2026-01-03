<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  title: String,
  value: Number,
  icon: Object,
  color: String,
  bgColor: String
});
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-sm text-gray-600 font-medium mb-2">{{ title }}</p>
        <p :class="`text-3xl font-semibold`">{{ value }}</p>
      </div>
      <div :class="`${bgColor} p-3 rounded-lg`">
        <component :is="icon" :class="`w-6 h-6 ${color}`" />
      </div>
    </div>
  </div>
</template>