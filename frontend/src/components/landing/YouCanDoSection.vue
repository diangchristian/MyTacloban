<script setup >
import FeastruesCard from '@/components/landing/FeaturesCard.vue'

</script>

<template>
  <div class="overflow-hidden py-16  ">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 ">
      <h1
        class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl text-center">
        What You Can Do With <span class="text-primary">MyTacloban</span> 
      </h1>

      <div class="  mx-auto grid max-w-3xl grid-cols-1 gap-x-4 gap-y-8 sm:gap-y-8 sm:space-x-4 lg:mx-0 lg:max-w-none md:grid-cols-2 lg:grid-cols-4 mt-8 rounded-lg  p-8 md:px-8 md:py-12 shadow-lg">
        <FeastruesCard/>
      </div>
    </div>
  </div>
</template>
