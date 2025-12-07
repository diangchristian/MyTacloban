<script setup>
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import { defineProps } from "vue"; 
import { Clock, MapPin, Calendar } from "lucide-vue-next";
import Button from "../ui/button/Button.vue";

// Update the event prop structure to clearly define bannerImageUrl
const props = defineProps({
  event: {
    type: Object,
    required: true,
    // Add bannerImageUrl to the shape definition (optional for simple usage)
    validator: (value) => {
        return ['title', 'location', 'date', 'time', 'category', 'description'].every(key => key in value);
    }
  },
  // The function passed from the parent (openEventDialog)
  onReadMore: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  <Card class="m-0 border-l-4 border-green-400">
    
    <div v-if="event.bannerImageUrl" class="relative overflow-hidden h-40">
      <img
        :src="event.bannerImageUrl"
        :alt="event.title"
        class="h-full w-full object-cover"
      />
    </div>
    <div v-else class="h-20 bg-green-50/50 flex items-center justify-center">
        <span class="text-sm text-gray-500 italic">No Banner Image</span>
    </div>
    <CardHeader>
      <CardTitle class="text-md">
        <div class="mb-2">
          <span class="bg-green-500/15 px-2 py-1 text-xs rounded-lg text-green-700">
            {{ event.category }}
          </span>
        </div>

        {{ event.title }}

        <div class="flex text-gray-600 mt-2 items-center gap-2">
          <MapPin class="size-5" />
          <p class="text-sm font-light">{{ event.location }}</p>
        </div>
      </CardTitle>

      <CardDescription class="mt-2 line-clamp-2"> 
        {{ event.description }}
      </CardDescription>
    </CardHeader>

    <CardContent class="-mt-1 space-y-2">
      <div class="flex text-gray-600 items-center gap-2">
        <Calendar class="size-4" />
        <p class="text-sm font-light">{{ event.date }}</p>
      </div>

      <div class="flex text-gray-600 items-center gap-2">
        <Clock class="size-4" />
        <p class="text-sm font-light">{{ event.time }}</p>
      </div>

      <Button 
        variant="outline" 
        class="cursor-pointer mt-4 w-full"
        @click="onReadMore(event)"
      >
        Read More
      </Button>
    </CardContent>
  </Card>
</template>