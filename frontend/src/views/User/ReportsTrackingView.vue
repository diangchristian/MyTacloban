<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/cards/ReportsCard.vue";
import ReportsDetails from "@/components/ReportsDetails.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import NoSelected from "@/components/NoSelected.vue";
import ReportsDetailsModal from "@/components/ReportsDetailsModal.vue";
import {useSubmitReport} from "@/stores/submitReport"
import { storeToRefs } from "pinia";
import { debounce } from 'lodash';
import { useAuthStore } from '@/stores/auth'


const submitReportStore = useSubmitReport()
const {reports} = storeToRefs(useSubmitReport())

const authStore = useAuthStore()      // access auth store
const userId = authStore.user?.id   


onMounted(async() => {
  submitReportStore.getUserReports()
})


const selectedReport = ref(null)
const isMobile = ref(false)
const showModal = ref(false)
const search = ref('')



const debouncedSearch = debounce(() => {
  submitReportStore.getBySearchAndStatusUser(search.value, 'all' ,userId );
}, 500);

function updateScreen(){
  isMobile.value = window.innerWidth < 1024
}


onMounted(() => {
  updateScreen();
  window.addEventListener('resize', updateScreen)
})


onBeforeUnmount(() => {
  window.removeEventListener("resize", updateScreen);

})


function openReport(report){
  selectedReport.value = report
  showModal.value = true;
}

function handleSearch(){
  console.log(search.value)
  debouncedSearch()
}




</script>
<template>
  <main class="w-full overflow-x-hidden">
    <div class="max-w-7xl mx-auto rounded-xl mt-4 w-full px-2 overflow-x-hidden">

      <!-- Filters -->
      <form action="" @submit.prevent>
        <div class="max-w-4xl flex flex-col sm:flex-row gap-4 sm:items-center w-full">
        <Input placeholder="Search reports" class="w-full sm:w-1/2" v-model="search" @keyup="handleSearch"/>

        <div class="flex flex-wrap gap-2">
          <Button  type="button" @click="submitReportStore.getUserReports()"  class="cursor-pointer">All Reports ({{ reports.length }})</Button>
          <Button  type="button"  @click="submitReportStore.getBySearchAndStatusUser(search, 'pending', userId)"    variant="outline" class="cursor-pointer">Pending ({{submitReportStore.pendingCount}})</Button>
          <Button type="button"  @click="submitReportStore.getBySearchAndStatusUser(search, 'in_progress', userId)"  variant="outline" class="cursor-pointer">In Progress ({{submitReportStore.inProgressCount}})</Button>
        </div>
      </div>
      </form>

      <!-- Content -->
      <div class="flex flex-col md:flex-row w-full mt-4 gap-4 overflow-x-hidden">

        <!-- Left Column -->
        <div
          class="bg-white rounded-lg flex flex-col flex-1 overflow-y-auto p-4 gap-4
                 h-[calc(100vh-210px)] md:h-[calc(100vh-160px)] scrollable"
        >
          <ReportsCard
            v-for="r in reports"
            :key="r.id"
            :report="r"
            @view="openReport"
          />
        </div>

        <!-- Right Column -->
        <div class="hidden md:flex md:flex-col md:w-[720px] bg-white rounded-lg p-4 
                    overflow-y-auto h-[calc(100vh-160px)]">

          <ReportsDetails
            v-if="selectedReport && !isMobile"
            :report="selectedReport"
            @close="selectedReport = null"
          />

          <NoSelected
            v-else-if="!selectedReport && !isMobile"
          />

        </div>

        <!-- Mobile Modal -->
        <ReportsDetailsModal
          v-if="showModal && selectedReport && isMobile"
          :report="selectedReport"
          @close="showModal = false"
        />
      </div>
    </div>
  </main>
</template>



<style scoped>

/* *{
  border: 1px solid red
} */
.scrollable::-webkit-scrollbar {
  width: 4px;
}
.scrollable::-webkit-scrollbar-thumb {
  background: #58F09F;
  border-radius: 10px;
}
.scrollable::-webkit-scrollbar-track {
  background: #f5f5f5;
}
</style>