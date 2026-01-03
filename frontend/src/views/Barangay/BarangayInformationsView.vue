<script setup>
import BarangayDashboardCard from '@/components/cards/BarangayDashboardCard.vue';
import BarangayContactInformation from '@/components/forms/BarangayContactInformation.vue';
import BarangayOfficialsTable from '@/components/tables/BarangayOfficialsTable.vue';


</script>

<template>
  <div class="space-y-4 pb-4">
    <BarangayDashboardCard />
    <BarangayContactInformation/>
   <BarangayOfficialsTable/>
  </div>
</template>
  