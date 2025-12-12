<script setup>
import BarangayCard from '@/components/cards/BarangayCard.vue';
import MapLocation from '@/components/location/MapLocation.vue'
import { Input } from '@/components/ui/input';
import { ref, computed, onMounted } from 'vue';
import { MapPin, Users, Home, Map, User, Phone, Mail } from "lucide-vue-next"
import {useBarangayStore} from "@/stores/barangay"
import { storeToRefs } from 'pinia';
import { debounce } from 'lodash';
import Button from '@/components/ui/button/Button.vue';

const barangayStore = useBarangayStore()
const {barangays} = storeToRefs(barangayStore)
const selectedItem = ref(null);
const search = ref('')

onMounted(async () => {
  barangayStore.getAllBarangay()
})


const debouncedSearch = debounce(() => {
  barangayStore.getByName(search.value, '');
}, 500);


const handleSearch = () => {
  debouncedSearch()
}
 

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog'


// // Adi an para search bar
// const filteredBarangays = computed(() => {
//   if (!searchQuery.value) {
//     return barangays.value
//   }
  
//   const query = searchQuery.value.toLowerCase()
//   return barangays.value.filter(barangay => 
//     barangay.name.toLowerCase().includes(query) ||
//     barangay.contactPerson.toLowerCase().includes(query)
//   )
// })

// adi ye kay gin v-for ko nala didto sa baba kay nagkakalat
const infoFields = [
  { icon: MapPin, label: 'Location', key: 'coordinates' },
  { icon: Users, label: 'Population', key: 'population' },
  { icon: Home, label: 'Households', key: 'households' },
  { icon: Map, label: 'Area', key: 'area', suffix: ' km²' },
  { icon: User, label: 'Contact Person', key: 'contact_person' },
  { icon: Phone, label: 'Contact No', key: 'contact_no' },
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
      v-model="search"
      type="text" 
      @keyup="handleSearch"
      placeholder="Search Barangay" 
      class="w-2/3 pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
    />
  </div>

  <!-- Array listing for barangay details & card -->
  <div class="mt-5">
    <div v-if="barangays.length > 0" class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <BarangayCard
        class="pt-0"
        v-for="item in barangays"
        :key="item.id"
        :barangay="item"
        @viewDetails="openDialog"
      />
    </div>
    <div v-else class="text-center py-10 text-gray-500">
      No barangays found matching "{{ search }}"
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
        <Button 
          class="px-4 py-2 font-bold text-white bg-primary hover:bg-primary/20 hover:text-primary rounded cursor-pointer" 
          @click="isDialogOpen = false"
        >
          Close
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>