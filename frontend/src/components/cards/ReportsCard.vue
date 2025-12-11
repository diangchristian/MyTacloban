<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { defineProps } from "vue";
import { 
    MapPin 
} from "lucide-vue-next"

const props = defineProps({
  report: Object
  
})

const emit = defineEmits(['view'])
</script>

<template>
   <Card class="">
  <CardHeader>
    <CardTitle>
      <div class="flex items-center gap-2 w-full mb-2">
        <p class="shrink-0">#{{ report.id }}</p>
        <span
          :class="{
            'bg-yellow-100 text-yellow-800': report.status === 'pending',
            'bg-blue-100 text-blue-800': report.status === 'in_progress',
            'bg-green-100 text-green-800': report.status === 'resolved'
          }"
          class="px-2 py-1 rounded-full text-xs font-semibold flex-shrink-0"
        >
          {{ report.status }}
        </span>
      </div>

      <!-- Title with truncation -->
      <p class="font-semibold text-md break-words" title="{{ report.title }}">
        {{ report.title }}
      </p>

      <!-- Location -->
      <div class="flex text-gray-600 mt-2 items-center gap-2">
        <MapPin class="size-5"/>
        <p class="text-sm font-light truncate" title="Barangay 50 Covered Court">
          Barangay 50 Covered Court
        </p>
      </div>
    </CardTitle>

    <!-- Description with multi-line truncation -->
    <CardDescription class="mt-2 line-clamp-3 text-sm text-gray-700">
      {{ report.description }}
    </CardDescription>
  </CardHeader>

  <CardContent class="mt-auto">
    <Button variant="outline" size="sm" class="cursor-pointer text-xs" @click="emit('view', report)">
      View Details
    </Button>
  </CardContent>
</Card>

</template>

<!-- 
<style scoped>
*{
  border: 1px solid red
}</style> -->