<script setup>
  import { ref, computed, watch } from 'vue'
  import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
  } from '@/components/ui/pagination'
  import ReportsCard from './cards/ReportsCard.vue'
  import { useRoute, useRouter } from 'vue-router'
  
  const props = defineProps({
    reports: {
      type: Array,
      required: true,
      default: () => [],
    },
  })
  
  const route = useRoute()
  const router = useRouter()
  
  const perPage = 8
  const currentPage = ref(Number(route.query.page) || 1)
  
  /* ✅ keep page valid if reports change */
  watch(
    () => props.reports.length,
    () => {
      const maxPage = Math.max(1, Math.ceil(props.reports.length / perPage))
      if (currentPage.value > maxPage) {
        currentPage.value = maxPage
      }
    },
  )
  
  /* ✅ total items */
  const total = computed(() => props.reports.length)
  
  /* ✅ slice using props */
  const paginatedReports = computed(() => {
    const start = (currentPage.value - 1) * perPage
    const end = start + perPage
    return props.reports.slice(start, end)
  })
  
  function onPageChange(newPage) {
    currentPage.value = newPage
    router.replace({
      query: { ...route.query, page: newPage },
    })
  }
  
  const viewDetails = (id) => {
    router.push({
      name: 'admin.report.details',
      params: { id },
    })
  }
  </script>
  
  <template>
    <div class="space-y-4 mt-4">
  
      <!-- Cards -->
      <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <ReportsCard
          v-for="r in paginatedReports"
          :key="r.id"
          :report="r"
          @view="viewDetails(r.id)"
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
  