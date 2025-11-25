<script setup>
import { Image } from 'lucide-vue-next';
</script>


<template>
    <div class="bg-white   p-4 rounded-xl">
        <h3 class="inline-flex items-center gap-2 mb-2 font-semibold"> <Image class="size-5 text-primary"/> Attached Photos (3)</h3>
        <div class="flex gap-4">
            <div class="size-40 bg-gray rounded-md border border-primary overflow-hidden ">
            <img src="@/assets/images/news-sample.png" alt="" class="object-cover">
            </div>
            <div class="size-40 bg-gray rounded-md border border-primary overflow-hidden ">
                <img src="@/assets/images/news-sample.png" alt="" class="object-cover">
            </div>
            <div class="size-40 bg-gray rounded-md border border-primary overflow-hidden ">
                <img src="@/assets/images/news-sample.png" alt="" class="object-cover">
            </div>
            <div class="size-40 bg-gray rounded-md border border-primary overflow-hidden ">
                <img src="@/assets/images/news-sample.png" alt="" class="object-cover">
            </div>
        </div>
    </div>
</template>