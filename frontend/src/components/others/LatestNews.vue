<script setup>
import NewsCard from '@/components/cards/NewsCard.vue';
import { ref } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Skeleton } from "@/components/ui/skeleton";

import images from "@/assets/images/news-sample.png";
const isNewsDialogOpen = ref(false);
const selectedItem = ref(null);

const openNewsDialog = (news) => {
  selectedItem.value = news;
  isNewsDialogOpen.value = true;
};

const props = defineProps({
    latestNews: {
        type: Object
    }
})


</script>


<template>
    <div class="mt-10">
        <div class="text-2xl font-semibold mb-4">Latest News</div>
        
        <!-- Featured News -->
        <div class="mt-4 flex flex-col lg:flex-row p-5 pb-10 border-b-4 border-b-gray-300 mb-6 cursor-pointer hover:bg-gray-50 transition-colors rounded-lg" 
             @click="openNewsDialog(latestNews[0])">
          <div class="flex-1 mt-5 lg:mr-10 border-t-4 border-t-[#54D591] pt-6 pl-4">
            <div class="text-4xl lg:text-6xl font-bold mb-6">
              {{ latestNews[0]?.title  }}
            </div>
            <div class="text-lg lg:text-xl text-gray-700">
              {{ latestNews[0]?.body  }}
            </div>
          </div>
          <div class="mt-5 lg:mt-5 shrink-0 lg:w-1/3">
            <img 
              :src="images" 
              class="w-full h-64 object-cover rounded-lg"
              alt="">
          </div>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <NewsCard 
              v-for="news in latestNews.slice(1).filter(news => !news.isHighlight)" 
              :key="news.id"
              :news="news"
              @view-details="openNewsDialog(news)"
            />
           
        </div>
    </div>
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
            :src="images" 
            :alt="selectedItem?.title"
            class="w-full h-64 object-cover rounded-lg"
          />
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
</template>