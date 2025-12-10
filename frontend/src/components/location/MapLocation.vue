<script setup>
import { MapPin } from "lucide-vue-next"
import { onMounted, ref, watch } from "vue";
import L from "leaflet";

const props = defineProps({
  report: Object,
  coordinates: {
    type: String,
    default: "11.2404,125.0047" // Default to Tacloban center
  }
})

const map = ref(null);
const marker = ref(null);

// Function to parse coordinates string
const parseCoordinates = (coordString) => {
  const [lat, lng] = coordString.split(',').map(coord => parseFloat(coord.trim()));
  return { lat, lng };
}

// Function to update map location
const updateMapLocation = (coordString) => {
  const { lat, lng } = parseCoordinates(coordString);
  
  if (map.value) {
    // Update map view
    map.value.setView([lat, lng], 14);
    
    // Remove old marker if exists
    if (marker.value) {
      map.value.removeLayer(marker.value);
    }
    
    // Add new marker
    marker.value = L.marker([lat, lng])
      .addTo(map.value)
      .bindPopup("Pinned Location")
      .openPopup();
  }
}

onMounted(() => {
  if (typeof initFlowbite !== 'undefined') {
    initFlowbite();
  }
  
  const { lat, lng } = parseCoordinates(props.coordinates);
  
  // Initialize map
  map.value = L.map("map").setView([lat, lng], 14);

  // Set map tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap contributors",
  }).addTo(map.value);

  // Add initial marker
  marker.value = L.marker([lat, lng])
    .addTo(map.value)
    .bindPopup("Pinned Location")
    .openPopup();
});

// Watch for coordinate changes
watch(() => props.coordinates, (newCoordinates) => {
  if (newCoordinates) {
    updateMapLocation(newCoordinates);
  }
});
</script>

<template>
  <div class="">
    <div class="bg-white p-4 rounded-xl shadow-sm">
      <h3 class="inline-flex items-center gap-1 font-semibold">
        <MapPin class="size-5 text-primary"/>
        Location
      </h3>
      <div class="w-full h-40 sm:h-64 rounded-lg overflow-hidden z-2 mt-2" id="map"></div>
    </div>
  </div>
</template>