<script setup>
import { ref, onMounted} from 'vue';
import NewsCard from '@/components/cards/NewsCard.vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import LatestNews from '@/components/LatestNews.vue';
import {useAnnouncementStore} from "@/stores/announcements";
import { Skeleton } from "@/components/ui/skeleton";


const announcementStore = useAnnouncementStore()
const newsData = ref([])
const isLoading = ref(true)

// Dialog state
const isNewsDialogOpen = ref(false);
const selectedItem = ref(null);

const openNewsDialog = (news) => {
  selectedItem.value = news;
  isNewsDialogOpen.value = true;
};


onMounted(async () => {
  await announcementStore.getAnnouncement()
  newsData.value = announcementStore.announcements
  isLoading.value = false;

})

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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 " v-if="isLoading">
          <Skeleton   v-for="n in 4" :key="n" class="h-50 w-full bg-gray-200 rounded-md" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4"  v-else >    
            <NewsCard 
              v-for="news in newsData.filter(news => news.isHighlight )" 
              :key="news.id"
              :news="news"
              @view-details="openNewsDialog(news)"
            />

           
        </div>
      </div>

      <!-- Latest News Section -->
      <LatestNews :latestNews="newsData"/>
    </div>

    <!-- Highlights Dialog -->
    <Dialog v-model:open="isNewsDialogOpen">
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
            {{ selectedItem?.body }}
          </DialogDescription>
          <div class="flex gap-2 pt-4 border-t">
            <button 
              class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors cursor-pointer"
              @click="isNewsDialogOpen = false"
            >
              Close
            </button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
</main>
</template>