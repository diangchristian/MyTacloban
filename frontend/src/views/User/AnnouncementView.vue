<script setup>
import { ref } from 'vue';
import { useAuthStore } from "@/stores/auth";

import images from "@/assets/images/news-sample.png";

import HighlightsCard from "@/components/cards/HighlightsCard.vue";
import NewsCard from "@/components/cards/NewsCard.vue";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

const { logout } = useAuthStore();
const { user } = useAuthStore();

// Dialog state
const isNewsDialogOpen = ref(false);
const isHighlightsDialogOpen = ref(false);
const selectedItem = ref(null);

// Sample data - replace with your actual data
const highlightsData = ref([
  {
    id: 1,
    title: "One way traffic along Imelda Ave. — Avenida Veteranos St.",
    description: "Due to the motorcade to honor the fiesta of Tacloban City, Traffic from Imelda Ave. will be redirected in a one way commodity through Avenida Veteranos.",
    time: "2 hours ago",
    date: "December 5, 2024",
    content: "Due to the motorcade to honor the fiesta of Tacloban City, Traffic from Imelda Ave. will be redirected in a one way commodity through Avenida Veteranos. Please be guided accordingly. The traffic rerouting will be in effect from 8:00 AM to 6:00 PM. Motorists are advised to take alternative routes and expect delays in the affected areas."
  },
  {
    id: 2,
    title: "Community Clean-up Drive Success",
    description: "Over 200 volunteers participated in the city-wide clean-up drive.",
    time: "5 hours ago",
    date: "December 5, 2024",
    content: "The community clean-up drive held last weekend was a resounding success with over 200 volunteers from different barangays participating. The event collected over 2 tons of waste materials and recyclables from various areas in the city."
  },
  {
    id: 3,
    title: "New Public Library Opening",
    description: "Grand opening ceremony scheduled for next week.",
    time: "1 day ago",
    date: "December 4, 2024",
    content: "The new public library will open its doors next week with a grand ceremony. The facility features modern amenities, study areas, and a vast collection of books and digital resources."
  },
  {
    id: 4,
    title: "Health Services Expansion",
    description: "Additional health centers to open in rural areas.",
    time: "2 days ago",
    date: "December 3, 2024",
    content: "The city government announces the expansion of health services with new centers opening in rural barangays to provide better access to healthcare for all residents."
  },
  {
    id: 5,
    title: "Free Skills Training Program",
    description: "Registration now open for vocational training courses.",
    time: "3 days ago",
    date: "December 2, 2024",
    content: "The LGU is offering free skills training programs for residents. Courses include culinary arts, computer literacy, and welding. Registration is open until December 15."
  },
  {
    id: 6,
    title: "Road Repair Schedule",
    description: "Major roads to undergo maintenance next month.",
    time: "4 days ago",
    date: "December 1, 2024",
    content: "Several major roads will undergo repair and maintenance starting next month. Motorists are advised to follow traffic advisories and take alternative routes during the construction period."
  }
]);

const newsData = ref([
  {
    id: 1,
    title: "UWAN NOW A SUPER TYPHOON, SIGNAL NO. 2 UP IN TACLOBAN",
    date: "December 6, 2024",
    category: "Weather Alert",
    image: images,
    summary: "Typhoon Uwan to make landfall, residents advised to evacuate as Signal No. 2 has been raised over Tacloban City and several municipalities",
    content: "The Philippine Atmospheric, Geophysical and Astronomical Services Administration (PAGASA) has upgraded Typhoon Uwan to super typhoon category as it continues to intensify over the Philippine Sea. Signal No. 2 has been raised over Tacloban City and several municipalities in Leyte province. Residents are advised to take necessary precautions and monitor updates from local authorities. Classes at all levels have been suspended, and evacuation centers have been prepared for affected families. The typhoon is expected to bring heavy rainfall and strong winds in the coming days."
  },
  {
    id: 2,
    title: "City Government Launches Digital Services Portal",
    date: "December 5, 2024",
    category: "Technology",
    image: images,
    summary: "Citizens can now access government services online through the new digital portal.",
    content: "The city government has officially launched its new digital services portal, allowing residents to access various government services online. The portal includes features for permit applications, tax payments, and document requests."
  },
  {
    id: 3,
    title: "Tourism Recovery Shows Strong Growth",
    date: "December 4, 2024",
    category: "Tourism",
    image: images,
    summary: "Tourist arrivals increase by 40% compared to last year.",
    content: "The city's tourism industry shows remarkable recovery with a 40% increase in tourist arrivals compared to the same period last year. Local businesses are optimistic about the continued growth."
  },
  {
    id: 4,
    title: "Tourism Recovery Shows Strong Growth",
    date: "December 4, 2024",
    category: "Tourism",
    image: images,
    summary: "Tourist arrivals increase by 40% compared to last year.",
    content: "The city's tourism industry shows remarkable recovery with a 40% increase in tourist arrivals compared to the same period last year. Local businesses are optimistic about the continued growth."
  },
  {
    id: 5,
    title: "Tourism Recovery Shows Strong Growth",
    date: "December 4, 2024",
    category: "Tourism",
    image: images,
    summary: "Tourist arrivals increase by 40% compared to last year.",
    content: "The city's tourism industry shows remarkable recovery with a 40% increase in tourist arrivals compared to the same period last year. Local businesses are optimistic about the continued growth."
  }
]);

