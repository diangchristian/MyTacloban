<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/ReportsCard.vue";
import ReportsDetails from "@/components/ReportsDetails.vue";
import { useMediaQuery } from '@vueuse/core'
import { ref, onMounted, onBeforeUnmount, defineProps } from "vue";
import NoSelected from "@/components/NoSelected.vue";
import ReportsDetailsModal from "@/components/ReportsDetailsModal.vue";


const reports = [
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
  }
]


const selectedReport = ref(null)
const isMobile = ref(false)
const showModal = ref(false)


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


</script>
<template>
  <main class="w-full overflow-x-hidden">
    <div class="max-w-7xl mx-auto rounded-xl mt-4 w-full px-2 overflow-x-hidden">

      <!-- Filters -->
      <div class="max-w-4xl flex flex-col sm:flex-row gap-4 sm:items-center w-full">
        <Input placeholder="Search reports" class="w-full sm:w-1/2" />

        <div class="flex flex-wrap gap-2">
          <Button>All Reports (9)</Button>
          <Button variant="outline">Pending (12)</Button>
          <Button variant="outline">In Progress (3)</Button>
        </div>
      </div>

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