<script setup>

import HeroImage from "@/assets/images/Mockup.png"




const stats = [
    {
        title: 'Active Citizen Served',
        data: '25,000+'
    },
    {
        title: 'Public Updates Delivered',
        data: '1,200+'
    },
    {
        title: 'Average Response Time',
        data: '< 24 hrs'
    },
    {
        title: 'Report Submitted',
        data: '500++'
    },
]



</script>


<template >
    <main class="w-full pt-12 p-4 bg-gradient-to-t from-secondary via-secondary to-green-100/60  " >
        <div class="max-w-7xl mx-auto" data-aos="fade-up">
            <div class="flex items-center justify-center flex-col space-y-2">
            <h2 class="text-xs font-semibold text-indigo-400 bg-primary/10 border-2 border-primary text-primary w-fit px-4 py-2 rounded-xl mt-4 ">About Us</h2>
            <h1 class="text-5xl font-semibold italic">Our Journey to smarter city</h1>
            <p>Empowering citizens through accessible digital city services and community engagement. </p>
        </div>
        <div class="max-w-5xl mx-auto shadow-xl/30 shadow-primary mt-4 rounded-xl overflow-hidden">
            <img :src="HeroImage" alt="">
        </div>
        <div class="mt-12  max-w-5xl mx-auto border-y-2 py-4">
            <div class="grid grid-cols-4 items-center" >
                <div class="p-4 text-center" v-for="stat in stats" :key="stat.title">
                    <p class="font-medium">{{ stat.title }}</p>
                    <h1 class="text-5xl font-semibold mt-4 text-primary">{{ stat.data }}</h1>
                </div>
            
            </div>
        </div>
        </div>
    </main>
    
</template>