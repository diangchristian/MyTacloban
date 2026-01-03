<script setup>
import { RouterLink  } from 'vue-router';
import { 
CalendarPlus,
ClipboardList,
NewspaperIcon,
Users
} from 'lucide-vue-next';
import {useAuthStore} from "@/stores/auth"



const authStore = useAuthStore()
const role = authStore.userRole === 'LGU_ADMIN' ? 'admin' : 'barangay'



const actions = [
    {
        title: 'Add New Event',
        icon: CalendarPlus,
        url: `/${role}/events-management`,
        color: 'bg-blue-100',
        textColor: 'text-blue-400'
    },
    {
        title: 'View All Reports',
        icon: ClipboardList,
        url: `/${role}/reports-management`,
        color: 'bg-green-100',
        textColor: 'text-green-400'
    },
    {
        title: 'Create Announcement',
        icon: NewspaperIcon,
        url: `/${role}/announcements/create`,
        color: 'bg-purple-100',
        textColor: 'text-purple-400'
    },
    {
        title: 'Manage Users',
        icon: Users,
        url: `/${role}/user-management`,
        color: 'bg-orange-100',
        textColor: 'text-orange-400'
    },
]





</script>

<template>
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
  
      <!-- Container with proper spacing -->
      <div class="space-y-4">
        <RouterLink
          v-for="action in actions"
          :key="action.title"
          :to="action.url"
          class="w-full flex items-center space-x-3 p-3 text-left border border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50"
        >
          <div class="p-2 rounded-lg" :class="action.color">
            <component :is="action.icon" class="size-5" :class="action.textColor"/>
          </div>
          <span class="text-sm font-medium text-gray-700">{{ action.title }}</span>
        </RouterLink>
      </div>
    </div>
  </template>