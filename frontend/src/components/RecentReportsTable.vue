<script setup>
const reports = [
  {
    id: "#1234",
    category: "Flooding",
    title: "Severe flooding in Barangay 95",
    status: "Pending",
    dateSubmitted: "Nov 22, 2025"
  },
  {
    id: "#1235",
    category: "Street Light",
    title: "Streetlight not working on Main Road",
    status: "In Progress",
    dateSubmitted: "Nov 21, 2025"
  },
  {
    id: "#1236",
    category: "Garbage",
    title: "Overflowing trash bins",
    status: "Resolved",
    dateSubmitted: "Nov 20, 2025"
  },
  {
    id: "#1237",
    category: "Road",
    title: "Potholes causing traffic",
    status: "Pending",
    dateSubmitted: "Nov 22, 2025"
  },
  {
    id: "#1238",
    category: "Flooding",
    title: "River overflow near Barangay 5",
    status: "In Progress",
    dateSubmitted: "Nov 21, 2025"
  }
]
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
        v-for="report in reports"
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
          <button class="font-bold hover:underline">View</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</template>
