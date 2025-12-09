<script setup>
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import FeatruesIcon from "./FeatruesIcon.vue";



const features = [
    {
        title: "Services",
        description : "Access public services with ease—from requesting documents to accessing local assistance programs. Submit requirements online, track progress, and get notified once your request is processed."
    },
    {
        title: "Announcements",
        description : "Stay updated with the latest news from the city. View weather alerts, road advisories, community warnings, and important government updates—all in one place."
    },
    {
        title: "Events",
        description : "Discover upcoming activities happening around Tacloban. From festivals and public gatherings to government-led programs, you can browse event details, schedules, and locations effortlessly."
    },
    {
        title: "Barangay",
        description : "Connect with your barangay effortlessly. View barangay officials, programs, hotlines, community schedules, and updates. You can also report community concerns directly for faster local response."
    },
]


</script>



<template>
   <div v-for="feature in features" :key="feature.title">
        <Card class="h-full">
            <CardHeader>
                <CardTitle class="text-xl">
                    <div class="size-20  rounded-lg mb-2">
                    <FeatruesIcon :title="feature.title.toLowerCase()"/>
                    </div>
                    {{ feature.title}}
                </CardTitle>
            </CardHeader>
            <CardContent class="-mt-4">
                <p class="text-gray-600">{{ feature.description }}</p>
            </CardContent>
            
    </Card>
   </div>
</template>