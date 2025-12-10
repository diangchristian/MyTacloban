<script setup lang="ts">
  import type { CarouselApi } from "@/components/ui/carousel";
  import { ref, watch, onMounted } from "vue";
  import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious
  } from "@/components/ui/carousel";
  
  const props = defineProps({
    images: {
      type: Array,
      default: () => []
    },
    index: {
      type: Number,
      default: 0
    }
  });
  
  const emit = defineEmits(["update:index"]);
  
  const api = ref<CarouselApi | null>(null);
  const totalCount = ref(0);
  const currentSlide = ref(props.index + 1);
  
  function setApi(val: CarouselApi) {
    api.value = val;
  
    // Initialize once API is ready
    if (api.value && props.images.length) {
      totalCount.value = api.value.scrollSnapList().length;
      api.value.scrollTo(props.index);
      currentSlide.value = props.index + 1;
  
      api.value.on("select", () => {
        currentSlide.value = api.value!.selectedScrollSnap() + 1;
        emit("update:index", api.value!.selectedScrollSnap());
      });
    }
  }
  
  // Watch for initial index changes from parent
  watch(
    () => props.index,
    (newIndex) => {
      if (api.value && props.images.length) {
        api.value.scrollTo(newIndex);
        currentSlide.value = newIndex + 1;
      }
    }
  );
  
  // Watch for images update dynamically
  watch(
    () => props.images,
    (imgs) => {
      if (api.value && imgs.length) {
        totalCount.value = api.value.scrollSnapList().length;
        api.value.scrollTo(props.index);
        currentSlide.value = props.index + 1;
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
