<script setup>


const props = defineProps({
    title: '',
    subTitle: ''
})



</script>


<template>
    <div class="w-full h-52 overflow-hidden rounded-md relative">
        <img src="@/assets/images/user-bg.jpg" class="w-full h-full object-cover" alt="">
        <div class="w-full h-full bg-gradient-to-l via-green to-primary/70 absolute top-0 left-0 z-2 flex flex-col justify-center px-8 text-white">
            <h1 class="text-4xl font-bold">{{ title }}</h1>
            <p class="font-semibold">{{ subTitle }}</p>
        </div>
    </div>
</template>