const openNewsDialog = (news) => {
  selectedItem.value = news;
  isNewsDialogOpen.value = true;
};

const openHighlightsDialog = (highlight) => {
  selectedItem.value = highlight;
  isHighlightsDialogOpen.value = true;
};
</script>

<template>
<main>
    <div class="w-full mx-auto">
      <div class="w-full h-52 overflow-hidden rounded-md relative">
        <img
          src="@/assets/images/user-bg.jpg"
          class="w-full h-full object-cover"
          alt=""
        >
        <div class="w-full h-full bg-gradient-to-l via-green to-primary/70 absolute top-0 left-0 z-2 flex flex-col justify-center px-8 text-white">
            <h1 class="text-5xl font-bold">ANNOUNCEMENTS</h1>
            <p class="font-semibold">Recent updates & announcements from the LGU</p>
        </div>
      </div>

      <!-- Highlights Section -->
      <div class="mt-10">
        <h1 class="text-2xl font-semibold mb-4">Highlights</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <HighlightsCard 
              v-for="highlight in highlightsData" 
              :key="highlight.id"
              :highlight="highlight"
              @view-details="openHighlightsDialog(highlight)"
            />
        </div>
      </div>

      <!-- Latest News Section -->
      <div class="mt-10">
        <div class="text-2xl font-semibold mb-4">Latest News</div>
        
        <!-- Featured News -->
        <div class="mt-4 flex flex-col lg:flex-row p-5 pb-10 border-b-4 border-b-gray-300 mb-6 cursor-pointer hover:bg-gray-50 transition-colors rounded-lg" 
             @click="openNewsDialog(newsData[0])">
          <div class="flex-1 mt-5 lg:mr-10 border-t-4 border-t-[#54D591] pt-6 pl-4">
            <div class="text-4xl lg:text-6xl font-bold mb-6">
              {{ newsData[0].title }}
            </div>
            <div class="text-lg lg:text-xl text-gray-700">
              {{ newsData[0].summary }}
            </div>
          </div>
          <div class="mt-5 lg:mt-5 flex-shrink-0 lg:w-1/3">
            <img 
              :src="newsData[0].image" 
              class="w-full h-64 object-cover rounded-lg"
              alt="">
          </div>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <NewsCard 
              v-for="news in newsData.slice(1)" 
              :key="news.id"
              :news="news"
              @view-details="openNewsDialog(news)"
            />
        </div>
      </div>
    </div>

    <!-- News Dialog -->
    <Dialog v-model:open="isNewsDialogOpen">
      <DialogContent class="max-w-3xl max-h-[80vh] overflow-y-auto">
        <DialogHeader>
          <div class="space-y-2">
            <div class="text-sm text-green-600 font-semibold">
              {{ selectedItem?.category }}
            </div>
            <DialogTitle class="text-3xl font-bold">
              {{ selectedItem?.title }}
            </DialogTitle>
            <div class="text-sm text-gray-500">
              {{ selectedItem?.date }}
            </div>
          </div>
        </DialogHeader>
        <div class="space-y-4">
          <img 
            v-if="selectedItem?.image"
            :src="selectedItem.image" 
            :alt="selectedItem?.title"
            class="w-full h-64 object-cover rounded-lg"
          />
          <DialogDescription class="text-base text-gray-700 leading-relaxed">
            {{ selectedItem?.content }}
          </DialogDescription>
          <div class="flex gap-2 pt-4 border-t">
            <button 
              class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors cursor-pointer"
              @click="isNewsDialogOpen = false"
            >
              Close
            </button>
            <button 
              class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors cursor-pointer"
              @click="window.open(`/news/${selectedItem?.id}`, '_blank')"
            >
              Open in New Tab
            </button>
          </div>
        </div>
      </DialogContent>
    </Dialog>

    <!-- Highlights Dialog -->
    <Dialog v-model:open="isHighlightsDialogOpen">
      <DialogContent class="max-w-2xl max-h-[80vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle class="text-2xl font-bold">
            {{ selectedItem?.title }}
          </DialogTitle>
          <div class="text-sm text-gray-500">
            {{ selectedItem?.date }}
          </div>
        </DialogHeader>
        <div class="space-y-4">
          <DialogDescription class="text-base text-gray-700 leading-relaxed">
            {{ selectedItem?.content }}
          </DialogDescription>
          <div class="flex gap-2 pt-4 border-t">
            <button 
              class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors cursor-pointer"
              @click="isHighlightsDialogOpen = false"
            >
              Close
            </button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
</main>
</template>