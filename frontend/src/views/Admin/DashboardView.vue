<script setup >
import DashboardStatsCard from '@/components/cards/DashboardStatsCard.vue';
import RecentReportsTable from '@/components/tables/RecentReportsTable.vue';
import QuickActions from '@/components/others/QuickActions.vue';  
import { ref, onMounted, computed } from "vue";
import { ClipboardList, Clock, CircleDot, CircleCheckBig, Users  } from 'lucide-vue-next';
import { useSubmitReport } from '@/stores/submitReport';
import { useCategoriesStore } from '@/stores/categories';
import {useUserStore} from "@/stores/userStore"

// initialize stores for reports, users, and categories
const reportStore = useSubmitReport();
const categoriesStore = useCategoriesStore();
const userStore = useUserStore()

// track loading state while fetching data
const isLoading = ref(true);

// fetch all data when component mounts
onMounted(async () => {
  try {
    // load reports, users, and categories in parallel for faster loading
    await Promise.all([
      reportStore.getAllReports(),
      userStore.fetchUsers(),
      categoriesStore.getReportCategories()
    ]);
  } finally {
    isLoading.value = false;
  }
});


// get the 5 most recent reports sorted by date
const recentReports = computed(() => {
  // ensure we have an array to work with
  const items = Array.isArray(reportStore.allReports) ? reportStore.allReports : [];
  
  // sort reports by creation date (newest first)
  const sorted = [...items].sort((a, b) => {
    const da = new Date(a.created_at || a.dateSubmitted || 0).getTime();
    const db = new Date(b.created_at || b.dateSubmitted || 0).getTime();
    return db - da;
  });

  // create a lookup map for faster category matching by id
  const categoryMap = new Map(
    (categoriesStore.reportCategories || []).map(c => [String(c.id), c])
  );

  // helper function to find the category name from various possible fields
  const resolveCategory = (r) => {
    // first try direct category name fields
    const direct = r.category?.name || r.category?.title || r.category || r.category_name || r.categoryName;
    if (direct) return direct;

    // if not found, try looking up by category id
    const id = r.category_id ?? r.report_category_id ?? r.categoryId;
    if (id != null) {
      const found = categoryMap.get(String(id));
      if (found?.name) return found.name;
      if (found?.title) return found.title;
      if (found?.category_name) return found.category_name;
    }
    return 'Unknown';
  };

  // take top 5 reports and format them for the table
  return sorted.slice(0, 5).map(r => ({
    id: r.id || r.reference || `#${r.id}`,
    category: resolveCategory(r),
    title: r.title || r.description || '—',
    status: (r.status || '').replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase()) || 'Pending',
    dateSubmitted: new Date(r.created_at || Date.now()).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  }));
});

// calculate date ranges for this week and last week
const now = new Date();
const startOfThisWeek = new Date(now);
startOfThisWeek.setDate(now.getDate() - 7); // 7 days ago
const startOfLastWeek = new Date(now);
startOfLastWeek.setDate(now.getDate() - 14); // 14 days ago

// helper function to count reports within a date range and optional status filter
const countReportsInRange = (items, start, end, predicate = () => true) => {
  return items.filter(r => {
    const created = new Date(r.created_at || r.dateSubmitted || 0).getTime();
    // check if report was created within the date range and matches the predicate (status filter)
    return created >= start.getTime() && created < end.getTime() && predicate(r);
  }).length;
};

// compute all dashboard statistics with week-over-week comparisons
const stats = computed(() => {
  const items = Array.isArray(reportStore.reports) ? reportStore.reports : [];

  // count total reports for this week and last week
  const totalThisWeek = countReportsInRange(items, startOfThisWeek, now);
  const totalLastWeek = countReportsInRange(items, startOfLastWeek, startOfThisWeek);

  // count pending reports for both weeks
  const pendingThisWeek = countReportsInRange(items, startOfThisWeek, now, r => r.status === 'pending');
  const pendingLastWeek = countReportsInRange(items, startOfLastWeek, startOfThisWeek, r => r.status === 'pending');

  // count in-progress reports for both weeks
  const inProgressThisWeek = countReportsInRange(items, startOfThisWeek, now, r => r.status === 'in_progress');
  const inProgressLastWeek = countReportsInRange(items, startOfLastWeek, startOfThisWeek, r => r.status === 'in_progress');

  // count resolved reports for both weeks
  const resolvedThisWeek = countReportsInRange(items, startOfThisWeek, now, r => r.status === 'resolved');
  const resolvedLastWeek = countReportsInRange(items, startOfLastWeek, startOfThisWeek, r => r.status === 'resolved');

  // return array of stat card configurations
  return ([
  {
    title: "Reports This Week",
    value: {
      count: reportStore.reports.length, // total count of all reports
      thisWeek: totalThisWeek,
      lastWeek: totalLastWeek
    },
    icon: ClipboardList,
    bg: "bg-blue-400/40",
    textColor: "text-blue-500",
  },
  {
    title: "Pending",
    value: {
      count: reportStore.pendingCount, // total pending reports
      thisWeek: pendingThisWeek,
      lastWeek: pendingLastWeek
    },
    icon: Clock,
    bg: "bg-yellow-400/40",
    textColor: "text-yellow-500",
  },
  {
    title: "In Progress",
    value: {
      count: reportStore.inProgressCount, // total in-progress reports
      thisWeek: inProgressThisWeek,
      lastWeek: inProgressLastWeek
    },
    icon: CircleDot,
    bg: "bg-orange-400/40",
    textColor: "text-orange-500",
  },
  {
    title: "Resolved",
    value: {
      count: reportStore.resolvedCount, // total resolved reports
      thisWeek: resolvedThisWeek,
      lastWeek: resolvedLastWeek
    },
    icon: CircleCheckBig,
    bg: "bg-green-400/40",
    textColor: "text-green-500",
  }
  ]);
});

</script>


<template>
    <div class="w-full mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-4 gap-4">
            <DashboardStatsCard v-for="stat in stats" :key="stat.title" :stat="stat"/>
        </div>
        <div class="mt-4 grid grid-cols-2  md:grid-cols-3 gap-4">
            <div class="col-span-2 bg-white p-4 rounded-xl shadow-sm">
              <h1 class="font-semibold">Recent Reports</h1>
              <RecentReportsTable :reports="recentReports" :isLoading="isLoading" class="mt-2"/>
            </div>
            <QuickActions class="col-span-2 md:col-span-1"/>
        </div>
    </div>


</template>