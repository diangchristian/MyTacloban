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

</script>

<template>
     <Card class="">
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
              <Button variant="outline" class="cursor-pointer">
                Read More
              </Button>
            </CardContent>
          </Card>
</template>