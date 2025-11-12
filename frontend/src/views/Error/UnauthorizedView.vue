<script setup>
import { RouterLink } from 'vue-router';
</script>


<template>
    <main>
        <h1>404</h1>
        <p>Unauthorized Access</p>
        <RouterLink :to="{name: 'home'}">Go to home</RouterLink>
    </main>

</template>