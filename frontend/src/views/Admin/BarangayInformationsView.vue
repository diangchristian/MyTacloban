<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useBarangayStore } from '@/stores/barangay'
import { useBarangayOfficialStore } from '@/stores/barangayOfficial'
import { useDialogStore } from '@/stores/dialogStore'
import StatsCard from '@/components/cards/BarangayInfoCard.vue'
import BarangayFormDialog from '@/components/forms/BarangayFormDialog.vue'
import OfficialFormDialog from '@/components/forms/OfficialFormDialog.vue'
import ConfirmDeleteDialog from '@/components/others/ConfirmDeleteDialog.vue'
import { Users, Building2, UsersRound, Search, Shield, UserCircle, FileText, Wallet, UserCheck, Star, Plus, Pencil, Trash2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectGroup,
  SelectLabel,
  SelectItem,
} from '@/components/ui/select'
import {
  Pagination,
  PaginationContent,
  PaginationFirst,
  PaginationLast,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import { Button } from '@/components/ui/button'

// ============================================================
// STORES & STATE
// ============================================================
const barangayStore = useBarangayStore()
const barangayOfficialStore = useBarangayOfficialStore()
const dialogStore = useDialogStore()

const searchQuery = ref('')
const selectedBarangayName = ref('all')
const currentPage = ref(1)
const ITEMS_PER_PAGE = 10
const showBarangayDialog = ref(false)
const selectedBarangayForEdit = ref(null)
const showOfficialDialog = ref(false)
const selectedOfficialForEdit = ref(null)

// ============================================================
// LIFECYCLE
// ============================================================
onMounted(async () => {
  try {
    await Promise.all([
      barangayStore.getAllBarangay(),
      barangayOfficialStore.getAllOfficials()
    ])
  } catch (error) {
    console.error('Error loading data:', error)
  }
})

// reset page when barangay selection changes
watch(selectedBarangayName, () => {
  currentPage.value = 1
})

// ============================================================
// BARANGAY COMPUTED
// ============================================================
const barangays = computed(() => barangayStore.barangays || [])

const selectedBarangay = computed(() => {
  if (selectedBarangayName.value === 'all') return null
  return barangays.value.find(b => b.name === selectedBarangayName.value)
})

const filteredBarangays = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return barangays.value
  return barangays.value.filter(b => b.name.toLowerCase().includes(query))
})

const searchSuggestions = computed(() => {
  return searchQuery.value.trim() ? filteredBarangays.value : []
})

// ============================================================
// OFFICIALS COMPUTED
// ============================================================
const allOfficials = computed(() => barangayOfficialStore.officials || [])

const filteredOfficials = computed(() => {
  if (selectedBarangayName.value === 'all') return allOfficials.value
  const barangay = selectedBarangay.value
  if (!barangay) return []
  return allOfficials.value.filter(o => o.barangay_id === barangay.id)
})

const totalOfficials = computed(() => filteredOfficials.value.length)
const totalPages = computed(() => Math.ceil(totalOfficials.value / ITEMS_PER_PAGE))

const paginatedOfficials = computed(() => {
  const start = (currentPage.value - 1) * ITEMS_PER_PAGE
  const end = start + ITEMS_PER_PAGE
  return filteredOfficials.value.slice(start, end)
})

// ============================================================
// STATISTICS COMPUTED
// ============================================================
const statistics = computed(() => {
  const isAll = selectedBarangayName.value === 'all'
  const barangay = selectedBarangay.value

  return {
    officials: totalOfficials.value,
    population: isAll 
      ? barangays.value.reduce((sum, b) => sum + Number(b.population || 0), 0)
      : Number(barangay?.population || 0),
    households: isAll
      ? barangays.value.reduce((sum, b) => sum + Number(b.households || 0), 0)
      : Number(barangay?.households || 0)
  }
})

// ============================================================
// UTILITIES
// ============================================================
const isLoading = computed(() => 
  barangayStore.isLoading || barangayOfficialStore.isLoading
)

const hasErrors = computed(() =>
  Object.keys(barangayStore.errors || {}).length > 0 ||
  Object.keys(barangayOfficialStore.errors || {}).length > 0
)

const formatNumber = (num) => Number(num).toLocaleString('en-US')

const getBarangayName = (barangayId) => {
  const barangay = barangays.value.find(b => b.id === barangayId)
  return barangay?.name || 'N/A'
}

