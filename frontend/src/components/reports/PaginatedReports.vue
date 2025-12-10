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
  import ReportsCard from '@/components/cards/ReportsCard.vue'
  import { useRoute, useRouter } from 'vue-router'
  import { Skeleton } from "@/components/ui/skeleton";
  import {useSubmitReport} from "@/stores/submitReport"
  import { storeToRefs } from 'pinia';




  const {isLoading} = storeToRefs(useSubmitReport())
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
  

  watch(
    () => props.reports.length,
    () => {
      const maxPage = Math.max(1, Math.ceil(props.reports.length / perPage))
      if (currentPage.value > maxPage) {
        currentPage.value = maxPage
      }
    },
  )
  
  const total = computed(() => props.reports.length)
  
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
      <div v-if="isLoading" class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <Skeleton v-for="i in 8" :key="i" class="h-96 w-full rounded-md mb-2 bg-gray-200" />
        </div>
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
        class="pb-4"
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
  