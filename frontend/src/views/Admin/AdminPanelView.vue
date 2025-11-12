<script setup >
import { useAuthStore } from '@/stores/auth';
import { storeToRefs } from 'pinia';
import {onMounted} from 'vue'

const {logout} = useAuthStore()
const {user} = useAuthStore()


</script>


<template>
    <div class="mt-28">
        <h1>Welcome to Admin Dashboard</h1>

        <form action="" @submit.prevent="logout">
            <button type="submit" class="cursor-pointer">Logout</button>
        </form>
    </div>


</template>