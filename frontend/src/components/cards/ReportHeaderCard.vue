<script setup>
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import Label from "../ui/label/Label.vue";
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
                <div class="flex items-center gap-2 w-full mb-2">
                    <p class="text-xs font-semibold">Report #2847</p>
                  <span
                    class="bg-blue-500/15 px-2 py-1 text-xs rounded-lg text-blue-400 border-2 border-blue-300"
                    >Broken Streetlights</span
                  >
                 
                </div>
                <h1 class="text-2xl">  Broken streetlight in apitong road, near citi hardware</h1>
              </CardTitle>
              <CardDescription class="mt-1">
                <Label for="" class="text-black mb-2">Description</Label>
                <p>
                    Sed ullamcorper magna sit amet interdum. Quia laoreet nulla condimenium. Aliquam erat volupat. Cras at nulla in turpis dignissim consequat vel laoreet purus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus laoreet, lacus id aliquot molestie ut lacus interdum.
                </p>
              </CardDescription>
            </CardHeader>
          </Card>
</template>