<script setup >
import DashboardStatsCard from '@/components/cards/DashboardStatsCard.vue';
import RecentReportsTable from '@/components/RecentReportsTable.vue';
import QuickActions from '@/components/QuickActions.vue';
import { ref, onMounted } from "vue";
import { ClipboardList, Clock, CircleDot, CircleCheckBig, Users  } from 'lucide-vue-next';

const stats = [
  {
    title: "Reports This Week",
    value: {
      count: 102,
      thisWeek: 9,
      lastWeek: 10
    },
    icon: ClipboardList,
    bg: "bg-blue-400/40",
    textColor: "text-blue-500",
    footer: `+${9 - 10} from last week`
  },
  {
    title: "Pending",
    value: {
      count: 34,
      thisWeek: 12,
      lastWeek: 15
    },
    icon: Clock,
    bg: "bg-yellow-400/40",
    textColor: "text-yellow-500",
    footer: `-${15 - 12} from last week`
  },
  {
    title: "In Progress",
    value: {
      count: 18,
      thisWeek: 8,
      lastWeek: 6
    },
    icon: CircleDot ,
    bg: "bg-orange-400/40",
    textColor: "text-orange-500",
    footer: `+${8 - 6} updated this week`
  },
  {
    title: "Resolved",
    value: {
      count: 502,
      thisWeek: 20,
      lastWeek: 15
    },
    icon: CircleCheckBig ,
    bg: "bg-green-400/40",
    textColor: "text-green-500",
    footer: `+${20 - 15} this week`
  },
  {
    title: "Total Users",
    value: {
      count: 1240,
      thisWeek: 26,
      lastWeek: 22
    },
    icon: Users,
    bg: "bg-purple-400/40",
    textColor: "text-purple-500",
    footer: `+${26 - 22} new users`
  }
]



</script>


<template>
    <div class="w-full mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-4 gap-4">
            <DashboardStatsCard v-for="stat in stats" :key="stat.title" :stat="stat"/>
        </div>
        <div class="mt-4 grid grid-cols-2  md:grid-cols-3 gap-4">
            <div class="col-span-2 bg-white p-4 rounded-xl shadow-sm">
              <h1 class="font-semibold">Recent Reports</h1>
              <RecentReportsTable class="mt-2"/>
            </div>
            <QuickActions class="col-span-2 md:col-span-1"/>
        </div>
    </div>


</template>