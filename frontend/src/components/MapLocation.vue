<script setup>
import { MapPin} from "lucide-vue-next"
import { onMounted, ref } from "vue";
import L from "leaflet";


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
    <div class="">
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <h3 class="inline-flex items-center gap-1 font-semibold"><MapPin class="size-5 text-primary"/>   Location</h3>
          <div class="w-full h-40 sm:h-64 rounded-lg overflow-hidden z-2 mt-2" id="map"></div>
        </div>
    </div>
  </template>
  