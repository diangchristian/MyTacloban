<script setup>
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import { Clock, MapPin, Calendar } from "lucide-vue-next";
import Button from "../ui/button/Button.vue";

const props = defineProps({
  event: {
    type: Object,
    required: true,

    validator: (value) => {
      return ['title', 'location', 'event_date', 'event_time', 'description', 'content', 'image']
        .every(key => key in value);
    }
  },

  onReadMore: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  <Card class="m-0 border-l-4 border-green-400">

    <!-- Event image -->
    <div v-if="event.image" class="relative overflow-hidden h-40">
      <img
        :src="event.image"
        :alt="event.title"
        class="h-full w-full object-cover"
      />
    </div>

    <div v-else class="h-20 bg-green-50/50 flex items-center justify-center">
      <span class="text-sm text-gray-500 italic">No Image Available</span>
    </div>

    <CardHeader>
      <CardTitle class="text-md">

        <!-- Title -->
        {{ event.title }}

        <div class="flex text-gray-600 mt-2 items-center gap-2">
          <MapPin class="size-5" />
          <p class="text-sm font-light">{{ event.location }}</p>
        </div>
      </CardTitle>

      <!-- Description -->
      <CardDescription class="mt-2 line-clamp-2">
        {{ event.description }}
      </CardDescription>
    </CardHeader>

    <CardContent class="-mt-1 space-y-2">

      <div class="flex text-gray-600 items-center gap-2">
        <Calendar class="size-4" />
        <p class="text-sm font-light">{{ event.event_date }}</p>
      </div>

      <div class="flex text-gray-600 items-center gap-2">
        <Clock class="size-4" />
        <p class="text-sm font-light">{{ event.event_time }}</p>
      </div>

      <Button 
        variant="outline" 
        class="cursor-pointer w-full mt-auto"
        @click="onReadMore(event)"
      >
        Read More
      </Button>
    </CardContent>
  </Card>
</template>
