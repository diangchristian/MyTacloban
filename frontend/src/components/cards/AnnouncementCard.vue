<script setup> 
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { defineProps } from "vue";
import { 
ArrowRight
} from "lucide-vue-next"
import Button from "../ui/button/Button.vue";



</script>


<template>
    <Card class="m-0">
    <CardHeader>
        <CardTitle class="text-md">
            <div class="flex items-center justify-between w-full mb-2">
                <span class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive">Urgent</span>
                <p class="text-gray-400 text-xs font-regular">2 hours ago</p>

            </div>
            Scheduled Water Interruption - District 1
        </CardTitle>
        <CardDescription class="mt-2">
            Water service will be temporarily interrupted on November 20, 2025, from 8:00 AM to 5:00 PM in District 1 for maintenance work.
        </CardDescription>
    </CardHeader>
    <CardContent class="-mt-1">
       <Button variant="outline" class="cursor-pointer" >Read More</Button>
        
    </CardContent>
            
    </Card>
</template>