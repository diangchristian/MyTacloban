<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/cards/ReportsCard.vue";
import {useSubmitReport} from "@/stores/submitReport"
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import PaginatedReports from "@/components/reports/PaginatedReports.vue"; 
import { debounce } from 'lodash';


const submitReportStore = useSubmitReport()
const {reports} = storeToRefs(submitReportStore)

const search = ref('')

const debouncedSearch = debounce(() => {
  submitReportStore.getBySearchAndStatusAdmin(search.value, 'all');
}, 500);



function handleSearch(){
  console.log(search.value)
  debouncedSearch()
}


onMounted(async () => [
  submitReportStore.getAllReports()
])

</script>

<template>
  <main class="h-full">
    <div class="w-full">
      <form action="" @submit.prevent>
        <div class="max-w-4xl flex flex-col sm:flex-row gap-4 sm:items-center w-full">
        <Input placeholder="Search reports" class="w-full sm:w-1/2" v-model="search" @keyup="handleSearch"/>

        <div class="flex flex-wrap gap-2">
          <Button  type="button" @click="submitReportStore.getAllReports()"  class="cursor-pointer">All Reports ({{ reports.length }})</Button>
          <Button  type="button"  @click="submitReportStore.getBySearchAndStatusAdmin(search, 'pending')"    variant="outline" class="cursor-pointer">Pending ({{submitReportStore.pendingCount}})</Button>
          <Button type="button"  @click="submitReportStore.getBySearchAndStatusAdmin(search, 'in_progress')"  variant="outline" class="cursor-pointer">In Progress ({{submitReportStore.inProgressCount}})</Button>
        </div>
      </div>
      </form>
      <PaginatedReports :reports="reports"/>
      <div class="mt-4"></div>
    </div>
  </main>
</template>


<!-- <style  scoped>
*{
  border: 1px solid red
}
</style> -->