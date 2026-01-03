<script setup>
import BarangayInfoCard from './BarangayInfoCard.vue';
import { Users, Building2, UsersRound, Search, Shield, UserCircle, FileText, Wallet, UserCheck, Star, Plus, Pencil, Trash2 } from 'lucide-vue-next'


const formatNumber = (num) => Number(num).toLocaleString('en-US')
</script>


<template>
    <div class="w-full overflow-hidden rounded-md relative 
                bg-gradient-to-br from-white via-green-100 to-green-200 
                shadow-md p-8">
  
      <div class="flex flex-col gap-2">
        <h1 class="text-4xl font-bold text-primary">
          Barangay 95 – A Caibaan
        </h1>
  
        <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium 
                     rounded-full bg-green-100 text-green-700 w-fit">
          <span class="w-2 h-2 bg-green-500 rounded-full"></span>
          Active
        </span>
      </div>
  
      <div class="my-6 h-px bg-green-200/70"></div>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <BarangayInfoCard 
          number="0" 
          label="Officials" 
          :icon="Users" 
          iconColor="bg-blue-500" 
          barangay="Barangay 95 - A Caibaan" 
        />
  
        <BarangayInfoCard 
          :number="formatNumber(1000)" 
          label="Population" 
          :icon="UsersRound" 
          iconColor="bg-purple-500" 
          barangay="Barangay 95 - A Caibaan" 
        />
  
        <BarangayInfoCard 
          :number="formatNumber(1000)" 
          label="Households" 
          :icon="Building2" 
          iconColor="bg-green-500" 
          barangay="Barangay 95 - A Caibaan" 
        />
      </div>
    </div>
  </template>
  