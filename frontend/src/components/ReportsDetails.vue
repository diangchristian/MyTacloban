<script setup>
import { MapPin} from "lucide-vue-next"
import { onMounted, ref } from "vue";
import L from "leaflet";
import ImagesModal from "@/components/ImagesModal.vue";
import { initFlowbite } from 'flowbite'
import { defineProps } from "vue";


const props = defineProps({
    report: Object
})


onMounted(() => {
  initFlowbite()
})

const map = ref(null);

onMounted(() => {
  map.value = L.map("map").setView([11.2404, 125.0047], 14); // Tacloban center

  // Set map tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap contributors",
  }).addTo(map.value);

  // Add pin
  L.marker([11.2404, 125.0047])
    .addTo(map.value)
    .bindPopup("Pinned Location")
    .openPopup();
});


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
              {{ report.category }}
            </span>
          </div>
        </div>
  
        <!-- Description -->
        <div>
          <p class="font-semibold">Description:</p>
          <p class="text-gray-700 break-words">{{ report.description }}</p>
        </div>
  
        <!-- Location -->
        <div>
          <p class="font-semibold">Location:</p>
          <div class="flex items-center mt-1 ml-0 text-gray-500">
            <MapPin class="size-5 mr-1" />
            <p class="break-words">{{ report.location }}</p>
          </div>
        </div>
  
        <!-- Map -->
        <div>
          <div class="w-full h-40 sm:h-64 rounded-lg overflow-hidden z-2" id="map"></div>
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
                src="@/assets/images/Mockup.png"
                alt="Mockup"
                class="w-32 sm:w-48 h-auto object-cover cursor-pointer"
              />
            </button>
          </div>
  
          <ImagesModal />
        </div>
  
      </div>
    </div>
  </template>
  