const getPositionStyle = (position) => {
  const styles = {
    'captain': {
      bg: 'bg-blue-500',
      text: 'text-white',
      icon: Shield,
      label: 'Barangay captain',
      priority: 1
    },
    'skchairman': {
      bg: 'bg-purple-500',
      text: 'text-white',
      icon: Star,
      label: 'SK chairman',
      priority: 2
    },
    'secretary': {
      bg: 'bg-green-500',
      text: 'text-white',
      icon: FileText,
      label: 'Barangay secretary',
      priority: 3
    },
    'treasurer': {
      bg: 'bg-yellow-500',
      text: 'text-white',
      icon: Wallet,
      label: 'Barangay treasurer',
      priority: 4
    },
    'councilor': {
      bg: 'bg-orange-500',
      text: 'text-white',
      icon: UserCheck,
      label: 'Barangay councilor',
      priority: 5
    }
  }
  return styles[position?.toLowerCase()] || {
    bg: 'bg-gray-500',
    text: 'text-white',
    icon: UserCircle,
    label: position || 'Official',
    priority: 99
  }
}

const getPositionClass = (position) => {
  const style = getPositionStyle(position)
  return `${style.bg} ${style.text}`
}

// ============================================================
// HANDLERS
// ============================================================
const handleSelectSuggestion = (name) => {
  selectedBarangayName.value = name
  searchQuery.value = ''
}

const goToPage = (page) => {
  currentPage.value = Math.max(1, Math.min(page, totalPages.value))
}

const openAddBarangayDialog = () => {
  selectedBarangayForEdit.value = null
  showBarangayDialog.value = true
}

const openEditBarangayDialog = (barangay) => {
  selectedBarangayForEdit.value = barangay
  showBarangayDialog.value = true
}

const handleBarangaySaved = async () => {
  await barangayStore.getAllBarangay()
}

const handleDeleteBarangay = async (barangay) => {
  dialogStore.openConfirm({
    title: 'Delete Barangay',
    description: `Are you sure you want to delete ${barangay.name}? This action cannot be undone and will remove all associated data.`,
    confirmText: 'Delete',
    onConfirm: async () => {
      try {
        await barangayStore.deleteBarangay(barangay.id)
        toast.success('Barangay deleted successfully')
        
        // if deleted barangay was selected, reset to 'all'
        if (selectedBarangayName.value === barangay.name) {
          selectedBarangayName.value = 'all'
        }
      } catch (error) {
        toast.error('Failed to delete barangay')
      }
    }
  })
}

const openAddOfficialDialog = () => {
  selectedOfficialForEdit.value = null
  showOfficialDialog.value = true
}

const openEditOfficialDialog = (official) => {
  selectedOfficialForEdit.value = official
  showOfficialDialog.value = true
}

const handleOfficialSaved = async () => {
  await barangayOfficialStore.getAllOfficials()
}

