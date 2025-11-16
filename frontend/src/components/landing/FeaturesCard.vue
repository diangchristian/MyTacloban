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
        description : "Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. Aliquam erat volutpat. Nunc euismod bibendum augue."
    },
    {
        title: "Announcements",
        description : "Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. Aliquam erat volutpat. Nunc euismod bibendum augue."
    },
    {
        title: "Events",
        description : "Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. Aliquam erat volutpat. Nunc euismod bibendum augue."
    },
    {
        title: "Barangay",
        description : "Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. Aliquam erat volutpat. Nunc euismod bibendum augue."
    },
]


</script>



<template>
   <div v-for="feature in features" :key="feature.title">
        <Card>
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