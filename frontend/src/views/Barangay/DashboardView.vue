<script setup >
import DashboardStatsCard from '@/components/cards/DashboardStatsCard.vue';
import RecentReportsTable from '@/components/tables/RecentReportsTable.vue';
import QuickActions from '@/components/others/QuickActions.vue';  
import { ref, onMounted, computed } from "vue";
import { ClipboardList, Clock, CircleDot, CircleCheckBig, Users, UserCheck } from 'lucide-vue-next';
import { useSubmitReport } from '@/stores/submitReport';
import { useCategoriesStore } from '@/stores/categories';
import {useUserStore} from "@/stores/userStore"
import DashboardBanner from '@/components/others/DashboardBanner.vue';
import { storeToRefs } from 'pinia';

const reportStore = useSubmitReport()
const categoriesStore = useCategoriesStore()
const userStore = useUserStore()

const {allReports, weeklyCounts } = storeToRefs(reportStore)

// loading state
const isLoading = ref(true)

// fetch data on mounted
onMounted(async () => {
  try {
    await reportStore.getDashboardStats()
    await reportStore.getAllReports()
    console.log(weeklyCounts.value)
  } finally {
    isLoading.value = false
  }
})

// computed refs for reactive access
const allReportsThisWeek = computed(() => weeklyCounts.value?.all_reports_this_week ?? 0)
const allReportsLastWeek = computed(() => weeklyCounts.value?.all_reports_last_week ?? 0)
const thisWeekStatuses = computed(() => weeklyCounts.value?.this_week ?? {})
const lastWeekStatuses = computed(() => weeklyCounts.value?.last_week ?? {})

// stats array reacts to weeklyCounts changes
const stats = computed(() => [
  {
    title: "Reports This Week",
    value: {
      count: allReportsThisWeek.value,
      thisWeek: allReportsThisWeek.value,
      lastWeek: allReportsLastWeek.value
    },
    icon: ClipboardList,
    bg: "bg-blue-400/40",
    textColor: "text-blue-500",
  },
  {
    title: "Pending",
    value: {
      count: thisWeekStatuses.value.pending ?? 0,
      thisWeek: thisWeekStatuses.value.pending ?? 0,
      lastWeek: lastWeekStatuses.value.pending ?? 0
    },
    icon: Clock,
    bg: "bg-yellow-400/40",
    textColor: "text-yellow-500",
  },
  {
    title: "Assigned",
    value: {
      count: thisWeekStatuses.value.assigned ?? 0,
      thisWeek: thisWeekStatuses.value.assigned ?? 0,
      lastWeek: lastWeekStatuses.value.assigned ?? 0
    },
    icon: UserCheck,
    bg: "bg-cyan-400/40",
    textColor: "text-white",
  },
  {
    title: "In Progress",
    value: {
      count: thisWeekStatuses.value.in_progress ?? 0,
      thisWeek: thisWeekStatuses.value.in_progress ?? 0,
      lastWeek: lastWeekStatuses.value.in_progress ?? 0
    },
    icon: CircleDot,
    bg: "bg-orange-400/40",
    textColor: "text-orange-500",
  },
  {
    title: "Resolved",
    value: {
      count: thisWeekStatuses.value.resolved ?? 0,
      thisWeek: thisWeekStatuses.value.resolved ?? 0,
      lastWeek: lastWeekStatuses.value.resolved ?? 0
    },
    icon: CircleCheckBig,
    bg: "bg-green-400/40",
    textColor: "text-green-500",
  }
])

</script>


<template>
    <div class="w-full mx-auto">
        <DashboardBanner title="BARANGAY 95 - A CAIBAAN" subTitle="Managing community reports and local services"/>

        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-4 gap-4 mt-4">
            <DashboardStatsCard v-for="stat in stats" :key="stat.title" :stat="stat"/>
        </div>
        <div class="mt-4 grid grid-cols-2  md:grid-cols-3 gap-4">
            <div class="col-span-2 bg-white p-4 rounded-xl shadow-sm">
              <h1 class="font-semibold">Recent Reports</h1>
              <RecentReportsTable :reports="allReports.slice(0,5)" :isLoading="isLoading" class="mt-2"/>
            </div>
            <QuickActions class="col-span-2 md:col-span-1"/>
        </div>
    </div>


</template>