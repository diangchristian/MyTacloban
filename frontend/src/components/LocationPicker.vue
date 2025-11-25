<script setup>
import { ref, onMounted } from "vue";
import L from "leaflet";

const props = defineProps({
  modelValue: {
    type: String,
    default: ""
  }
});

const emit = defineEmits(["update:modelValue"]);

const latlng = ref({ lat: 11.2404, lng: 125.0047 });
const latlngString = ref(`${latlng.value.lat},${latlng.value.lng}`);

let map, marker;

function updateParent() {
  const val = `${latlng.value.lat},${latlng.value.lng}`;
  latlngString.value = val;
  emit("update:modelValue", val); // 🔥 send to parent form
}

onMounted(() => {
  map = L.map("map").setView([latlng.value.lat, latlng.value.lng], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

  marker = L.marker([latlng.value.lat, latlng.value.lng], { draggable: true }).addTo(map);

  marker.on("dragend", () => {
    const pos = marker.getLatLng();
    latlng.value = { lat: pos.lat, lng: pos.lng };
    updateParent();
  });

  map.on("click", (e) => {
    marker.setLatLng(e.latlng);
    latlng.value = { lat: e.latlng.lat, lng: e.latlng.lng };
    updateParent();
  });
});
</script>

<template>
  <div class="bg-white h-full p-4">
    <label class="block text-sm font-medium">Mark Location</label>
    <div id="map" class="w-full h-64 rounded-md mb-4 mt-2"></div>

    <!-- Hidden for now, but keeps v-model in sync -->
    <input type="text" v-model="latlngString" class="hidden" />
  </div>
</template>
