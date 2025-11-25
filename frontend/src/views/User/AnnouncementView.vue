<script setup>
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";
import DashboardCard from "@/components/user/DashboardCard.vue";
import AnnouncementCard from "@/components/cards/AnnouncementCard.vue";
import EventsCard from "@/components/cards/EventsCard.vue";
import HighlightsCard from "@/components/cards/HighlightsCard.vue"
import NewsCard from "@/components/cards/NewsCard.vue";


const { logout } = useAuthStore();
const { user } = useAuthStore();

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
        <div class="w-full h-full bg-gradient-to-l  via-green to-primary/70 absolute top-0 left-0 z-2 flex flex-col justify-center px-8 text-white">
            <h1 class="text-5xl font-bold">ANNOUNCEMENTS</h1>
            <p class="font-semibold">Recent updates & announcements from the LGU</p>
        </div>
      </div>

      <div class="mt-10">
        <h1 class="text-2xl">Highlights</h1>
        <div class="grid grid-cols-1 sm:grid-cols-3 space-x-4 space-y-4 gap-4 mt-4">
            <HighlightsCard/>
            <HighlightsCard/>
            <HighlightsCard/>
            <HighlightsCard/>
            <HighlightsCard/>
            <HighlightsCard/>
        </div>
      </div>

      <div class="mt-10">
        <div class="text-2xl">Latest News</div>
        <div class="mt-4 flex p-5 pb-10 border-b-5 border-b-gray/80">
          <div class="mt-5 mr-10 border-t-5 border-t-[#54D591] w-full h-auto flex flex-col pt-25 pl-15">
                <div class="text-7xl font-bold w-4/5">UWAN NOW A SUPER TYPHOON, SIGNAL NO. 2 UP IN TACLOBAN</div>
                <div class="text-2xl mt-10 w-5/6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus animi ducimus, a repellat rem commodi laboriosam beatae expedita autem voluptas laborum excepturi totam? Facere quam recusandae ad vel modi nam.</div>
          </div>
          <div class="mt-5">
            <img 
            src="@/assets/images/news-sample.png" 
            class=""
            alt="">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 space-x-4 space-y-4 gap-4 mt-4">
            <NewsCard/>
            <NewsCard/>
            <NewsCard/>
            <NewsCard/>
            <NewsCard/>
            <NewsCard/>

        </div>

      </div>
    </div>
   

  </main>
</template>