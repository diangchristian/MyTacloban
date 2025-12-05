<script setup>
import { ref } from 'vue'
import { 
  Users, 
  Building2, 
  UsersRound, 
  Search, 
  Grid3x3, 
  List, 
  ChevronDown 
} from 'lucide-vue-next'

import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectGroup,
  SelectLabel,
  SelectItem,
} from "@/components/ui/select";

import StatsCard from '@/components/cards/BarangayInfoCard.vue'
import BarangayCard from '@/components/cards/BarangaySelCard.vue'
import BarangayTable from '@/components/BarangayTable.vue'

// reactive state
const searchQuery = ref('')
const viewMode = ref('grid')

import { computed } from 'vue'

const selectedPopulation = computed(() => {
  const found = barangaysInfo.value.find(b => b.name === barangay.value)
  return found ? found.population : 0
})

const selectedBarangays = computed(() => {
  const found = barangaysInfo.value.find(b => b.name === barangay.value)
  return found ? found.houses : 0
})

const selectedOfficials = computed(() => {
  const found = barangaysInfo.value.find(b => b.name === barangay.value)
  return found ? found.totalOfficials : 0
})

const barangaysInfo = ref([
  {
    id: 1,
    name: 'Nula-Tula',
    captain: 'Aron Jacob',
    phone: '+1234567890',
    email: 'barangay1@example.com',
    population: '200',
    houses: '35',
    totalOfficials: '12'
  },
  {
    id: 2,
    name: 'Caibanan',
    captain: 'Chris Chan The Ang',
    phone: '+1234567891',
    email: 'barangay2@example.com',
    population: '152',
    houses: '48',
    totalOfficials: '8'
  }
])

const barangay = ref("");

</script>


<template>
  <div class="min-h-screen bg-gray-50 flex flex-items-start">
    <!-- Main Content -->
    <div class="flex-1">
      <div class="p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Header -->
          <div class="bg-green-50 rounded-lg p-6 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <Building2 class="w-8 h-8 text-green-600" />
              <div>
                <div class="text-sm text-green-600 font-medium">SELECTED BARANGAY</div>
                <div class="text-xl font-bold text-gray-800">{{ barangay.toUpperCase() || "NONE"}}</div>
              </div>
            </div>
            <Select v-model="barangay">
                <SelectTrigger
                  class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors cursor-pointer"
                >
                  <SelectValue class="text-white" placeholder="SELECT BARANGAY" />
                </SelectTrigger>

                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Barangays</SelectLabel>

                    <SelectItem class="cursor-pointer"
                      v-for="b in barangaysInfo"
                      :key="b.id"
                      :value="String(b.name)"
                    >
                      {{ b.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
          </div>

          <!-- Stats Cards -->
          <div class="grid sm:grid-cols 1 md:grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <StatsCard 
              :number="selectedOfficials" 
              label="Total Officials" 
              :icon="Users" 
              iconColor="bg-blue-500" 
              :barangay="barangay" 
              />
            
            <StatsCard 

              :number="selectedBarangays" 
              label="Households" 
              :icon="Building2" 
              iconColor="bg-green-500" 
              :barangay="barangay" 
            />

            <StatsCard 
              :number="selectedPopulation" 
              label="Population" 
              :icon="UsersRound" 
              iconColor="bg-purple-500" 
              :barangay="barangay" 
              />

          </div>

          <!-- Search and Filters -->
          <div class="bg-white rounded-lg p-4 mb-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
              <div class="flex-1 relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Search officials by name or email..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                />
              </div>
              <div class="flex gap-2">
                <button class="cursor-pointer"
                  @click="viewMode = 'grid'"
                  :class="[
                    'p-2 rounded transition-colors',
                    viewMode === 'grid' 
                      ? 'bg-green-100 text-green-600' 
                      : 'text-gray-400 hover:bg-gray-100 hover:text-gray-600'
                  ]"
                >
                  <Grid3x3 class="w-5 h-5" />
                </button>
                <button class="cursor-pointer"
                  @click="viewMode = 'list'"
                  :class="[
                    'p-2 rounded transition-colors',
                    viewMode === 'list' 
                      ? 'bg-green-100 text-green-600' 
                      : 'text-gray-400 hover:bg-gray-100 hover:text-gray-600'
                  ]"
                >
                  <List class="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>

          <!-- Barangay List -->
          <!-- Barangay List -->
          <div v-if="viewMode === 'grid'" class="grid grid-cols-3 gap-6">
            <BarangayCard 
              v-for="barangay in barangaysInfo" 
              :key="barangay.id"
              :name="barangay.name"
              :captain="barangay.captain"
              :phone="barangay.phone"
              :email="barangay.email"
            />
          </div>
          <BarangayTable v-else :barangays="barangaysInfo" />
        </div>
      </div>
    </div>
  </div>
</template>