<script setup>
import NavBar from './NavBar.vue';
</script>



<template>
    <div>
        <NavBar />
        <main>
            <slot />
        </main>
    </div>
</template>