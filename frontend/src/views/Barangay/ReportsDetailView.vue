<script setup>
import ReportHeaderCard from '@/components/cards/ReportHeaderCard.vue';
import ReportStatusCard from '@/components/cards/ReportStatusCard.vue';
import TimelineCard from '@/components/cards/TimelineCard.vue';
import ReportImages from '@/components/reports/ReportImages.vue';
import MapLocation from '@/components/location/MapLocation.vue';
import Button from '@/components/ui/button/Button.vue';
import AddTimeline from '@/components/reports/AddTimeline.vue';
import { ref, onMounted, computed } from 'vue';
import { MessageSquare } from 'lucide-vue-next';
import {useSubmitReport} from "@/stores/submitReport"
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import ReportsDetails from '@/components/reports/ReportsDetails.vue';
import { Skeleton } from "@/components/ui/skeleton";
import ConfirmDeleteDialog from '@/components/others/ConfirmDeleteDialog.vue';
import {useDialogStore} from "@/stores/dialogStore"
import {useAuthStore} from "@/stores/auth"


const authStore = useAuthStore();
const dialogStore = useDialogStore()
const submitReportStore = useSubmitReport()
const {reportDetails, isLoading} = storeToRefs(submitReportStore)

onMounted(() => {
    submitReportStore.getReportById(route.params.id)
    
})

const refreshTimeline = async () => {
  await submitReportStore.getReportById(route.params.id)   // reload report + timelines
  addNewActivity.value = false;                            // close modal automatically
}


const route = useRoute()
const router = useRouter()


const addNewActivity = ref(false)

const addActivity = () => {
    addNewActivity.value = true
}

const handleClose = () => {
    addNewActivity.value = false;
}


const handleEscalate = () => {
  
dialogStore.openConfirm({
  title: 'Escalate Report to LGU',
  description:
    'Escalating this report will transfer handling to the LGU Admin. Barangay staff will no longer be able to update it.',
  confirmText: 'Escalate',
  variant: 'warning',
  onConfirm: () => submitReportStore.escalateToLGU(reportDetails.value[0].id) ,
})

}


</script>


<template>
    <main>
      <div class="w-full">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          
          <!-- Left Column -->
          <div class="col-span-2 space-y-4">
            <Skeleton v-if="isLoading" class="h-24 w-full rounded-lg bg-gray-200" />
            <ReportHeaderCard v-else-if="reportDetails" :header="reportDetails[0]" />
  
            <Skeleton v-if="isLoading" class="h-64 w-full rounded-lg bg-gray-200" />
            <MapLocation v-else-if="reportDetails" :coordinates="reportDetails[0].coordinates" />
  
            <Skeleton v-if="isLoading" class="h-48 w-full rounded-lg bg-gray-200" />
            <ReportImages v-else-if="reportDetails" :images="reportDetails[0].images" />
          </div>
  
          <!-- Right Column -->
          <div class="flex flex-col gap-4">
            <Skeleton v-if="isLoading" class="h-12 w-full rounded-lg bg-gray-200" />
            <ReportStatusCard v-else-if="reportDetails" :status="reportDetails[0].status" :id="reportDetails[0].id" />
  
            <div class="bg-white p-4 rounded-xl shadow-sm">
              <div class="flex justify-between items-center mb-4">
                <h3 class="inline-flex items-center gap-2">
                  <MessageSquare class="size-5 text-primary" />
                  Activity Timeline
                </h3>
                <Button size="sm" class="cursor-pointer" @click="addActivity">+ Add</Button>
              </div>
  
              <AddTimeline v-if="addNewActivity && !isLoading" @close="addNewActivity = false" :id="reportDetails[0].id" @saved="refreshTimeline"/>
              
              <div v-if="isLoading">
                <Skeleton v-for="i in 3" :key="i" class="h-12 w-full rounded-md mb-2 bg-gray-200" />
              </div>
              <TimelineCard v-else-if="reportDetails" :timelines="reportDetails[0].timelines" />

             

            </div>


            <div class="bg-white p-4 shadow-sm rounded-xl" v-if="authStore.userRole === 'BARANGAY_STAFF'">
                <h3 class="text-orange-300 font-semibold">Report Escalation</h3>
                <div class="mt-2 border border-orange-400 rounded-lg p-4 bg-orange-400/10">
                  <p class="font-bold text-orange-400">NOTICE</p>
                  <p class="text-xs text-orange-400">Escalating this report will transfer handling to the LGU Admin.
                    After escalation, barangay staff will no longer be able to update the report.</p>
                </div>
                <Button class="mt-4 bg-orange-400 hover:bg-orange-600 hover:cursor-pointer" @click="handleEscalate"> 
                  Escalate to LGU Admin
                </Button>
              </div>
          </div>
  
        </div>
      </div>
      <ConfirmDeleteDialog/>
    </main>
  </template>
  
