<script setup>
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";
import DashboardCard from "@/components/user/DashboardCard.vue";
import AnnouncementCard from "@/components/AnnouncementCard.vue";
import EventsCard from "@/components/EventsCard.vue";
import HighlightsCard from "@/components/HighlightsCard.vue"


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
        <h1 class="text-2xl">Upcoming Events</h1>
        <div class="grid  grid-cols-1  md:grid-cols-3   lg:grid-cols-4 space-x-4 space-y-4 gap-4 mt-4">
          <EventsCard/>
          <EventsCard/>
          <EventsCard/>
          <EventsCard/>

        </div>
      </div>
    </div>
   

  </main>
</template>