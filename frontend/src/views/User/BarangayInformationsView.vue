<script setup>
import BarangayCard from '@/components/cards/BarangayCard.vue';
import MapLocation from '@/components/MapLocation.vue'
import { Input } from '@/components/ui/input';
import { ref, computed } from 'vue';
import { MapPin, Users, Home, Map, User, Phone, Mail } from "lucide-vue-next"

const selectedItem = ref(null);

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog'

// hilaba ini parehas it ak, hardcoded sample data
const barangays = ref([
  {
    id: 1,
    name: "Nula-Tula (Brgy. 3 & 3A)",
    coordinates: "11.2489,124.9706",
    population: 2663,
    households: 2663,
    area: 0.27,
    contactPerson: "John Doe I",
    contactNumber: "+639062688526",
    email: "johndoe@email.com",
  },
  {
    id: 2,
    name: "Caibanan",
    coordinates: "11.1939,124.9923",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Chris The Ang",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 3,
    name: "Sagkahan",
    coordinates: "11.2160,125.0026",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Gcob The Great",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 4,
    name: "Brgy. V&G",
    coordinates: "11.2404,125.0047",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Yllanna The Grace",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 5,
    name: "Brgy. San Jose",
    coordinates: "11.2404,125.0047",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Archie The King",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 6,
    name: "Brgy. San Jose",
    coordinates: "11.2404,125.0047",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Archie The King",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 7,
    name: "Brgy. San Jose",
    coordinates: "11.2404,125.0047",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Archie The King",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
  {
    id: 8,
    name: "Brgy. San Jose",
    coordinates: "11.2404,125.0047",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Archie The King",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
])

// adi an pan search query
const searchQuery = ref('')

// Adi an para search bar
const filteredBarangays = computed(() => {
  if (!searchQuery.value) {
    return barangays.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return barangays.value.filter(barangay => 
    barangay.name.toLowerCase().includes(query) ||
    barangay.contactPerson.toLowerCase().includes(query)
  )
})

// adi ye kay gin v-for ko nala didto sa baba kay nagkakalat
const infoFields = [
  { icon: MapPin, label: 'Location', key: 'coordinates' },
  { icon: Users, label: 'Population', key: 'population' },
  { icon: Home, label: 'Households', key: 'households' },
  { icon: Map, label: 'Area', key: 'area', suffix: ' km²' },
  { icon: User, label: 'Contact Person', key: 'contactPerson' },
  { icon: Phone, label: 'Contact No', key: 'contactNumber' },
  { icon: Mail, label: 'Email', key: 'email', span: true }
]

// adi hera bagan trigger/watcher para han pag abri dialog tas pagkuha kun ano na brgy
const selectedBarangay = ref(null)
const isDialogOpen = ref(false)

function openDialog(item) {
  selectedBarangay.value = item
  isDialogOpen.value = true
}
</script>

<template>
  <!-- Search Area -->
  <div class="relative flex items-center border-b-2 border-b-gray/50 pb-4">
    <svg class="absolute left-3 h-5 w-5 text-gray-400 focus-within:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
    <Input 
      v-model="searchQuery"
      type="text" 
      placeholder="Search Barangay" 
      class="w-2/3 pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
    />
  </div>

  <!-- Array listing for barangay details & card -->
  <div class="mt-5">
    <div v-if="filteredBarangays.length > 0" class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <BarangayCard
        class="pt-0"
        v-for="item in filteredBarangays"
        :key="item.id"
        :barangay="item"
        @viewDetails="openDialog"
      />
    </div>
    <div v-else class="text-center py-10 text-gray-500">
      No barangays found matching "{{ searchQuery }}"
    </div>
  </div>

  <!-- ---- GLOBAL DIALOG HERE ---- -->
  <Dialog v-model:open="isDialogOpen">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ selectedBarangay?.name }}</DialogTitle>
        <DialogDescription>
          Full details of the selected barangay.
        </DialogDescription>
      </DialogHeader>

      <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
        <div 
          v-for="field in infoFields" 
          :key="field.key"
          class="flex items-center gap-2"
          :class="{ 'sm:col-span-1 md:col-span-2': field.span }"
        >
          <component :is="field.icon" class="size-4 text-primary flex-shrink-0" />
          <span class="text-sm font-semibold whitespace-nowrap">{{ field.label }}:</span> 
          <span class="text-sm" :class="field.span ? '' : 'truncate'">{{ selectedBarangay?.[field.key] }}{{ field.suffix || '' }}</span>
        </div>
      </div>

      <MapLocation :coordinates="selectedBarangay.coordinates" />

      <DialogFooter>
        <button 
          class="px-4 py-2 font-bold text-black bg-primary hover:bg-primary/20 hover:text-primary rounded cursor-pointer" 
          @click="isDialogOpen = false"
        >
          Close
        </button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>