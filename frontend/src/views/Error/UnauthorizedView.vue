<script setup>
import { RouterLink, useRouter } from 'vue-router';
import { Button } from '@/components/ui/button';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const handleLogout = async () => {
    await authStore.logout();
    router.push({ name: 'login' });
};

</script>


<template>
    <main>
        <div class="mt-22">
            <h1>404</h1>
            <p>Unauthorized Access</p>
            <RouterLink :to="{name: 'home'}">Go to home</RouterLink>
            <Button @click="handleLogout">Logout</Button>
        </div>
    </main>

</template>