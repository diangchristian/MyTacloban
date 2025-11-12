<script setup >
import { useAuthStore } from '@/stores/auth';
import { storeToRefs } from 'pinia';
import {onMounted} from 'vue'

const {logout} = useAuthStore()
const {user} = useAuthStore()


</script>


<template>
    <h1>Welcome to Dashboard</h1>
    <p>{{ user.name }}</p>
    <p>{{ user.role }}</p>

    <form action="" @submit.prevent="logout">
        <button type="submit" class="cursor-pointer">Logout</button>
    </form>


</template>