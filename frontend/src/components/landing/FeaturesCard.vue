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
        description: "Easily access city services—from requesting documents to applying for permits and local assistance. Submit requirements online, track the status of your requests, and receive notifications once they’re completed.",
        delay: 100,
    },
    {
        title: "Announcements",
        description: "Get real-time updates from the City Government. Stay informed on weather advisories, traffic alerts, public safety notices, and other important announcements that affect the community.",
        delay: 200,
    },
    {
        title: "Events",
        description: "Explore upcoming activities and programs happening around Tacloban. Stay updated on festivals, public events, and government initiatives with detailed schedules and locations.",
        delay: 300,
    },
    {
        title: "Barangay",
        description: "Stay connected with your barangay. View local officials, programs, and contact information. Access community updates, schedules, and submit concerns directly for faster response and assistance.",
        delay: 400,
    },
];



</script>



<template>
   <div v-for="feature in features" :key="feature.title" >
        <Card class="h-full" data-aos="fade-up" :data-aos-delay="feature.delay">
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