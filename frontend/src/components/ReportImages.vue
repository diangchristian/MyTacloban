<script setup>
import { Image } from 'lucide-vue-next';
import ImagesModal from '@/components/ImagesModal.vue';
import { ref } from 'vue';
import mockup1 from '@/assets/images/Mockup.png'
import mockup2 from '@/assets/images/Mockup2.png'
import sample from '@/assets/images/news-sample.png'

const images = [
  mockup1,
  mockup2,
  sample
]

const index = ref(0)


</script>


<template>
    <div class="bg-white   p-4 rounded-xl">
        <h3 class="inline-flex items-center gap-2 mb-2 font-semibold"> <Image class="size-5 text-primary"/> Attached Photos (3)</h3>
        <div class="flex gap-4 flex-wrap">


            <button
              data-modal-target="default-modal"
              data-modal-toggle="default-modal"
              class="size-40 bg-gray rounded-md border border-primary overflow-hidden"
              v-for="(image, i) in images"
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
        <ImagesModal :images="images" :index="index"/>
    </div>
</template>