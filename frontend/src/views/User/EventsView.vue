<script setup>
import EventsCard from "@/components/cards/EventsCard.vue";
import Button from "@/components/ui/button/Button.vue";
import { ref, computed, onMounted } from "vue";
import { ChevronDown, ChevronUp, ChevronLeft, ChevronRight, Clock, Calendar, MapPin } from "lucide-vue-next";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { useEventStore } from "@/stores/events";
import { storeToRefs } from "pinia";

const eventStore = useEventStore();
const { events } = storeToRefs(eventStore);

onMounted(() => {
  eventStore.getEvents();
});

// ---------- Reactive States ----------
const selectedFilter = ref("All");
const isFilterOpen = ref(false);
const isYearDropdownOpen = ref(false);

// Dialog state
const isEventDialogOpen = ref(false);
const selectedEvent = ref(null);

// Filter options (DB has no category)
const FILTER_OPTIONS = ["All"];

// ---------- Filtered Events ----------
const filteredEvents = computed(() => {
  return events.value;
});

// ---------- Months ----------
const months = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];

// ---------- Available Years (Always includes current year) ----------
const availableYears = computed(() => {
  const eventYears = events.value
    .map(e => new Date(e.event_date).getFullYear())
    .filter(y => !isNaN(y));

  const uniqueYears = [...new Set(eventYears)];

  const currentYearValue = new Date().getFullYear();
  if (!uniqueYears.includes(currentYearValue)) {
    uniqueYears.push(currentYearValue);
  }

  return uniqueYears.sort((a, b) => a - b);
});

// ---------- Year Index ----------
const currentYearIndex = ref(
  availableYears.value.indexOf(new Date().getFullYear())
);

if (currentYearIndex.value === -1) {
  currentYearIndex.value = 0;
}

// ---------- Current Year ----------
const currentYear = computed(() => {
  return availableYears.value[currentYearIndex.value];
});

// ---------- Month Index ----------
const currentMonthIndex = ref(new Date().getMonth());

