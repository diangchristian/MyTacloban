<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useBarangayStore } from "@/stores/barangay";
import StatsCard from '@/components/cards/BarangayInfoCard.vue';
import BarangayCard from '@/components/cards/BarangaySelCard.vue';
import BarangayTable from '@/components/BarangayTable.vue';

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

// Initialize store
const barangayStore = useBarangayStore()

// Reactive state
const searchQuery = ref('')
const viewMode = ref('grid')
const barangay = ref("")

// Fetch barangays on component mount
onMounted(async () => {
  await barangayStore.getAllBarangay()
})

// Watch search query and fetch filtered barangays
watch(searchQuery, async (newValue) => {
  if (newValue) {
    await barangayStore.getByName(newValue)
  } else {
    await barangayStore.getAllBarangay()
  }
})

// Get barangays from store
const barangaysInfo = computed(() => barangayStore.barangays || [])

// Helper function to format numbers with commas
const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US')
}

// Computed properties for selected barangay stats or totals
const selectedPopulation = computed(() => {
  if (!barangay.value) {
    // Return total population of all barangays - ensure numbers are parsed
    const total = barangaysInfo.value.reduce((total, b) => {
      return total + (Number(b.population) || 0)
    }, 0)
    return formatNumber(total)
  }
  const found = barangaysInfo.value.find(b => b.name === barangay.value)
  return found ? formatNumber(found.population) : 0
})

const selectedHouseholds = computed(() => {
  if (!barangay.value) {
    // Return total households of all barangays - ensure numbers are parsed
    const total = barangaysInfo.value.reduce((total, b) => {
      return total + (Number(b.households) || 0)
    }, 0)
    return formatNumber(total)
  }
  const found = barangaysInfo.value.find(b => b.name === barangay.value)
  return found ? formatNumber(found.households) : 0
})

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
                  <SelectItem 
                    class="cursor-pointer"
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
          <div class="grid sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <StatsCard 
              :number="selectedHouseholds" 
              :label="barangay ? 'Households' : 'Total Households'" 
              :icon="Building2" 
              iconColor="bg-green-500" 
              :barangay="barangay" 
            />

            <StatsCard 
              :number="selectedPopulation" 
              :label="barangay ? 'Population' : 'Total Population'" 
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
                  placeholder="Search barangays by name..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                />
              </div>
              <div class="flex gap-2">
                <button 
                  class="cursor-pointer"
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
                  class="cursor-pointer"
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

          <!-- Loading State -->
          <div v-if="barangayStore.isLoading" class="text-center py-12">
            <div class="text-gray-500">Loading barangays...</div>
          </div>

          <!-- Error State -->
          <div v-else-if="Object.keys(barangayStore.errors).length > 0" class="text-center py-12">
            <div class="text-red-500">Error loading barangays</div>
          </div>

          <!-- Barangay List -->
          <div v-else-if="barangaysInfo.length > 0">
            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <div class="text-gray-500">No barangays found</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>