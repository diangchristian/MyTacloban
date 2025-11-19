<script setup lang="ts">
import type { CarouselApi } from "@/components/ui/carousel";
import { watchOnce } from "@vueuse/core";
import { ref } from "vue";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";

const api = ref<CarouselApi>();
const totalCount = ref(0);
const current = ref(0);

function setApi(val: CarouselApi) {
  api.value = val;
}
watchOnce(api, (api) => {
  if (!api) return;
  totalCount.value = api.scrollSnapList().length;
  current.value = api.selectedScrollSnap() + 1;
  api.on("select", () => {
    current.value = api.selectedScrollSnap() + 1;
  });
});


</script>

<template>
  <!-- Main modalh-[calc(100%-1rem)]   -->
  <div
    id="default-modal"
    tabindex="-1"
    aria-hidden="true"
    class="bg-black/40 z-10 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%)] max-h-full"
  >
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div
        class="relative flex items-center justify-center rounded-base  p-4 md:p-6 ">
        <div class="w-full sm:w-auto">
            <Carousel class="relative w-full max-w-md" @init-api="setApi">
                <CarouselContent>

                    <CarouselItem v-for="(_, index) in 5" :key="index">
                    <!-- Remove outer p-1 -->
                    <div class="w-full h-full rounded-xl overflow-hidden">
                        <Card class="p-0">
                        
                        <CardContent class="p-0 m-0 aspect-square">
                            <img 
                            src="@/assets/images/Mockup2.png"
                            class="object-cover w-full h-full"  
                            alt=""
                            />
                        </CardContent>

                        </Card>
                    </div>
                    </CarouselItem>

                </CarouselContent>

                <CarouselPrevious />
                <CarouselNext />
                </Carousel>

          <div class="py-2 text-center text-sm text-white">
            Slide {{ current }} of {{ totalCount }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
