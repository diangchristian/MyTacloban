<script setup> 
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { defineProps, defineEmits } from "vue";
import { 
  ArrowRight
} from "lucide-vue-next"

import { Button } from "@/components/ui/button";

const props = defineProps({
  highlight: {
    type: Object,
    required: true,
    default: () => ({
      title: "One way traffic along Imelda Ave. — Avenida Veteranos St.",
      description: "Due to the motorcade to honor the fiesta of Tacloban City, Traffic from Imelda Ave. will be redirected in a one way commodity through Avenida Veteranos. Please be guided accordingly.",
      time: "2 hours ago",
      date: "December 6, 2024"
    })
  }
});

const emit = defineEmits(['view-details']);
</script>

<template>
    <Card class="m-2 bg-gradient-to-l via-green to-primary/70 hover:shadow-lg transition-shadow cursor-pointer h-full flex flex-col">
      <CardHeader class="flex-1">
        <div class="flex justify-between items-start mb-2">
          <p class="text-black-400 text-xs font-regular whitespace-nowrap">
            {{ highlight.time }}
          </p>
        </div>
        <CardTitle v-if="highlight.title" class="text-md font-bold mb-3 line-clamp-2">
          {{ highlight.title }}
        </CardTitle>
        <CardDescription class="text-[#000] line-clamp-3">
          {{ highlight.description }}
        </CardDescription>
      </CardHeader>
      <CardFooter class="flex justify-end">
        <Button 
          variant="ghost" 
          size="sm"
          @click="emit('view-details')"
          class="text-black hover:text-primary hover:bg-gray transition-colors cursor-pointer"
        >
          View Details
          <ArrowRight class="ml-2 h-4 w-4" />
        </Button>
      </CardFooter>
    </Card>
</template>