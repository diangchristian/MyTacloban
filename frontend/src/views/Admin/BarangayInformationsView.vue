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

import StatsCard from '@/components/cards/BarangayInfoCard.vue'
import BarangayCard from '@/components/cards/BarangaySelCard.vue'
import BarangayTable from '@/components/BarangayTable.vue'

// reactive state
const searchQuery = ref('')
const sortBy = ref('A - Z')
const viewMode = ref('grid')

const barangays = ref([
  {
    id: 1,
    name: 'Nula-Tula',
    captain: 'Aron Jacob',
    phone: '+1234567890',
    email: 'barangay1@example.com'
  },
  {
    id: 2,
    name: 'Caibanan',
    captain: 'Chris Chan The Ang',
    phone: '+1234567891',
    email: 'barangay2@example.com'
  }
])
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
                <div class="text-xl font-bold text-gray-800">ALL BARANGAYS</div>
              </div>
            </div>
            <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors">
              <span>+</span>
              SELECT BARANGAY
            </button>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-3 gap-6 mb-6">
            <StatsCard number="248" label="Total Officials" :icon="Users" iconColor="bg-blue-500" />
            <StatsCard number="12" label="Barangays" :icon="Building2" iconColor="bg-green-500" />
            <StatsCard number="3000" label="Population" :icon="UsersRound" iconColor="bg-purple-500" />
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
              <div class="relative">
                <select 
                  v-model="sortBy"
                  class="appearance-none px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white cursor-pointer"
                >
                  <option>A - Z</option>
                  <option>Z - A</option>
                </select>
                <ChevronDown class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
              <div class="flex gap-2">
                <button 
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
                <button 
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
          <div v-if="viewMode === 'grid'" class="grid grid-cols-3 gap-6">
            <BarangayCard 
              v-for="barangay in barangays" 
              :key="barangay.id"
              :name="barangay.name"
              :captain="barangay.captain"
              :phone="barangay.phone"
              :email="barangay.email"
            />
          </div>
          <BarangayTable v-else :barangays="barangays" />
        </div>
      </div>
    </div>
  </div>
</template>