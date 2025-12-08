<script setup>
import { defineProps } from 'vue';

import {
  Card,
  CardHeader,
  CardContent,
  CardFooter,
} from '@/components/ui/card'

import { Button } from '../ui/button';

import {
  UserIcon,
  PhoneIcon,
  MailIcon
} from 'lucide-vue-next'

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  captain: {
    type: String,
    required: true
  },
  phone: {
    type: String,
    required: true
  },
  email: {
    type: String,
    required: true
  }
})
</script>

<template>
  <Card class="w-full max-w-sm mx-auto overflow-hidden border border-gray-100 shadow-lg rounded-xl">

    <!-- Header -->
    <CardHeader class="bg-primary text-primary-foreground p-4 rounded-tr-lg rounded-tl-lg">
      <h2 class="text-lg font-bold mb-1">{{ name }}</h2>
    </CardHeader>

    <!-- Contact Info -->
    <CardContent class="p-4 space-y-3">

      <!-- Barangay Captain -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Barangay Captain</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <UserIcon class="w-4 h-4 text-gray-400" />
          {{ captain }}
        </div>
      </div>

      <!-- Contact Number -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Contact Number</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <PhoneIcon class="w-4 h-4 text-gray-400" />
          {{ phone }}
        </div>
      </div>

      <!-- Email -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Email</div>
        <div class="flex items-center gap-2 text-sm text-gray-700 break-all">
          <MailIcon class="w-4 h-4 text-gray-400 flex-shrink-0" />
          {{ email }}
        </div>
      </div>

    </CardContent>

    <!-- Footer -->
    <CardFooter class="p-4 mt-auto">
      <Button
        @click="$emit('viewDetails', { name, captain, phone, email })"
        class="w-full bg-primary text-primary-foreground py-3 rounded-lg hover:opacity-90 transition shadow-md hover:cursor-pointer">
        View Details
      </Button>
    </CardFooter>

  </Card>
</template>