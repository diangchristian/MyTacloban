<script setup>
import { MapPin} from "lucide-vue-next"
import { onMounted, ref } from "vue";
import MapLocation from "./MapLocation.vue";
import ImagesModal from "@/components/ImagesModal.vue";
import { initFlowbite } from 'flowbite'
import { defineProps } from "vue";
import mockup1 from '@/assets/images/Mockup.png'
import mockup2 from '@/assets/images/Mockup2.png'
import sample from '@/assets/images/news-sample.png'

const images = [
  mockup1,
  mockup2,
  sample
]

const props = defineProps({
    report: Object
})

onMounted(() => {
  initFlowbite()
})



</script>

<template>
    <div class="h-full">
      <div class="space-y-4">
  
        <!-- ID + Date -->
        <div class="flex flex-wrap items-center justify-between w-full mb-2 text-sm text-gray-400 gap-1">
          <p>{{ report.id }}</p>
          <p>{{ report.date }}</p>
        </div>
  
        <!-- Header -->
        <div>
          <h1 class="text-xl md:text-3xl font-bold break-words">{{ report.title }}</h1>
  
          <div class="flex flex-wrap gap-2 mt-2 text-sm">
            <h2>Category:</h2>
            <span class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive">
              {{ report.category_name }}
            </span>
          </div>
        </div>
  
        <!-- Description -->
        <div>
          <p class="font-semibold">Description:</p>
          <p class="text-gray-700 break-words">{{ report.description }}</p>
        </div>
  
        <!-- Location -->
        <!-- <div>
          <p class="font-semibold">Location:</p>
          <div class="flex items-center mt-1 ml-0 text-gray-500">
            <MapPin class="size-5 mr-1" />
            <p class="break-words">{{ report.location }}</p>
          </div>
        </div>
   -->
        <!-- Map -->
        <div>
          <MapLocation :coordinates="report.coordinates"/>
        </div>
  
        <!-- Images -->
        <div>
          <p class="font-semibold mb-2">Images:</p>
  
          <div class="flex flex-wrap gap-4">
            <button
              data-modal-target="default-modal"
              data-modal-toggle="default-modal"
              class="bg-primary border border-transparent shadow-xs focus:outline-none rounded-lg overflow-hidden"
            >
              <img
                :src="report.images[0]"
                alt="Mockup"
                class="w-32 sm:w-48 h-auto object-cover cursor-pointer"
              />
            </button>
          </div>
  
          <ImagesModal :images="report.images"  />
        </div>
  
      </div>
    </div>
  </template>
  