<script setup>
import { MapPin} from "lucide-vue-next"
import { onMounted, ref } from "vue";
import L from "leaflet";
import ImagesModal from "@/components/ImagesModal.vue";


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
        <div class="">
            <div class="flex items-center justify-between w-full mb-2 text-sm text-gray-400">
                    <p>#2847</p>
                    <p>November 19, 2025</p>
            </div> 
            <!-- header -->
            <div class="">
                <h1 class="text-3xl font-bold ">Broken Streetlight</h1>
                <div class="flex gap-2 mt-2">
                    <h2 class="">Status:</h2>
                    <span
                    class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive"
                    >Urgent</span>
                </div>
            </div> 
            <!-- description -->
            <div class="mt-4">
                <p class="font-semibold">Description:</p>
                <p>Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.</p>
            </div>
            <!-- location -->
            <div class="mt-4 flex  items-start">
                <p class="font-semibold">Location:</p>
                <div class="flex ml-2 text-gray-500 items-center"><MapPin class="size-5 mr-1"/>Main Road, Barangay 1</div>
            </div>
            <!-- map -->
            <div class="mt-4">
                <div class="w-full h-64 z-2" id="map"></div>
            </div>
            <!-- images -->
             <div class="mt-4">
                <div class="flex space-x-4 flex-col">
                    <p class="font-semibold">Images:</p>
                    <div class="size-40 object-cover mt-2 ">
                        <button data-modal-target="default-modal" data-modal-toggle="default-modal" 
                            class=" bg-primary box-border border border-transparent  shadow-xs focus:outline-none  object-cover" type="button">
                                <img src="@/assets/images/Mockup.png" alt="Mockup" class="w-48 cursor-pointer" />
                         </button>
                    </div>
                    <ImagesModal/>
                    
                </div>
                
             </div>
        </div>
    </div>
</template>