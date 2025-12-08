<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useBarangayStore } from "@/stores/barangay";
import { useBarangayOfficialStore } from "@/stores/barangayOfficial";
import StatsCard from '@/components/cards/BarangayInfoCard.vue';
import BarangayCard from '@/components/cards/BarangaySelCard.vue';
import BarangayTable from '@/components/BarangayTable.vue';

import { 
  Users, 
  Building2, 
  UsersRound, 
  Search, 
  ChevronDown, 
  Computer
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

// Stores
const barangayStore = useBarangayStore()
const barangayOfficialStore = useBarangayOfficialStore()

// State
const searchQuery = ref('')
const viewMode = ref('grid')
const selectedBarangayName = ref("all")   // Default to ALL
const isInitialLoad = ref(true)

// Load initial data
onMounted(async () => {
  try {
    await Promise.all([
      barangayStore.getAllBarangay(),
      barangayOfficialStore.getAllOfficials()
    ])
  } catch (error) {
    console.error('Error loading initial data:', error)
  } finally {
    isInitialLoad.value = false
  }
})

/* ---------------------------------------
   SEARCH SUGGESTIONS (LOCAL ONLY)
---------------------------------------- */
const barangaysInfo = computed(() => barangayStore.barangays || []);

const searchSuggestions = computed(() => {
  if (!searchQuery.value.trim()) return []
  return barangaysInfo.value.filter(b =>
    b.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const handleSelectSuggestion = (name) => {
  selectedBarangayName.value = name       // update <Select>
  searchQuery.value = ''                  // clear search box
}

/* ---------------------------------------
   FILTERED LIST FOR THE <SELECT>
---------------------------------------- */
const filteredBarangays = computed(() => {
  if (!searchQuery.value) return barangaysInfo.value
  return barangaysInfo.value.filter(b =>
    b.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

/* ---------------------------------------
   OFFICIALS / STATISTICS
---------------------------------------- */
const getBarangayName = (barangayId) => {
  const barangay = barangaysInfo.value.find(b => b.id === barangayId)
  return barangay ? barangay.name : 'N/A'
}

const officialsInfo = computed(() => {
  const all = barangayOfficialStore.officials || []

  if (selectedBarangayName.value === "all") {
    return all
  }

  const selected = barangaysInfo.value.find(b => b.name === selectedBarangayName.value)
  if (!selected) return []

  return all.filter(o => o.barangay_id === selected.id)
})

const selectedBarangay = computed(() => {
  if (selectedBarangayName.value === "all") return null
  return barangaysInfo.value.find(b => b.name === selectedBarangayName.value)
})

const formatNumber = (num) => Number(num).toLocaleString('en-US')

const selectedPopulation = computed(() => {
  if (selectedBarangayName.value === "all") {
    return barangaysInfo.value.reduce((sum, b) => sum + Number(b.population || 0), 0)
  }
  return selectedBarangay.value ? Number(selectedBarangay.value.population || 0) : 0
})

const selectedHouseholds = computed(() => {
  if (selectedBarangayName.value === "all") {
    return barangaysInfo.value.reduce((sum, b) => sum + Number(b.households || 0), 0)
  }
  return selectedBarangay.value ? Number(selectedBarangay.value.households || 0) : 0
})

const selectedOfficials = computed(() => officialsInfo.value.length)

const barangayCaptain = computed(() => {
  if (!selectedBarangay.value) return 'N/A'
  const captain = officialsInfo.value.find(
    o => o.barangay_id === selectedBarangay.value.id && o.position === "Captain"
  )
  return captain ? captain.official_name : "N/A"
})

const nonCaptainOfficials = computed(() => {
  if (!selectedBarangay.value) return []
  return officialsInfo.value.filter(
    o => o.barangay_id === selectedBarangay.value.id && o.position !== "Captain"
  )
})

/* ---------------------------------------
   LOADING + ERROR HANDLING
---------------------------------------- */
const isLoading = computed(() => barangayStore.isLoading || barangayOfficialStore.isLoading)

const hasErrors = computed(() =>
  Object.keys(barangayStore.errors || {}).length > 0 ||
  Object.keys(barangayOfficialStore.errors || {}).length > 0
)

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
              <div class="text-xl font-bold text-gray-800">
                {{ selectedBarangayName.toUpperCase() || "ALL BARANGAYS" }}
              </div>
            </div>
          </div>

          <!-- SELECT BARANGAY -->
          <Select v-model:model-value="selectedBarangayName"
                  :key="selectedBarangayName"
            >
            <SelectTrigger
              class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors cursor-pointer min-w-[200px]"
            >
              <SelectValue class="text-white" placeholder="SELECT BARANGAY" />
            </SelectTrigger>

            <SelectContent>
              <SelectGroup>
                <SelectLabel>Barangays</SelectLabel>

                <SelectItem value="all" class="cursor-pointer">
                  ALL BARANGAYS
                </SelectItem>

                <!-- filteredBarangays instead of barangaysInfo -->
                <SelectItem
                  v-for="b in filteredBarangays"
                  :key="b.id"
                  :value="b.name"
                  class="cursor-pointer"
                >
                  {{ b.name }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 shadow-sm border border-gray-200">
          <div class="flex items-center gap-4">

            <!-- SEARCH BOX -->
            <div class="flex-1 relative">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Search barangays by name..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />

              <!-- Suggestions dropdown -->
              <div 
                v-if="searchSuggestions.length > 0" 
                class="absolute mt-1 w-full bg-white border rounded shadow z-50"
              >
                <div 
                  v-for="b in searchSuggestions" 
                  :key="b.id"
                  @click="handleSelectSuggestion(b.name)"
                  class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                >
                  {{ b.name }}
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

          <StatsCard 
            :number="selectedOfficials" 
            :label="selectedBarangayName ? 'Officials' : 'Total Officials'" 
            :icon="Users" 
            iconColor="bg-blue-500" 
            :barangay="selectedBarangayName" 
          />

          <StatsCard 
            :number="formatNumber(selectedPopulation)" 
            :label="selectedBarangayName ? 'Population' : 'Total Population'" 
            :icon="UsersRound" 
            iconColor="bg-purple-500" 
            :barangay="selectedBarangayName" 
          />

          <StatsCard 
            :number="formatNumber(selectedHouseholds)" 
            :label="selectedBarangayName ? 'Households' : 'Total Households'" 
            :icon="Building2" 
            iconColor="bg-green-500" 
            :barangay="selectedBarangayName" 
          />

        </div>

          <!-- Officials Section (always visible) -->
        <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            {{ selectedBarangayName ? `${selectedBarangayName} Officials` : 'All Barangay Officials' }}
          </h3>

          <!-- 1. LOADING -->
          <div v-if="isLoading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-300 border-t-green-600"></div>
            <div class="text-gray-500 mt-4">Loading data...</div>
          </div>

          <!-- 2. ERRORS -->
          <div v-else-if="hasErrors" class="text-center py-12">
            <div class="text-red-500 font-medium">Error loading data</div>
            <p class="text-sm text-gray-500 mt-2">Please try refreshing the page</p>
          </div>

          <!-- 3. DATA EXISTS -->
          <div v-else-if="officialsInfo.length > 0" class="space-y-3">
            <div v-for="official in officialsInfo" 
                :key="official.id"
                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex-1">
                <p class="text-gray-800 font-semibold">{{ official.official_name }}</p>
                <div class="flex gap-2 text-sm text-gray-500 mt-1">
                  <span>{{ getBarangayName(official.barangay_id) }}</span>
                  <span v-if="official.email">• {{ official.email }}</span>
                  <span v-if="official.contact_number">• {{ official.contact_number }}</span>
                </div>
              </div>

              <div :class="[
                'px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap',
                official.position === 'Captain' ? 'bg-blue-100 text-blue-700' :
                official.position === 'SK Chairman' ? 'bg-purple-100 text-purple-700' :
                official.position === 'Secretary' ? 'bg-green-100 text-green-700' :
                official.position === 'Treasurer' ? 'bg-yellow-100 text-yellow-700' :
                'bg-gray-100 text-gray-700'
              ]">
                {{ official.position }}
              </div>
            </div>
          </div>

          <!-- 4. NO DATA -->
          <div v-else class="text-center py-12 text-gray-500">
            <Users class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <div class="text-gray-500 font-medium">No officials found</div>
            <p class="text-sm text-gray-400 mt-1">
              {{ selectedBarangayName === 'all' 
                  ? 'No officials in the system' 
                  : 'This barangay has no officials assigned' }}
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</template>
