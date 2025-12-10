<script setup>
import {
  Clock, Calendar, MapPin,
  SquarePen, Trash2, Eye, Plus, ListChecks, Megaphone
} from "lucide-vue-next";

defineProps({
events: Array,
getStatusClasses: Function
})
</script>
<template>
    <section class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Event Management</h2>
  
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ event.title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>{{ event.date }}</span>
                                    <span class="text-xs text-gray-400">{{ event.time }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ event.category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[getStatusClasses(event.status)]"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                >
                                    {{ event.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <Button
                                        variant="ghost"
                                        class="p-2 text-blue-600 hover:text-blue-800"
                                        @click="openEditDialog(event)"
                                        aria-label="Edit Event"
                                    >
                                        <SquarePen class="size-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        class="p-2 text-red-600 hover:text-red-800"
                                        @click="deleteEvent(event.id)"
                                        aria-label="Delete Event"
                                    >
                                        <Trash2 class="size-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        class="p-2 text-green-600 hover:text-green-800"
                                        @click="openPreviewDialog(event)"
                                        aria-label="Preview Event"
                                    >
                                        <Eye class="size-4" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
      </div>
  
      <p v-if="events.length === 0" class="text-gray-500 text-center py-10">
        No events found. Click "New Event" to create one.
      </p>
    </section>
  </template>
  

