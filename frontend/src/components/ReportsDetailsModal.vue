<script setup>
import ReportsDetails from "@/components/ReportsDetails.vue";
const props = defineProps({
  report: Object
})
const emit = defineEmits(["close"])

console.log('clicked')
</script>

<template>
  <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-4 my-4 h-full w-full max-w-2xl relative">
      
      <!-- close -->
      <button 
        class="absolute top-2 right-2 text-xl"
        @click="emit('close')"
      >
        ✕
      </button>

     <div class="my-4">
      <ReportsDetails :report="report"/>
     </div>

    </div>
  </div>
</template>
