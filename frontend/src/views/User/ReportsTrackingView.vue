<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/ReportsCard.vue";
import ReportsDetails from "@/components/ReportsDetails.vue";
import ReportDetailsDrawer from "@/components/ReportDetailsDrawer.vue";
import { defineProps } from "vue";
import { useMediaQuery } from '@vueuse/core'

const isMobile = useMediaQuery('(max-width: 768px)')


</script>

<template>
  <main class="">
    <div class="max-w-7xl mx-auto rounded-xl mt-4">

      <!-- Filters -->
      <div class="max-w-3xl gap-4 flex flex-wrap sm:flex-nowrap">
        <Input placeholder="Search reports" class="sm:w-1/2" />
        <div class="flex gap-2">
          <Button>All Reports(9)</Button>
          <Button variant="outline">Pending (12)</Button>
          <Button variant="outline">In Progress(3)</Button>
        </div>
      </div>

      <!-- Content area -->
      <!-- h-[calc(100vh-150px)] -->
      <div class="flex flex-col md:flex-row w-full mt-4 gap-4 overflow-x-hidden">
      <!-- Left Column -->
      <div
        class="bg-white rounded-lg flex flex-col flex-1 overflow-y-auto p-4 gap-4
              h-[calc(100vh-200px)] md:h-[calc(100vh-150px)] scrollable"
      >
        <ReportsCard />
        <ReportsCard />
        <ReportsCard />
        <ReportsCard />
      </div>

      <!-- Right Column -->
      <div class="flex-2 hidden md:block p-4">
        <div class="w-full md:w-3/4">
          <ReportsDetails />
          
        </div>
      </div>
    </div>
      <ReportDetailsDrawer/>
    </div>
  </main>
</template>


<style scoped>
.scrollable::-webkit-scrollbar {
  width: 4px;
}
.scrollable::-webkit-scrollbar-thumb {
  background: #58F09F;
  border-radius: 10px;
}
.scrollable::-webkit-scrollbar-track {
  background: #f5f5f5;
}
</style>