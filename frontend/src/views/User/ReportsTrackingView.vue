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
</script>

<template>
  <main>
    <div class="max-w-7xl mx-auto h-40 rounded-xl mt-4">
      <div class="max-w-3xl gap-4 flex flex-wrap sm:flex-nowrap">
        <Input placeholder="Search reports" class="sm:w-1/2" />
        <div class="flex gap-2">
          <Button>All Reports(9)</Button>
          <Button variant="outline">Pending (12)</Button>
          <Button variant="outline">In Progress(3)</Button>
        </div>
      </div>
      <div
        class="flex   w-full min-h-1/2 h-full mt-2 h-screen"
      >
        <div class="bg-white rounded-lg flex-1 border overflow-y-scroll  p-4">
            <Card class="m-0">
            <CardHeader>
              <CardTitle class="text-md">
                <div class="flex items-center justify-between w-full mb-2">
                    <p>#2847</p>
                  <span
                    class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive"
                    >Urgent</span
                  >
                </div>
                Scheduled Water Interruption - District 1
              </CardTitle>
              <CardDescription class="mt-2">
                Water service will be temporarily interrupted on November 20,
                2025, from 8:00 AM to 5:00 PM in District 1 for maintenance
                work.
              </CardDescription>
            </CardHeader>
            <CardContent class="-mt-1">
              <Button variant="outline" class="cursor-pointer"
                >Read More</Button
              >
            </CardContent>
          </Card>   
        </div>
        <div class="flex-2">
         
        </div>
      </div>
    </div>
  </main>
</template>
