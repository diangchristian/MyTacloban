<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/cards/ReportsCard.vue";
import {useSubmitReport} from "@/stores/submitReport"
import { storeToRefs } from "pinia";
import { onMounted, ref, watch } from "vue";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {getDateRange} from "@/utils/getDateRange"
import Label from "@/components/ui/label/Label.vue"; 


const submitReportStore = useSubmitReport()
const {allReports} = storeToRefs(submitReportStore)

const search = ref('')
const selectedDate = ref(null);
const selectedStatus = ref(null);

const debouncedSearch = debounce(() => {
  const {start, end} = getDateRange(selectedDate.value)
  submitReportStore.getBySearchAndStatusAdmin(search.value, selectedStatus, start, end);
}, 500);



function handleSearch(){  
  debouncedSearch()
}

const clearFilters = () => {
  search.value = "";
  selectedDate.value = null;
  selectedStatus.value = null;

  submitReportStore.getAllReports();
};

watch([search, selectedDate, selectedStatus], () => {
  debouncedSearch();
});

onMounted(async () => [
  submitReportStore.getAllReports()
])

</script>

<template>
  <main class="h-full">
    <div class="w-full">
      <form action="" @submit.prevent>
        <div class="max-w-4xl flex flex-col sm:flex-row gap-4 sm:items-center w-full ">
        <Input placeholder="Search reports" class="w-full sm:w-1/2" v-model="search" @keyup="handleSearch"/>
        <Button > Search </Button>

        <div class="flex items-center flex-wrap gap-4 w-full ">
             <!-- Status Filter -->
        <div class="flex items-center gap-2">
          <Label>Status</Label>
          <Select v-model="selectedStatus">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Select status" class="w-full" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="pending">Pending</SelectItem>
              <SelectItem value="assigned">Assigned</SelectItem>
              <SelectItem value="in_progress">In Progress</SelectItem>
              <SelectItem value="resolved">Resolved</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Date Filter -->
        <div class="flex items-center gap-2">
          <Label>Date</Label>
          <Select v-model="selectedDate">
            <SelectTrigger class="w-full ">
              <SelectValue placeholder="Select date" class="w-full" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="today">Today</SelectItem>
              <SelectItem value="this_week">This Week</SelectItem>
              <SelectItem value="this_month">This Month</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Clear Filters Button -->
        <Button variant="outline" class="" @click="clearFilters">
          Clear Filters
        </Button>
      </div>
      </div>
      </form>
      <PaginatedReports :reports="allReports"/>
      <div class="mt-4"></div>
    </div>
  </main>
</template>


<!-- <style  scoped>
*{
  border: 1px solid red
}
</style> -->