<script setup>
import EventsCard from "@/components/cards/EventsCard.vue";
import DashboardStatsCard from '@/components/cards/DashboardStatsCard.vue';
// Input component import added back to allow search functionality if needed (though not used currently)
// If you don't need the Input component, you can delete this line:
// import Input from "@/components/ui/input/Input.vue"; 
import Button from "@/components/ui/button/Button.vue";
import { ref, computed } from "vue";
// All necessary Lucide components are imported
import { ChevronDown, ChevronUp, ChevronLeft, ChevronRight, Clock, Calendar, MapPin, ClipboardList, CircleDot } from "lucide-vue-next";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

// ---------- Event Data ----------
const events = [
  { 
    category: "Health", 
    title: "Free Medical Mission", 
    location: "Barangay 50 Covered Court", 
    description: "Free medical services for all residents.",
    content: "The City Health Office is sponsoring a massive free medical mission offering consultations, dental check-ups, minor surgery, and free medicines for all residents of Barangay 50 and surrounding areas. Please bring any existing medical records. First come, first served.",
    time: "8:00 AM – 3:00 PM", 
    date: "2025-11-19" 
  },
  { 
    category: "Sports", 
    title: "Basketball Finals", 
    location: "City Gym", 
    description: "Inter-barangay championship games.",
    content: "Come and watch the exciting finale of the inter-barangay basketball tournament! The championship game between Barangay 21 and Barangay 6 will determine this year's champion. Tickets are available at the City Youth Affairs Office.",
    time: "6:00 PM – 10:00 PM", 
    date: "2025-11-25" 
  },
  { 
    category: "Community", 
    title: "Clean-Up Drive", 
    location: "Nula-Tula Seawall", 
    description: "Join us in keeping our community clean.",
    content: "The City Environment and Natural Resources Office (CENRO) is organizing a large-scale clean-up drive along the Nula-Tula Seawall. Volunteers are requested to bring gloves and wear appropriate attire. Snacks and water will be provided. Let's protect our coastal environment!",
    time: "7:00 AM – 11:00 AM", 
    date: "2025-12-05" 
  },
  { 
    category: "Education", 
    title: "Scholarship Orientation", 
    location: "City Hall Lobby", 
    description: "Information on available scholarships.",
    content: "An orientation session will be held for students interested in applying for city-funded scholarships for college and vocational courses. Representatives from the City Education Department will discuss eligibility and application requirements. Parents are encouraged to attend.",
    time: "2:00 PM – 4:00 PM", 
    date: "2025-12-10" 
  },
  { 
    category: "Festival", 
    title: "New Year Celebration", 
    location: "City Plaza", 
    description: "Welcome the new year!",
    content: "Ring in the New Year with a spectacular fireworks display and live music at the City Plaza! The event will feature local bands, food stalls, and a countdown to midnight. Gates open at 8:00 PM.",
    time: "8:00 PM – 1:00 AM", 
    date: "2026-01-01" 
  }
];

// ---------- Reactive States ----------
// We include a dummy searchQuery just in case the Input component is added later
const searchQuery = ref(""); 
const selectedFilter = ref("All");
const isFilterOpen = ref(false);
const isYearDropdownOpen = ref(false);

// Dialog state for event details
const isEventDialogOpen = ref(false);
const selectedEvent = ref(null);

// ---------- Compute Dynamic Categories ----------
const FILTER_OPTIONS = computed(() => {
  const categories = events.map(e => e.category);
  return ["All", ...new Set(categories)];
});

// ---------- Filtered Events (Using Category Filter only, as per the current script) ----------
const filteredEvents = computed(() => {
  return events.filter(e => {
    // If a search bar were present, you'd add: e.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesFilter = selectedFilter.value === "All" || e.category === selectedFilter.value;
    return matchesFilter;
  });
});

// ---------- Months Array ----------
const months = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];

