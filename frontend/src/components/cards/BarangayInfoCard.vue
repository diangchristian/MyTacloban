<script setup>
    import { defineProps } from 'vue'
    
    const props = defineProps({
      number: String,
      label: String,
      icon: Object,
      iconColor: String,

      barangay: {
        type: String,
        default: ""
      }

    });
</script>

<template>
  <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 flex items-center justify-between">
    <div>
      <div class="text-3xl font-bold text-gray-800">{{ number }}</div>
      <div class="text-gray-600 text-sm mt-1">{{ label }}</div>
    </div>
    <div :class="`${iconColor} p-3 rounded-lg`">
      <component :is="icon" class="w-8 h-8 text-white" />
    </div>
  </div>
</template>
