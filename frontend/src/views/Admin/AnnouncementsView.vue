<script setup>
import DashboardStatsCard from "@/components/cards/DashboardStatsCard.vue";
// import AnnouncementCard from "@/components/cards/AnnouncementCard.vue";
import {
  ClipboardList,
  Clock,
  CircleDot,
  CircleCheckBig,
  Users,
  Archive,
  Newspaper
} from "lucide-vue-next";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import Label from "@/components/ui/label/Label.vue";
import { RouterLink } from "vue-router";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useAnnouncementStore } from "@/stores/announcements";
import { Skeleton } from "@/components/ui/skeleton";
import { storeToRefs } from "pinia";
import { onMounted, ref, computed, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import ConfirmDeleteDialog from "@/components/others/ConfirmDeleteDialog.vue";
import { debounce } from "lodash";
import PaginatedAnnouncements from "@/components/others/PaginatedAnnouncements.vue";
import {getDateRange} from "@/utils/getDateRange"
import EmptyCard from "@/components/cards/EmptyCard.vue";

const router = useRouter();

const announcementStore = useAnnouncementStore();
const { announcements, stats, categories, isLoading } =
  storeToRefs(announcementStore);
const searchTerm = ref("");
const selectedCategory = ref(null);
const selectedDate = ref(null);
const selectedStatus = ref(null);

onMounted(() => {
  announcementStore.getAnnouncement();
  announcementStore.getStats();
  announcementStore.getCategories();
});



const fetchAnnouncements = () => {
  const { start, end } = getDateRange(selectedDate.value);
  announcementStore.getBySearch(
    searchTerm.value,
    selectedCategory.value,
    start,
    end,
    selectedStatus.value     // <-- new!
  );
};


const emptyDisplay = {
  title: 'You currently don\'t have an announcement',
  description: 'You\'re all caught up. New announcements will appear here.',
  icon: Newspaper
}


// Debounced version of fetch
const debouncedFetch = debounce(fetchAnnouncements, 300); // 300ms delay

// Watch for changes
watch([searchTerm, selectedCategory, selectedDate, selectedStatus], () => {
  debouncedFetch();
});

const announcementStats = computed(() => {
  if (!stats.value) return [];

  const total = stats.value.total || { count: 0, thisWeek: 0, lastWeek: 0 };
  const published = stats.value.published || {
    count: 0,
    thisWeek: 0,
    lastWeek: 0,
  };
  const draft = stats.value.draft || { count: 0, thisWeek: 0, lastWeek: 0 };
  const archived = stats.value.archived || { count: 0, thisWeek: 0, lastWeek: 0 }
  return [
    {
      title: "Total Announcements",
      value: total,
      icon: ClipboardList,
      bg: "bg-blue-400/40",
      textColor: "text-blue-500",
      footer: `${total.thisWeek - total.lastWeek >= 0 ? "+" : ""}${
        total.thisWeek - total.lastWeek
      } from last week`,
    },
    {
      title: "Published",
      value: published,
      icon: Clock,
      bg: "bg-yellow-400/40",
      textColor: "text-yellow-500",
      footer: `${published.thisWeek - published.lastWeek >= 0 ? "+" : ""}${
        published.thisWeek - published.lastWeek
      } from last week`,
    },
    {
      title: "Draft",
      value: draft,
      icon: CircleDot,
      bg: "bg-orange-400/40",
      textColor: "text-orange-500",
      footer: `${draft.thisWeek - draft.lastWeek >= 0 ? "+" : ""}${
        draft.thisWeek - draft.lastWeek
      } updated this week`,
    },
    {
      title: "Archived",
      value: archived,
      icon: Archive,
      bg: "bg-red-400/40",
      textColor: "text-red-500",
      footer: `${archived.thisWeek - archived.lastWeek >= 0 ? "+" : ""}${
        archived.thisWeek - archived.lastWeek
      } updated this week`,
    },
  ];
});

const clearFilters = () => {
  searchTerm.value = "";
  selectedCategory.value = null;
  selectedDate.value = null;
  selectedStatus.value = null;

  announcementStore.getAnnouncement();
};


</script>

<template>
  <div class="w-full mx-auto pb-4">
    <div
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
      v-if="isLoading"
    >
      <Skeleton
        v-for="n in 3"
        :key="n"
        class="h-40 w-full bg-gray-200 rounded-md"
      />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" v-else>
      <DashboardStatsCard
        v-for="stat in announcementStats"
        :key="stat.title"
        :stat="stat"
      />
    </div>

    <div class="w-full  mt-8">
      <div class="flex gap-3">
        <Input
        v-model="searchTerm"
        placeholder="Search announcements"
        class="w-full max-w-md bg-white"
      />
      <Button @click="fetchAnnouncements"> Search </Button>

      </div>
      <div class="flex items-center flex-wrap gap-4 mt-4">
        <!-- Category Filter -->
        <div class="flex items-center gap-2">
          <Label>Category</Label>
          <Select v-model="selectedCategory">
            <SelectTrigger class="w-full ">
              <SelectValue placeholder="Select category" class="w-full" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="cat in categories"
                :key="cat.id"  
                :value="cat.id"
              >
                {{ cat.category_name }}
              </SelectItem>
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

        <!-- Status Filter -->
      <div class="flex items-center gap-2">
        <Label>Status</Label>
        <Select v-model="selectedStatus">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Select status" class="w-full" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="published">Published</SelectItem>
            <SelectItem value="draft">Draft</SelectItem>
            <SelectItem value="archived">Archived</SelectItem>
          </SelectContent>
        </Select>
      </div>



        <!-- Clear Filters Button -->
        <Button variant="outline" class="" @click="clearFilters">
          Clear Filters
        </Button>
      </div>
    </div>

    <div class="mt-4">
      <Button asChild>
        <RouterLink to="/admin/announcements/create">+ Add</RouterLink>
      </Button>
    </div>
    <PaginatedAnnouncements  :announcements="announcements" v-if="announcements.length !== 0"/>

  <EmptyCard v-else :emptyObject="emptyDisplay"/>

    <ConfirmDeleteDialog />
  </div>
</template>
