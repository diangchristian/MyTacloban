<script setup lang="ts">
import BarangayCard from '@/components/BarangayCard.vue';
import { Input } from '@/components/ui/input';

</script>

<template>
    <div class="relative flex items-center border-b-2 border-b-gray/50 pb-4">
        <svg class="absolute left-3 h-5 w-5 text-gray-400 focus-within:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <Input type="text" placeholder="Search Barangay" class="w-2/3 pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
    </div>

    <div class="mt-10">
        <div class="grid grid-cols-4 gap-4">
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
            <BarangayCard/>
        </div>
    </div>

</template>