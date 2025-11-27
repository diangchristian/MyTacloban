<script setup lang="ts">
import type { CarouselApi } from "@/components/ui/carousel";
import { watchOnce } from "@vueuse/core";
import { ref, watch } from "vue";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
import { defineProps } from "vue";

const props = defineProps({
  images: {
    type: Array
  },
  index: {
    type: Number
  }
})


const api = ref<CarouselApi>();
const totalCount = ref(0);

const activeIndex = ref(props.index ?? 0); // 0-based
const currentSlide = ref(1);

function setApi(val: CarouselApi) {
  api.value = val;
}

watchOnce(api, (api) => {
  if (!api) return;

  totalCount.value = api.scrollSnapList().length;

  api.on("select", () => {
    currentSlide.value = api.selectedScrollSnap() + 1;
  });

  // ✅ sync initial index
  api.scrollTo(props.index);
  currentSlide.value = props.index + 1;
});

watch(
  () => props.index,
  (i) => {
    if (api.value) {  
      api.value.scrollTo(i);
      currentSlide.value = i + 1;
    }
  }
);


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

                    <CarouselItem v-for="(img, index) in images" :key="index">
                      <img :src="img"   class="object-cover w-full h-full unselectable-image"  />
                    </CarouselItem>

                </CarouselContent>

                <CarouselPrevious />
                <CarouselNext />
                </Carousel>

          <div class="py-2 text-center text-sm text-white">
            Slide {{ currentSlide }} of {{ totalCount }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