// ---------- Events for Current Month ----------
const eventsForCurrentMonth = computed(() => {
  const monthName = months[currentMonthIndex.value];

  return filteredEvents.value.filter(event => {
    const eventDate = new Date(event.event_date);
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

// ---------- Modal ----------
const openEventDialog = (event) => {
  selectedEvent.value = event;
  isEventDialogOpen.value = true;
};
</script>

<template>
  <div class="space-y-8">

    <div class="w-full mx-auto">
      <div class="w-full h-52 overflow-hidden rounded-md relative">
        <img src="@/assets/images/user-bg.jpg" class="w-full h-full object-cover" alt="">
        <div class="w-full h-full bg-gradient-to-l via-green to-primary/70 absolute top-0 left-0 z-2 flex flex-col justify-center px-8 text-white">
            <h1 class="text-3xl font-bold">EVENTS</h1>
            <p class="font-semibold">Upcoming schedules and activities in the city</p>
        </div>
      </div>
    </div>

    <div class="p-4 md:px-8 lg:px-12 space-y-8">

      <div class="flex flex-col md:flex-row md:justify-between items-center mb-6 gap-2">

        <!-- Filter Dropdown (All) -->
        <div class="relative w-full md:w-auto md:order-1 order-2">
          <Button
            @click="isFilterOpen = !isFilterOpen"
            class="h-11 px-6 flex items-center justify-between gap-3 rounded-full border-2 transition-all duration-200 shadow-sm w-full md:w-auto"
            :class="isFilterOpen 
              ? 'bg-green-600 text-white border-green-600 hover:bg-green-700' 
              : 'bg-white text-gray-700 border-gray-300 hover:border-green-400'"
          >
            <span class="font-medium text-sm">{{ selectedFilter }}</span>
            <component 
              :is="isFilterOpen ? ChevronUp : ChevronDown" 
              size="18" 
              :class="isFilterOpen ? 'text-white' : 'text-green-500'" 
            />
          </Button>

          <div 
            v-if="isFilterOpen" 
            class="absolute mt-2 w-full md:w-48 bg-white shadow-xl rounded-xl border border-gray-100 p-2 z-50 left-0" 
          >
            <button
              v-for="filter in FILTER_OPTIONS"
              :key="filter"
              @click="selectedFilter = filter; isFilterOpen = false"
              class="w-full text-left px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-green-100 transition-colors"
            >
              {{ filter }}
            </button>
          </div>
        </div>

        <!-- Year and Month Navigation -->
        <div class="flex flex-col items-center gap-2 md:order-2 order-1 md:mx-auto mt-4 md:mt-0">

          <!-- Year Dropdown -->
          <div class="relative mb-2">
            <Button
              @click="isYearDropdownOpen = !isYearDropdownOpen"
              class="bg-transparent text-gray-900 text-lg font-semibold px-2 py-1 hover:bg-gray-100 transition-colors"
            >
              {{ currentYear }}
            </Button>

            <div
              v-if="isYearDropdownOpen"
              class="absolute mt-1 w-28 bg-white border rounded-lg shadow-lg z-50 left-1/2 transform -translate-x-1/2"
            >
              <button
                v-for="(year, index) in availableYears"
                :key="year"
                @click="currentYearIndex = index; isYearDropdownOpen = false"
                class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-green-100 transition-colors"
              >
                {{ year }}
              </button>
            </div>
          </div>

          <!-- Month Switcher -->
          <div class="flex items-center gap-6">
            <Button 
              @click="prevMonth" 
              class="p-2 rounded-full bg-white text-green-600 hover:bg-green-50 transition-colors shadow-md border-2 border-green-100"
            >
              <ChevronLeft size="24" />
            </Button>

            <p class="text-3xl font-extrabold text-gray-900">{{ months[currentMonthIndex] }}</p>

            <Button 
              @click="nextMonth" 
              class="p-2 rounded-full bg-white text-green-600 hover:bg-green-50 transition-colors shadow-md border-2 border-green-100"
            >
              <ChevronRight size="24" />
            </Button>
          </div>
        </div>

        <!-- Invisible Spacer -->
        <div class="w-full md:w-auto md:order-3 order-3 invisible">
             <Button class="h-11 px-6 w-full md:w-auto invisible">
                 <span class="font-medium text-sm">All</span>
            </Button>
        </div>
      </div>

      <hr class="border-gray-200 mt-4" />

      <!-- Event Cards -->
      <div class="mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="event in eventsForCurrentMonth" :key="event.title + event.date">
            <EventsCard :event="event" :on-read-more="openEventDialog" /> 
          </div>
        </div>

        <p v-if="eventsForCurrentMonth.length === 0" class="text-gray-500 text-center mt-10 w-full">
          No events scheduled for {{ months[currentMonthIndex] }} {{ currentYear }}.
        </p>
      </div>

      <!-- Event Dialog -->
      <Dialog v-model:open="isEventDialogOpen">
        <DialogContent class="max-w-2xl w-full overflow-y-auto p-4 rounded-lg">
          <DialogHeader>
            <DialogTitle class="text-md font-medium text-gray-900">
              {{ selectedEvent?.title }}
            </DialogTitle>
          </DialogHeader>

          <CardContent class="space-y-2">
            <div class="flex items-center gap-2 text-gray-600 font-light text-sm">
              <Calendar class="size-4" /> <span>{{ selectedEvent?.event_date }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 font-light text-sm">
              <Clock class="size-4" /> <span>{{ selectedEvent?.event_time }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 font-light text-sm">
              <MapPin class="size-4" /> <span>{{ selectedEvent?.location }}</span>
            </div>

            <p class="text-gray-700 leading-relaxed">
              {{ selectedEvent?.content }}
            </p>

            <Button
              class="w-full mt-4 px-4 py-2 border rounded-md text-green-600 hover:bg-green-50 transition-colors"
              @click="isEventDialogOpen = false"
            >
              Close
            </Button>
          </CardContent>
        </DialogContent>
      </Dialog>
    </div>
  </div>
</template>
