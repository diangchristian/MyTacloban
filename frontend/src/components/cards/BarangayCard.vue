<script setup>
import { defineProps } from 'vue';
import { ref } from 'vue';

import {
  Card,
  CardHeader,
  CardContent,
  CardFooter,
} from '@/components/ui/card'

import { Button } from '../ui/button';

import {
  MapPinIcon,
  UsersIcon,
  HomeIcon,
  MapIcon,
  UserIcon,
  PhoneIcon,
  MailIcon
} from 'lucide-vue-next'

const selectedBarangay = ref(null)

function viewDetails(brgy) {
  selectedBarangay.value = brgy
}

const props = defineProps({
  barangay: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <Card class="w-full max-w-sm mx-auto overflow-hidden border border-gray-100 shadow-lg rounded-xl">

    <!-- Header -->
    <CardHeader class="bg-primary text-primary-foreground p-4 rounded-tr-lg rounded-tl-lg">
      <h2 class="text-lg font-bold mb-1">{{ barangay.name }}</h2>
      <div class="flex items-center gap-1 text-sm opacity-90">
        <MapPinIcon class="w-4 h-4" />
        <span>{{ barangay.coordinates }}</span>
      </div>
    </CardHeader>

    <!-- Stats -->
    <CardContent class="bg-gray-50 p-4">
      <div class="grid grid-cols-3 gap-4">

        <!-- Population -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-blue-600 mb-1">
            <UsersIcon class="w-4 h-4" />
            <span class="font-bold text-lg">{{ barangay.population }}</span>
          </div>
          <span class="text-xs text-gray-500">Population</span>
        </div>

        <!-- Households -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-purple-600 mb-1">
            <HomeIcon class="w-4 h-4" />
            <span class="font-bold text-lg">{{ barangay.households }}</span>
          </div>
          <span class="text-xs text-gray-500">Households</span>
        </div>

        <!-- Area -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-orange-600 mb-1">
            <MapIcon class="w-4 h-4" />
            <span class="font-bold text-lg">{{ barangay.area }} km²</span>
          </div>
          <span class="text-xs text-gray-500">Area Coverage</span>
        </div>

      </div>
    </CardContent>

    <!-- Contact Info -->
    <CardContent class="p-4 space-y-3">

      <!-- Contact Person -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Contact Person</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <UserIcon class="w-4 h-4 text-gray-400" />
          {{ barangay.contact_person }}
        </div>
      </div>

      <!-- Contact Number -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Contact Number</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <PhoneIcon class="w-4 h-4 text-gray-400" />
          {{ barangay.contact_no }}
        </div>
      </div>

      <!-- Email -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Email</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <MailIcon class="w-4 h-4 text-gray-400" />
          {{ barangay.email }}
        </div>
      </div>

    </CardContent>

    <!-- Footer -->
    <CardFooter class="p-4 mt-auto">
      <Button
        @click="$emit('viewDetails', barangay)"
        class="w-full bg-primary text-primary-foreground py-3 rounded-lg hover:opacity-90 transition shadow-md hover:cursor-pointer">
        View Details
      </Button>
    </CardFooter>

  </Card>
</template>

<!-- 
<style scoped>
*{
  border: 1px solid red
}
</style> -->