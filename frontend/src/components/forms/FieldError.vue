 <script setup>

const props = defineProps({
    errorMessage: {
        type: String
    }
})


</script>


<template>
    <p class="text-xs text-red-400">{{errorMessage}}</p>
</template>