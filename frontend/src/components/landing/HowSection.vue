<script setup lang="ts">
import type { CarouselApi } from "@/components/ui/carousel";
import { watchOnce } from "@vueuse/core";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
import StepCard from "@/components/landing/StepCard.vue";

const api = ref<CarouselApi | null>(null);
const intervalId = ref<number | null>(null);
const delay = 3000;

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
        class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl text-center"
      >
        How <span class="text-primary">MyTacloban</span> Works
      </h1>

      <div
        class="mx-auto grid max-w-3xl grid-cols-1 gap-x-4 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2 mt-8 rounded-lg bg-gradient-to-br from-white via-white to-green-100/90 p-8 md:px-8 md:py-12 shadow-lg"
      >
        <div class="w-full space-y-8 flex items-center justify-center flex-col">
          <StepCard />
          <StepCard />
          <StepCard />
        </div>
        <div class=" flex items-center justify-center">
          <div class="lg:max-w-xl">
            <Carousel class="relative w-full max-w-xs"  @init-api="setApi">
              <CarouselContent>
                <CarouselItem v-for="(_, index) in 3" :key="index">
                  <div class="p-1 ">
                    <Card class="">
                      <CardContent
                        class="flex aspect-square items-center justify-center p-6  y h-96 ">
                        <h1>Hello</h1>
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
