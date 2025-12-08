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
import TimelineCard from "./cards/TimelineCard.vue";
import {useSubmitReport} from "@/stores/submitReport"
import { Skeleton } from "@/components/ui/skeleton";
import { storeToRefs } from "pinia";
import { MessageSquare } from 'lucide-vue-next';


const submitReportStore = useSubmitReport()
const {reportDetails, isLoading} = storeToRefs(submitReportStore)


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
       <div class="grid grid-cols-1 lg:grid-cols-2">
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
        
        <div class="bg-white p-4 rounded-xl shadow-sm">
              <div class="flex justify-between items-center mb-4">
                <h3 class="inline-flex items-center gap-2">
                  <MessageSquare class="size-5 text-primary" />
                  Activity Timeline
                </h3>
              </div>
              
              <div v-if="!isLoading">
                <Skeleton v-for="i in 3" :key="i" class="h-12 w-full rounded-md mb-2 bg-gray-200" />
              </div>
              <TimelineCard v-if="report" :timelines="report.timelines" />
        </div>
       </div>
      </div>
    </div>
  </template>
  