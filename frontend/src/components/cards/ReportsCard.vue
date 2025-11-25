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


const props = defineProps({
  report: Object
  
})

const emit = defineEmits(['view'])
</script>

<template>
     <Card class="">
            <CardHeader>
              <CardTitle class="text-md">
                <div class="flex items-center justify-between w-full mb-2">
                    <p>{{report.id }}</p>
                  <span
                    class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive"
                    >{{ report.category }}</span
                  >
                </div>
                {{ report.title }}
              </CardTitle>
              <CardDescription class="mt-2">
               {{ report.description }}
              </CardDescription>
            </CardHeader>

            <CardContent class="-mt-1">
              <Button variant="outline" class="cursor-pointer" @click="emit('view', report)">
                View Details
              </Button>
            </CardContent>
          </Card>
</template>