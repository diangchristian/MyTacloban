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
import Button from "./ui/button/Button.vue";

// Accept ONE object prop
const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Card class="m-0 border-l-4 border-green-400">
    <CardHeader>
      <CardTitle class="text-md">
        <!-- Category -->
        <div class="mb-2">
          <span class="bg-green-500/15 px-2 py-1 text-xs rounded-lg text-green-700">
            {{ event.category }}
          </span>
        </div>

        <!-- Title -->
        {{ event.title }}

        <!-- Location -->
        <div class="flex text-gray-600 mt-2 items-center gap-2">
          <MapPin class="size-5" />
          <p class="text-sm font-light">{{ event.location }}</p>
        </div>
      </CardTitle>

      <!-- Description -->
      <CardDescription class="mt-2">
        {{ event.description }}
      </CardDescription>
    </CardHeader>

    <CardContent class="-mt-1 space-y-2">
      <!-- Date -->
      <div class="flex text-gray-600 items-center gap-2">
        <Calendar class="size-4" />
        <p class="text-sm font-light">{{ event.date }}</p>
      </div>

      <!-- Time -->
      <div class="flex text-gray-600 items-center gap-2">
        <Clock class="size-4" />
        <p class="text-sm font-light">{{ event.time }}</p>
      </div>

      <Button variant="outline" class="cursor-pointer mt-4 w-full">
        Read More
      </Button>
    </CardContent>
  </Card>
</template>
