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
              <CardTitle class="text-md">
                <div class="flex items-center gap-2 w-full mb-2">
                    <p>#{{report.id }}</p>
                    <span
                      :class="{
                        'bg-yellow-100 text-yellow-800': report.status === 'pending',
                        'bg-blue-100 text-blue-800': report.status === 'in_progress',
                        'bg-green-100 text-green-800': report.status === 'resolved'
                      }"
                      class="px-2 py-1 rounded-full text-xs font-semibold"
                    >
                      {{ report.status }}
                    </span>
                  
                 
                </div>
                {{ report.title }}
                <div class="flex text-gray-600 mt-2 items-center gap-2">
                    <MapPin class="size-5"/> 
                    <p class="text-sm font-light">Barangay 50 Covered Court</p>
                </div>
              </CardTitle>
              <CardDescription class="mt-2">
               {{ report.description }}
              </CardDescription>
            </CardHeader>

            <CardContent class="-mt-1">
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