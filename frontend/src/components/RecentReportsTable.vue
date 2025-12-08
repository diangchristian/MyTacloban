<script setup>
import { defineProps } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
  reports: {
    type: Array,
    default: () => []
  }
})

const viewReport = (reportId) => {
  router.push({ name: 'admin.report.details', params: { id: reportId } })
}
</script>

<template>
<div class="relative overflow-x-auto bg-white shadow-xs rounded-2xl ">
  <table class="w-full text-sm text-left text-gray-700">
    <thead class=" border-b border-default">
      <tr>
        <th class="px-6 py-3 font-medium">ID</th>
        <th class="px-6 py-3 font-medium">Category</th>
        <th class="px-6 py-3 font-medium">Title</th>
        <th class="px-6 py-3 font-medium">Status</th>
        <th class="px-6 py-3 font-medium">Date Submitted</th>
        <th class="px-6 py-3 font-medium">Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="report in props.reports"
        :key="report.id"
        class=" border-b border-default"
      >
        <th class="px-6 py-4 font-medium whitespace-nowrap">{{ report.id }}</th>
        <td class="px-6 py-4">{{ report.category }}</td>
        <td class="px-6 py-4">{{ report.title }}</td>
        <td class="px-6 py-4">
          <span
            :class="{
              'bg-yellow-100 text-yellow-800': report.status === 'Pending',
              'bg-blue-100 text-blue-800': report.status === 'In Progress',
              'bg-green-100 text-green-800': report.status === 'Resolved'
            }"
            class="px-2 py-1 rounded-full text-xs font-semibold"
          >
            {{ report.status }}
          </span>
        </td>
        <td class="px-6 py-4">{{ report.dateSubmitted }}</td>
        <td class="px-6 py-4">
          <button 
            @click="viewReport(report.id)" 
            class="font-bold hover:underline text-black-600 hover:text-primary cursor-pointer"
          >
            View
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</template>
