<script setup>
import { Image } from 'lucide-vue-next';
import ImagesModal from '@/components/others/ImagesModal.vue';
import { ref } from 'vue';

import sample from '@/assets/images/news-sample.png'

const props = defineProps({
  images:{
    type: Array
  }
})

const index = ref(0)
console.log(props.images.length)
</script>


<template>
    <div class="bg-white   p-4 rounded-xl">
        <h3 class="inline-flex items-center gap-2 mb-2 font-semibold"> <Image class="size-5 text-primary"/> Attached Photos ({{ props.images.length }})</h3>
        <div class="" >
            <div class="flex gap-4 flex-wrap" v-if="props.images.length > 0">
              <button
              data-modal-target="default-modal"
              data-modal-toggle="default-modal"
              class="size-40 bg-gray rounded-md border border-primary overflow-hidden"
              v-for="(image, i) in props.images"
              :key="i"
              @click="index = i"
              >
              <img
                :src='image'
                alt="Mockup"
                class="w-32 sm:w-48 h-auto object-cover cursor-pointer unselectable-image"
              />
            </button>
            </div>
            <div class="" v-else>
              <h1>No images attached</h1>
            </div>
           
        </div>
       
        <ImagesModal :images="props.images" :index="index"/>
    </div>
</template>