const handleDeleteOfficial = async (official) => {
  dialogStore.openConfirm({
    title: 'Delete Official',
    description: `Are you sure you want to delete ${official.official_name} (${getPositionStyle(official.position).label})? This action cannot be undone.`,
    confirmText: 'Delete',
    onConfirm: async () => {
      try {
        await barangayOfficialStore.deleteOfficial(official.id)
        toast.success('Official deleted successfully')
      } catch (error) {
        toast.error('Failed to delete official')
      }
    }
  })
}
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

          <div class="flex items-center gap-3">
            <!-- Add Barangay Button -->
            <Button @click="openAddBarangayDialog" class="bg-green-600 hover:bg-green-700 text-white">
              <Plus class="w-4 h-4 mr-2" />
              Add Barangay
            </Button>

            <!-- Edit Selected Barangay -->
            <Button 
              v-if="selectedBarangayName !== 'all'" 
              @click="openEditBarangayDialog(selectedBarangay)" 
              variant="outline"
              class="border-green-600 text-green-600 hover:bg-green-50"
            >
              <Pencil class="w-4 h-4 mr-2" />
              Edit
            </Button>

            <!-- Delete Selected Barangay -->
            <Button 
              v-if="selectedBarangayName !== 'all'" 
              @click="handleDeleteBarangay(selectedBarangay)" 
              variant="outline"
              class="border-red-600 text-red-600 hover:bg-red-50"
            >
              <Trash2 class="w-4 h-4 mr-2" />
              Delete
            </Button>

          <Select 
            v-model:model-value="selectedBarangayName"
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
            :number="statistics.officials" 
            label="Officials" 
            :icon="Users" 
            iconColor="bg-blue-500" 
            :barangay="selectedBarangayName" 
          />

          <StatsCard 
            :number="formatNumber(statistics.population)" 
            label="Population" 
            :icon="UsersRound" 
            iconColor="bg-purple-500" 
            :barangay="selectedBarangayName" 
          />

          <StatsCard 
            :number="formatNumber(statistics.households)" 
            label="Households" 
            :icon="Building2" 
            iconColor="bg-green-500" 
            :barangay="selectedBarangayName" 
          />
        </div>

        <!-- Officials Section -->
        <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border border-gray-200">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
              {{ selectedBarangayName === 'all' ? 'All Barangay Officials' : `${selectedBarangayName} Officials` }}
            </h3>
            <Button @click="openAddOfficialDialog" class="bg-blue-600 hover:bg-blue-700 text-white" size="sm">
              <Plus class="w-4 h-4 mr-2" />
              Add Official
            </Button>
          </div>

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

          <!-- Officials List -->
          <div v-else-if="filteredOfficials.length > 0">
            <div class="space-y-3 mb-4">
              <div 
                v-for="official in paginatedOfficials" 
                :key="official.id"
                class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200 hover:border-gray-300"
              >
                <!-- Avatar -->
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    {{ official.official_name.charAt(0).toUpperCase() }}
                  </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <p class="text-gray-900 font-semibold text-base">{{ official.official_name }}</p>
                  </div>
                  <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                      <Building2 class="w-3.5 h-3.5" />
                      {{ getBarangayName(official.barangay_id) }}
                    </span>
                    <span v-if="official.email" class="flex items-center gap-1 truncate">
                      <span class="text-gray-400">•</span>
                      {{ official.email }}
                    </span>
                    <span v-if="official.contact_number" class="flex items-center gap-1">
                      <span class="text-gray-400">•</span>
                      {{ official.contact_number }}
                    </span>
                  </div>
                </div>

                <!-- Position Badge -->
                <div class="flex-shrink-0 flex items-center gap-2">
                  <div :class="['px-4 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 shadow-sm', getPositionClass(official.position)]">
                    <component :is="getPositionStyle(official.position).icon" class="w-4 h-4" />
                    {{ getPositionStyle(official.position).label }}
                  </div>
                  
                  <!-- Action Buttons -->
                  <Button @click="openEditOfficialDialog(official)" variant="outline" size="icon" class="h-9 w-9">
                    <Pencil class="w-4 h-4" />
                  </Button>
                  <Button @click="handleDeleteOfficial(official)" variant="outline" size="icon" class="h-9 w-9 text-red-600 hover:text-red-700 hover:bg-red-50">
                    <Trash2 class="w-4 h-4" />
                  </Button>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between pt-4 border-t border-gray-200">
              <p class="text-sm text-gray-500">
                Showing {{ (currentPage - 1) * ITEMS_PER_PAGE + 1 }} to {{ Math.min(currentPage * ITEMS_PER_PAGE, totalOfficials) }} of {{ totalOfficials }} officials
              </p>
              
              <Pagination v-model:page="currentPage" :total="totalOfficials" :items-per-page="ITEMS_PER_PAGE" :sibling-count="1" show-edges>
                <PaginationContent class="flex items-center gap-1">
                  <PaginationFirst @click="goToPage(1)" :disabled="currentPage === 1" class="cursor-pointer"/>
                  <PaginationPrevious @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="cursor-pointer"/>

                  <Button
                    v-for="pageNum in totalPages" 
                    :key="pageNum"
                    @click="goToPage(pageNum)"
                    class="w-9 h-9 p-0 cursor-pointer" 
                    :variant="currentPage === pageNum ? 'default' : 'outline'"
                  >
                    {{ pageNum }}
                  </Button>

                  <PaginationNext @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="cursor-pointer"/>
                  <PaginationLast @click="goToPage(totalPages)" :disabled="currentPage === totalPages" class="cursor-pointer" />
                </PaginationContent>
              </Pagination>
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

  <!-- Barangay CRUD Dialog -->
  <BarangayFormDialog 
    v-model:open="showBarangayDialog" 
    :barangay="selectedBarangayForEdit"
    @saved="handleBarangaySaved"
  />

  <!-- Official CRUD Dialog -->
  <OfficialFormDialog 
    v-model:open="showOfficialDialog" 
    :official="selectedOfficialForEdit"
    :preselected-barangay-id="selectedBarangay?.id"
    @saved="handleOfficialSaved"
  />

  <!-- Delete Confirmation Dialog -->
  <ConfirmDeleteDialog />
</div>
</template>
