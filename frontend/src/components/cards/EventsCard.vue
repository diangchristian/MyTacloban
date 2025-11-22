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
    Clock,
    MapPin
    
} from "lucide-vue-next"
import Button from "../ui/button/Button.vue";

</script>


<template>
    <Card class="m-0 border-l-4 border-green-400">
        <CardHeader>
            <CardTitle class="text-md">
                <div class=" mb-2">
                    <span class="bg-green-500/15 px-2 py-1 text-xs rounded-lg text-green-700">Health</span>                  
                </div>
                Free Medical Mission
                <div class="flex text-gray-600 mt-2 items-center gap-2">
                    <MapPin class="size-5"/> 
                    <p class="text-sm font-light">Barangay 50 Covered Court</p>
                </div>
            </CardTitle>
            <CardDescription class="mt-2">
                Water service will be temporarily interrupted on November 20, 2025, from 8:00 AM to 5:00 PM in District 1 for maintenance work.
            </CardDescription>
        </CardHeader>
        <CardContent class="-mt-1">
            <div class="flex text-gray-600 mt-2 items-center gap-2">
                    <Clock class="size-4"/> 
                    <p class="text-sm font-light">8:00 AM - 3:00 PM</p>
                </div>
            <Button variant="outline" class="cursor-pointer mt-4 w-full" >Read More</Button>
        </CardContent>
    </Card>
</template>


