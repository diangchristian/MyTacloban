<script setup>
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import Button from "../ui/button/Button.vue";
import { defineProps } from "vue";
import { 
ArrowRight
} from "lucide-vue-next"
import { RouterLink } from "vue-router";


const props = defineProps({
    quickLinks: {
        type: Object,
    }
})

</script>





<template>
 <Card class="m-0">
    <CardHeader>
        <CardTitle class="text-md">
            <!-- <div class="size-20  rounded-lg mb-2"></div> -->
            {{ quickLinks.title }}
        </CardTitle>
        <CardDescription class="-mt-2">
            {{ quickLinks.subHeader }}
        </CardDescription>
    </CardHeader>
    <CardContent class="-mt-1">
        <Button :variant="quickLinks.buttonType" class="w-full text-start cursor-pointer" asChild>
            <RouterLink :to="quickLinks.url" class="flex items-center justify-between w-full">
            Get Started <ArrowRight class="ml-2" />
            </RouterLink>
        </Button>
        
    </CardContent>
            
    </Card>

</template>