<script setup lang="ts">
import type { CarouselApi } from "@/components/ui/carousel";
import { ref, onBeforeUnmount } from "vue";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";

import StepCard from "@/components/landing/StepCard.vue"; 
import StepImg1 from "@/assets/images/Sign up-cuate.png";
import StepImg2 from "@/assets/images/Search-cuate.png";
import StepImg3 from "@/assets/images/Social media-amico.png";




const api = ref<CarouselApi | null>(null);
const intervalId = ref<number | null>(null);
const delay = 3000;

const steps = [
  {
    stepNumber: 1,
    title: "Register an Account",
    description: "Create your profile as a Tacloban resident or LGU staff member.",
    image: StepImg1, // <--- UPDATE THESE IMAGE PATHS
  },
  {
    stepNumber: 2,
    title: "Search & Discover",
    description: "Easily search for local businesses, public services, and city events.",
    image: StepImg2, // <--- UPDATE THESE IMAGE PATHS
  },
  {
    stepNumber: 3,
    title: "Connect Locally",
    description: "Get contact information and directions to connect with the local community.",
    image: StepImg3, // <--- UPDATE THESE IMAGE PATHS
  },
];


function setApi(val: CarouselApi) {
  api.value = val;
  startAutoSlide()
}

function startAutoSlide(){
    if(!api.value) return

    intervalId.value = window.setInterval(() => {
        if (!api.value) return;
        const current = api.value.selectedScrollSnap();
        const total = api.value.scrollSnapList().length;

        if (current === total - 1) {
            api.value.scrollTo(0);
        } else {
            api.value.scrollNext();
        }
    }, delay)

}

onBeforeUnmount(() => {
  if (intervalId.value) clearInterval(intervalId.value);
});
</script>

<template>
  <div class="overflow-hidden py-16 ">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <h1
        data-aos="fade-down"
        class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl text-center"
      >
        How <span class="text-primary">MyTacloban</span> Works
      </h1>

      <div
        class="mx-auto grid max-w-3xl grid-cols-1 gap-x-4 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2 mt-8 rounded-lg bg-gradient-to-br from-white via-white to-green-100/90 p-8 md:px-8 md:py-12 shadow-lg"
      data-aos="zoom-in-up"
      
        >
        <div class="w-full space-y-8 flex items-center justify-center flex-col">
          <StepCard 
            v-for="step in steps" 
            :key="step.stepNumber" 
            :step-number="step.stepNumber"
            :title="step.title"
            :description="step.description"
          />
        </div>

        <div class=" flex items-center justify-center">
          <div class="">
            <Carousel class="relative w-full max-w-md" @init-api="setApi">
              <CarouselContent>
                <CarouselItem v-for="(step, index) in steps" :key="index">
                  <div class="p-1 ">
                    <Card class="w-full bg-primary/5">
                      <CardContent
                        class="flex aspect-square items-center justify-center   "
                      >
                        <img
                            :src="step.image"
                            :alt="step.title"
                            class="h-full object-cover rounded-md shadow-lg border"
                        />
                      </CardContent>
                    </Card>
                  </div>
                </CarouselItem>
              </CarouselContent>
              </Carousel>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>