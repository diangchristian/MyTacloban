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
const props = defineProps({
  header: {
    type: Object
  }
})
</script>



<template> 
    <Card class="">
            <CardHeader>
              <CardTitle class="text-md">
                <div class="flex items-center gap-2 w-full mb-2">
                    <p class="text-xs font-semibold">Report #{{ props.header.id }}</p>
                  <span
                    class="bg-blue-500/15 px-2 py-1 text-xs rounded-lg text-blue-400 border-2 border-blue-300"
                    >{{ props.header.category_name }}</span
                  >
                 
                </div>
                <h1 class="text-2xl">  {{ props.header.title }}</h1>

                <div class="mt-4">
                  <Label for="" class="text-black mb-2">Reporter Information</Label>
                  <p class="text-sm font-light">Name: {{props.header.full_name }}</p>
                  <p class="text-sm font-light">Email: {{props.header.email }}</p>
                  <p class="text-sm font-light">Phone Number: {{props.header.phone_number }}</p>
                </div>
              </CardTitle>
              <CardDescription class="mt-1">
                <Label for="" class="text-black mb-2">Description</Label>
                <p>
                  {{ props.header.description }}.
                </p>
              </CardDescription>
            </CardHeader>
          </Card>
</template>