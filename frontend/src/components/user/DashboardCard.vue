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



const props = defineProps({
    button: {
        type: String,
        default: ''
    }
})

</script>





<template>
 <Card class="m-0">
    <CardHeader>
        <CardTitle class="text-md">
            <!-- <div class="size-20  rounded-lg mb-2"></div> -->
            Submit a Report
        </CardTitle>
        <CardDescription class="-mt-2">
            Report issues in your community
        </CardDescription>
    </CardHeader>
    <CardContent class="-mt-1">
        <Button :variant="props.button === 'primary' ? '' : 'outline' " class="w-full text-start" >Get Started <ArrowRight /></Button>
        
    </CardContent>
            
    </Card>

</template>