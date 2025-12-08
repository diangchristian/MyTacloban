<script setup>
import DashboardStatsCard from "@/components/cards/DashboardStatsCard.vue";
import AnnouncementCard from "@/components/cards/AnnouncementCard.vue";
import {
  ClipboardList,
  Clock,
  CircleDot,
  CircleCheckBig,
  Users,
} from "lucide-vue-next";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import { RouterLink } from "vue-router";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

const stats = [
  {
    title: "Total Announcements",
    value: {
      count: 102,
      thisWeek: 9,
      lastWeek: 10,
    },
    icon: ClipboardList,
    bg: "bg-blue-400/40",
    textColor: "text-blue-500",
    footer: `+${9 - 10} from last week`,
  },
  {
    title: "Posted",
    value: {
      count: 34,
      thisWeek: 12,
      lastWeek: 15,
    },
    icon: Clock,
    bg: "bg-yellow-400/40",
    textColor: "text-yellow-500",
    footer: `-${15 - 12} from last week`,
  },
  {
    title: "Draft",
    value: {
      count: 18,
      thisWeek: 8,
      lastWeek: 6,
    },
    icon: CircleDot,
    bg: "bg-orange-400/40",
    textColor: "text-orange-500",
    footer: `+${8 - 6} updated this week`,
  },
];
</script>

<template>
  <div class="w-full mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <DashboardStatsCard
        v-for="stat in stats"
        :key="stat.title"
        :stat="stat"
      />
    </div>

    <div class="w-full flex flex-col lg:flex-row gap-4 sm:items-center mt-8">
      <Input placeholder="Search reports" class="w-full max-w-md bg-white" />
      <Button>
         Search
      </Button>
      <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <Label for="" class="">Category</Label>
              <Select>
                <SelectTrigger class="w-full mt-2">
                  <SelectValue placeholder="Select a status" class="w-full" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="in_progress"> Emergency </SelectItem>
                  <SelectItem value="assigned"> Community Event </SelectItem>
                  <SelectItem value="resolved"> Notice </SelectItem>
                </SelectContent>
              </Select>
          </div>
          <div class="flex items-center gap-2">
            <Label for="" class="">Date</Label>
              <Select>
                <SelectTrigger class="w-full mt-2">
                  <SelectValue placeholder="Select a status" class="w-full" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="in_progress"> Today </SelectItem>
                  <SelectItem value="assigned"> This Week </SelectItem>
                  <SelectItem value="resolved"> This Month </SelectItem>
                </SelectContent>
              </Select>
          </div>
      </div>
    </div>
    <div class="mt-4">
      <Button asChild>
        <RouterLink to="/admin/announcements/create">+ Add</RouterLink>
      </Button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">

      <!-- Pwede didi ye if mag v-for kit add computed kanan user role -->
      <AnnouncementCard role="user" />
      <AnnouncementCard role="admin" />
      <AnnouncementCard role="admin" />
      <AnnouncementCard role="admin" />
      <AnnouncementCard role="admin" />
      <AnnouncementCard 
      role="admin" />

      <!-- SAMPLE V-FOR -->

      <!-- an add lang computed import para userRole para ig pass ha announcement card to get whether user or admin -->
       
       <!-- <AnnouncementCard 
       v-for="announcement in announcements" 
       :key="announcement.id" 
       :role="userRole" 
       @readMore="readmorehandler"
       @edit="edithandler"
       @delete="deletehandler"
       /> -->
    </div>
  </div>
</template>
