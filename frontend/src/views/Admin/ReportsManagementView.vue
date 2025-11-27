<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import ReportsCard from "@/components/cards/ReportsCard.vue";
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import PaginatedReports from "@/components/PaginatedReports.vue";


const reports = [
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#4567',
    'category': 'flooding',
    'title':'Flooding in barangay 95-caibaanan',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Barangay 95-A Caibaan',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  },
  {
    'id': '#1234',
    'category': 'street light',
    'title':'Scheduled Water Interruption - District 1',
    'description': 'Severe flooding during heavy rain. Water accumulates up to knee level, blocking the road.',
    'location': 'Main Road, Barangay 1',
    'date': 'November 19, 2025'
  }
]

</script>

<template>
  <main>
    <div class="w-full h-screen">
      <div
        class="w-full flex flex-col lg:flex-row gap-4 sm:items-center justify-center">
        <Input placeholder="Search reports" class="w-full max-w-md" />
          <div class="flex flex-wrap gap-2">
            <Button>All Reports (9)</Button>
            <Button variant="outline">Pending (12)</Button>
            <Button variant="outline">In Progress (3)</Button>
          </div>
      </div>
      <PaginatedReports/>
      <!-- <div class="grid grid-cols-4 mt-4 gap-4 h-full">
        <ReportsCard
            v-for="r in reports"
            :key="r.id"
            :report="r"
          />
      </div> -->
      <div class="mt-4"></div>
    </div>
  </main>
</template>
