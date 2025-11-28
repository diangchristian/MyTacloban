<script setup>
import { defineProps, defineEmits } from 'vue';
import { Eye, Edit2, Trash2, Users } from 'lucide-vue-next';

const props = defineProps({
  user: Object
});

const emit = defineEmits(['view', 'edit', 'delete']);

const statusColors = {
  'Blocked': 'bg-red-100 text-red-600',
  'Pending': 'bg-yellow-100 text-yellow-600',
  'Active': 'bg-green-100 text-green-600',
  'Inactive': 'bg-gray-100 text-gray-600'
};

const roleColors = {
  'Resident': 'bg-gray-100 text-gray-700',
  'Admin': 'bg-purple-100 text-purple-700'
};
</script>

<template>
  <tr class="border-b border-gray-100 hover:bg-gray-50">
    <td class="py-4 px-4">
      <input type="checkbox" class="w-4 h-4 rounded border-gray-300" />
    </td>
    <td class="py-4 px-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
          <Users class="w-5 h-5 text-gray-600" />
        </div>
        <div>
          <p class="font-medium text-gray-900">{{ user.name }}</p>
          <p class="text-sm text-gray-500">{{ user.email }}</p>
        </div>
      </div>
    </td>
    <td class="py-4 px-4">
      <span :class="`px-3 py-1 rounded-full text-xs font-medium ${roleColors[user.role]}`">
        {{ user.role }}
      </span>
    </td>
    <td class="py-4 px-4">
      <span :class="`px-3 py-1 rounded-full text-xs font-medium ${statusColors[user.status]}`">
        {{ user.status }}
      </span>
    </td>
    <td class="py-4 px-4 text-sm text-gray-600">{{ user.dateJoined }}</td>
    <td class="py-4 px-4">
      <div class="flex items-center gap-2">
        <button 
          @click="emit('view', user)"
          class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <Eye class="w-4 h-4 text-gray-600" />
        </button>
        <button 
          @click="emit('edit', user)"
          class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <Edit2 class="w-4 h-4 text-gray-600" />
        </button>
        <button 
          @click="emit('delete', user)"
          class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <Trash2 class="w-4 h-4 text-gray-600" />
        </button>
      </div>
    </td>
  </tr>
</template>