// ---------- Dashboard Stats (Re-integrated) ----------
const stats = [
  {
    title: "Total Events",
    value: {
      count: events.length,
      thisWeek: 9, // Dummy data
      lastWeek: 10 // Dummy data
    },
    icon: ClipboardList, // Lucide Component
    bg: "bg-blue-400/40",
    textColor: "text-blue-500",
    footer: `+${9 - 10} from last week`
  },
  {
    title: "Upcoming",
    value: {
      count: events.filter(e => new Date(e.date) >= new Date()).length,
      thisWeek: 12, // Dummy data
      lastWeek: 15 // Dummy data
    },
    icon: Clock, // Lucide Component
    bg: "bg-yellow-400/40",
    textColor: "text-yellow-500",
    footer: `-${15 - 12} from last week`
  },
  {
    title: "Past",
    value: {
      count: events.filter(e => new Date(e.date) < new Date()).length,
      thisWeek: 8, // Dummy data
      lastWeek: 6 // Dummy data
    },
    icon: CircleDot, // Lucide Component
    bg: "bg-orange-400/40",
    textColor: "text-orange-500",
    footer: `+${8 - 6} updated this week`
  },
];


// ---------- Available Years ----------
const availableYears = computed(() => {
  const years = events.map(e => new Date(e.date).getFullYear());
  // Use current year if no events exist
  if (years.length === 0) return [new Date().getFullYear()]; 
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

// ---------- Modal Method (Passed to EventsCard via prop) ----------
const openEventDialog = (event) => {
  selectedEvent.value = event;
  isEventDialogOpen.value = true;
};
</script>

<template>
  <div class="space-y-8 p-4 md:p-6 lg:p-8">
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <DashboardStatsCard v-for="stat in stats" :key="stat.title" :stat="stat"/>
    </div>
    
    <div class="flex justify-end mb-4 pt-4">
      
      <div class="relative w-full md:w-auto">
        <Button
          @click="isFilterOpen = !isFilterOpen"
          class="h-11 px-6 flex items-center justify-between gap-3 rounded-full border-2 transition-all duration-200 shadow-sm w-full"
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

        <div v-if="isFilterOpen" class="absolute mt-2 w-full md:w-48 bg-white shadow-xl rounded-xl border border-gray-100 p-2 z-50 right-0">
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
    </div>
    
    <div class="flex flex-col items-center gap-2">

      <div class="relative">
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

      <div class="flex items-center gap-6 mt-1">
        <Button 
          @click="prevMonth" 
          class="p-2 rounded-full bg-white text-green-600 hover:bg-green-50 transition-colors shadow-md border-2 border-green-100"
          aria-label="Previous Month"
        >
          <ChevronLeft size="24" />
        </Button>

        <p class="text-3xl font-extrabold text-gray-900">{{ months[currentMonthIndex] }}</p>

        <Button 
          @click="nextMonth" 
          class="p-2 rounded-full bg-white text-green-600 hover:bg-green-50 transition-colors shadow-md border-2 border-green-100"
          aria-label="Next Month"
        >
          <ChevronRight size="24" />
        </Button>
      </div>
    </div>
    
    <hr class="border-gray-200 mt-4" />

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

    <Dialog v-model:open="isEventDialogOpen">
      <DialogContent class="max-w-2xl max-h-[80vh] overflow-y-auto">
        
        <DialogHeader>
          <div class="space-y-2">
            <DialogTitle class="text-2xl font-bold text-gray-900">
              {{ selectedEvent?.title }}
            </DialogTitle>
            
            <div class="flex flex-col gap-1">
              <div class="flex items-center text-sm text-gray-700">
                <Calendar class="size-4 mr-2 text-green-600" />
                <span>{{ selectedEvent?.date }}</span>
              </div>
              <div class="flex items-center text-sm text-gray-700">
                <Clock class="size-4 mr-2 text-green-600" />
                <span>{{ selectedEvent?.time }}</span>
              </div>
            </div>
            
            <div class="text-sm text-green-600 font-bold pt-2">
              {{ selectedEvent?.category }}
            </div>
          </div>
        </DialogHeader>
        
        <div class="space-y-4">
          <DialogDescription class="text-base text-gray-700 leading-relaxed">
            
            <p class="font-semibold flex items-center gap-2 mb-1 text-gray-900">
              <MapPin class="size-4 text-green-600" />
              Location
            </p>
            <p class="mb-4">{{ selectedEvent?.location }}</p>
            
            <p class="font-semibold text-gray-900">Full Details</p>
            <p>{{ selectedEvent?.content }}</p> 
          </DialogDescription>
          
          <div class="flex gap-2 pt-4 border-t">
            <Button
              class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white transition-colors"
              @click="isEventDialogOpen = false"
            >
              Close
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  </div>
</template>