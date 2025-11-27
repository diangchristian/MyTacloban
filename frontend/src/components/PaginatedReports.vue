<script setup>
import { ref, computed } from "vue"

import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import ReportsCard from "./cards/ReportsCard.vue"
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const reports = ref([
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  }
])


const perPage = 8
const currentPage = ref(Number(route.query.page) || 1)

// Compute total
const total = computed(() => reports.value.length)

function onPageChange(newPage) {
  currentPage.value = newPage
  router.replace({ query: { ...route.query, page: newPage } })
}

// Slice reports by page
const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * perPage
  const end = start + perPage
  return reports.value.slice(start, end)
})


const viewDetails = (id) => {
  router.push({
    name: 'admin.report.details',
    params: { id }
  })
}



</script>

<template>
  <div class="space-y-4 mt-4">

    <!-- Render the cards -->
    <div class="grid  md:grid-cols-2  lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <ReportsCard
            v-for="r in paginatedReports"
            :key="r.id"
            :report="r"
            @view = "viewDetails(r.id)"
          />
    </div>

    <!-- Pagination -->
    <Pagination
      v-slot="{ page }"
      :items-per-page="perPage"
      :total="total"
      :default-page="currentPage"
      @update:page="onPageChange"
    >
      <PaginationContent v-slot="{ items }">
        <PaginationPrevious />

        <template v-for="(item, index) in items" :key="index">
          <PaginationItem
            v-if="item.type === 'page'"
            :value="item.value"
            :is-active="item.value === page"
          >
            {{ item.value }}
          </PaginationItem>
        </template>

        <PaginationEllipsis />
        <PaginationNext />
      </PaginationContent>
    </Pagination>

  </div>
</template>
