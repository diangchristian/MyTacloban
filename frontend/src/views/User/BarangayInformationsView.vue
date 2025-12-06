<script setup >
import BarangayCard from '@/components/cards/BarangayCard.vue';
import { Input } from '@/components/ui/input';
import { ref } from 'vue';
import img1 from '@/assets/images/news-sample.png';
import img2 from '@/assets/images/news-sample.png';
import img3 from '@/assets/images/news-sample.png';
import img4 from '@/assets/images/news-sample.png';


const selectedItem = ref(null);

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog'



const barangays = ref([
  {
    id: 1,
    name: "Nula-Tula (Brgy. 3 & 3A)",
    location: "Tacloban City, Leyte",
    population: 2663,
    households: 2663,
    area: 0.27,
    contactPerson: "John Doe I",
    contactNumber: "+639062688526",
    email: "johndoe@email.com",
    images: [
      img1,
      img2,
      img3,
      img4,
    ]
  },
  {
    id: 2,
    name: "Caibanan",
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
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
    location: "Tacloban City, Leyte",
    population: 1500,
    households: 800,
    area: 0.15,
    contactPerson: "Archie The King",
    contactNumber: "+639123456789",
    email: "maria@email.com"
  },
])

// this is for global dialogs
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
        <Input type="text" placeholder="Search Barangay" class="w-2/3 pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
    </div>

    <!-- Array listing for barangay details & card -->
    <div class="mt-5">
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <BarangayCard
                v-for="item in barangays"
                :key="item.id"
                :barangay="item"
                @viewDetails="openDialog"
            />
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

        <div class="space-y-1">
          <p><strong>Location:</strong> {{ selectedBarangay?.location }}</p>
          <p><strong>Population:</strong> {{ selectedBarangay?.population }}</p>
          <p><strong>Households:</strong> {{ selectedBarangay?.households }}</p>
          <p><strong>Area:</strong> {{ selectedBarangay?.area }} km²</p>
          <p><strong>Contact Person:</strong> {{ selectedBarangay?.contactPerson }}</p>
          <p><strong>Contact Number:</strong> {{ selectedBarangay?.contactNumber }}</p>
          <p><strong>Email:</strong> {{ selectedBarangay?.email }}</p>
        </div>

        <div v-if="selectedBarangay?.images?.length" class="grid grid-cols-2 gap-3 mt-4">
        <img
            v-for="(img, idx) in selectedBarangay.images"
            :key="idx"
            :src="img"
            class="w-full h-32 object-cover rounded-lg shadow"
        />
        </div>

        <div v-else class="text-center text-gray-500 text-sm mt-4">
        No images available for this barangay.
        </div>

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
