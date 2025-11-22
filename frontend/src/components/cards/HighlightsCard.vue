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
    <Card class="m-2 bg-gradient-to-l  via-green to-primary/70">
    <CardHeader>
        <CardTitle class="text-md">
            One way traffic along Imelda Ave. — Avenida Veteranos St.
        </CardTitle>
        <CardDescription class="mt-2 text-[#000]">
            Due to the motorcade to honor the fiesta of Tacloban City, Traffic from Imelda Ave. will be redirected in a one way commodity through Avenida Veteranos. Please be guided accordingly.
        </CardDescription>
    </CardHeader>
    <CardContent class="-mt-1">
        <div class="flex items-center justify-between w-full mb-2">
            <span class="px-2 py-1 text-xl font-bold rounded-lg text-[#FFF] text-shadow-lg">ROAD ADVISORY</span>
            <p class="text-black-400 text-xs font-regular">2 hours ago</p>

        </div>
    </CardContent>
            
    </Card>
</template>