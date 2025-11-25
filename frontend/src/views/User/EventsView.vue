<script setup>
import EventsCard from "@/components/EventsCard.vue";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import { ref, computed } from "vue";
import { ChevronDown, ChevronUp, ChevronLeft, ChevronRight } from "lucide-vue-next";

// ---------- Event Data ----------
const events = [
  { category: "Health", title: "Free Medical Mission", location: "Barangay 50 Covered Court", description: "Free medical services for all residents.", time: "8:00 AM – 3:00 PM", date: "2025-11-19" },
  { category: "Sports", title: "Basketball Finals", location: "City Gym", description: "Inter-barangay championship games.", time: "6:00 PM – 10:00 PM", date: "2025-11-25" },
  { category: "Community", title: "Clean-Up Drive", location: "Nula-Tula Seawall", description: "Join us in keeping our community clean.", time: "7:00 AM – 11:00 AM", date: "2025-12-05" },
  { category: "Education", title: "Scholarship Orientation", location: "City Hall Lobby", description: "Information on available scholarships.", time: "2:00 PM – 4:00 PM", date: "2025-12-10" },
  { category: "Festival", title: "New Year Celebration", location: "City Plaza", description: "Welcome the new year!", time: "8:00 PM – 1:00 AM", date: "2026-01-01" }
];

// ---------- Reactive States ----------
const searchQuery = ref("");
const selectedFilter = ref("All");
const isFilterOpen = ref(false);
const isYearDropdownOpen = ref(false);

// ---------- Compute Dynamic Categories ----------
const FILTER_OPTIONS = computed(() => {
  const categories = events.map(e => e.category);
  return ["All", ...new Set(categories)];
});

// ---------- Filtered Events ----------
const filteredEvents = computed(() => {
  return events.filter(e => {
    const matchesSearch = e.title.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesFilter = selectedFilter.value === "All" || e.category === selectedFilter.value;
    return matchesSearch && matchesFilter;
  });
});

// ---------- Months Array ----------
const months = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];

// ---------- Available Years ----------
const availableYears = computed(() => {
  const years = events.map(e => new Date(e.date).getFullYear());
  return [...new Set(years)].sort((a,b) => a-b);
});

// ---------- Page Turner State ----------
const currentMonthIndex = ref(new Date().getMonth());
const currentYearIndex = ref(availableYears.value.indexOf(new Date().getFullYear()) >= 0 ? availableYears.value.indexOf(new Date().getFullYear()) : 0);

// ---------- Computed Current Year ----------
const currentYear = computed(() => availableYears.value[currentYearIndex.value]);

// ---------- Events for Current Month + Year ----------
const eventsForCurrentMonth = computed(() => {
  const monthName = months[currentMonthIndex.value];
  return filteredEvents.value.filter(event => {
    const eventDate = new Date(event.date);
    return (
      eventDate.getFullYear() === currentYear.value &&
      eventDate.toLocaleString("default", { month: "long" }) === monthName
    );
  });
});

// ---------- Page Turner Methods ----------
function prevMonth() {
  if (currentMonthIndex.value > 0) currentMonthIndex.value--;
  else currentMonthIndex.value = 11;
}
function nextMonth() {
  if (currentMonthIndex.value < 11) currentMonthIndex.value++;
  else currentMonthIndex.value = 0;
}
</script>

<template>
  <div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <Input v-model="searchQuery" placeholder="Search events..." class="w-full md:w-1/3" />

      <!-- Category Filter -->
      <div class="relative">
        <Button
          @click="isFilterOpen = !isFilterOpen"
          class="h-11 px-4 flex items-center justify-between gap-2 rounded-xl border w-40"
          :class="isFilterOpen ? 'bg-green-500 text-white border-green-500' : 'bg-white text-green-500 border-gray-300'"
        >
          {{ selectedFilter }}
          <component :is="isFilterOpen ? ChevronUp : ChevronDown" size="18" :class="isFilterOpen ? 'text-white' : 'text-green-500'" />
        </Button>

        <div v-if="isFilterOpen" class="absolute mt-2 w-40 bg-white shadow-lg rounded-xl border p-2 z-50">
          <button
            v-for="filter in FILTER_OPTIONS"
            :key="filter"
            @click="selectedFilter = filter; isFilterOpen = false"
            class="w-full text-left px-3 py-2 rounded-lg hover:bg-green-100"
          >
            {{ filter }}
          </button>
        </div>
      </div>
    </div>

  
    <div class="flex flex-col items-center gap-1 mt-6">

      <!-- Year -->
      <div class="relative">
        <!-- Year Selector Button (Transparent) -->
<!-- Year Selector Button (Plain Black, Transparent, No Arrow, No Hover) -->
<div class="relative">
  <Button
    @click="isYearDropdownOpen = !isYearDropdownOpen"
    class="bg-transparent text-black text-lg font-semibold px-2 py-1 flex items-center gap-0"
  >
    {{ currentYear }}
  </Button>

  <!-- Dropdown -->
  <div
    v-if="isYearDropdownOpen"
    class="absolute mt-1 w-28 bg-white border rounded-lg shadow-lg z-50"
  >
    <button
      v-for="(year, index) in availableYears"
      :key="year"
      @click="currentYearIndex = index; isYearDropdownOpen = false"
      class="w-full text-left px-3 py-2 hover:bg-green-100"
    >
      {{ year }}
    </button>
  </div>
</div>



        <div v-if="isYearDropdownOpen" class="absolute mt-1 w-28 bg-white border rounded-lg shadow-lg z-50">
          <button
            v-for="(year, index) in availableYears"
            :key="year"
            @click="currentYearIndex = index; isYearDropdownOpen = false"
            class="w-full text-left px-3 py-2 hover:bg-green-100"
          >
            {{ year }}
          </button>
        </div>
      </div>

      <!-- Month Navigation -->
      <div class="flex items-center gap-4 mt-1">
        <Button @click="prevMonth" class="p-2 rounded-full hover:bg-green-100">
          <ChevronLeft size="24" />
        </Button>

        <p class="text-2xl font-bold">{{ months[currentMonthIndex] }}</p>

        <Button @click="nextMonth" class="p-2 rounded-full hover:bg-green-100">
          <ChevronRight size="24" />
        </Button>
      </div>
    </div>

    <!-- Event Cards -->
    <div class="flex flex-col gap-4 mt-4">
      <EventsCard v-for="event in eventsForCurrentMonth" :key="event.title + event.date" :event="event" />
      <p v-if="eventsForCurrentMonth.length === 0" class="text-gray-500 text-center mt-4">
        No events for this month.
      </p>
    </div>
  </div>
</template>

