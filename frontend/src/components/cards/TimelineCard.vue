<script setup>


const props =  defineProps({
  timelines: {
    type: Array
  }
})



</script>

<template>
    <ol class="timeline relative pl-6 max-h-96 overflow-y-auto scrollable" v-for="timeline in props.timelines" :key="timeline.id">
      <li class="relative mb-6 pl-6 border-s">
        <!-- dot: placed on the timeline line (uses translateX to center) -->
        <div class="absolute w-3 h-3 bg-primary rounded-full mt-1.5 -start-1.5 border border-buffer"></div>
        <h6 class="text-sm font-semibold text-heading my-2">{{timeline.status}}</h6>
        <p class="text-xs font-normal text-gray-500">{{timeline.description}}</p>
        <time class="text-xs font-normal leading-none text-gray-500">{{ timeline.created_at }}</time>
      </li>
      <!-- more items -->
    </ol>
  </template>


<style scoped>

/* *{
  border: 1px solid red
} */

.scrollable::-webkit-scrollbar {
  width: 2px;
}
.scrollable::-webkit-scrollbar-thumb {
  background: #58F09F;
  border-radius: 10px;
}
.scrollable::-webkit-scrollbar-track {
  background: #f5f5f5;
}
</style>