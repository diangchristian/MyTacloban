<script setup>
import { ref } from 'vue';
import StatsCard from '@/components/cards/UserManagementCard.vue';
import UserRow from '@/components/UserRow.vue';
import { Users, Radio, Clock, Search } from 'lucide-vue-next';

const currentPage = ref(1);

const stats = [
  { title: 'Total Users', value: 248, icon: Users, color: 'text-blue-600', bgColor: 'bg-blue-100' },
  { title: 'Active Users', value: 3, icon: Radio, color: 'text-green-600', bgColor: 'bg-green-100' },
  { title: 'Pending/For Approval', value: 2, icon: Clock, color: 'text-yellow-600', bgColor: 'bg-yellow-100' }
];

const users = ref([
  { id: 1, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Blocked', dateJoined: 'Jan. 12, 2023' },
  { id: 2, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
  { id: 3, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 4, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Inactive', dateJoined: 'Jan. 12, 2023' },
  { id: 5, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 6, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
  { id: 7, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' }
]);

</script>

<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <StatsCard 
          v-for="(stat, index) in stats" 
          :key="index"
          :title="stat.title"
          :value="stat.value"
          :icon="stat.icon"
          :color="stat.color"
          :bg-color="stat.bgColor"
        />
      </div>

      <!-- Main Content Card -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <!-- Search and Filters -->
        <div class="p-6 border-b border-gray-100">
          <div class="relative mb-4">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
              type="text"
              placeholder="Search name, username or email..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div class="flex gap-3">
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>All Roles</option>
              <option>Admin</option>
              <option>Resident</option>
            </select>
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option>All Status</option>
              <option>Active</option>
              <option>Pending</option>
              <option>Blocked</option>
              <option>Inactive</option>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
              <tr>
                <th class="py-3 px-4 text-left">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300" />
                </th>
                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Joined</th>
                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody>
              <UserRow
                v-for="user in users"
                :key="user.id"
                :user="user"
                @view="handleView"
                @edit="handleEdit"
                @delete="handleDelete"
              />
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
          <p class="text-sm text-gray-600">Showing 7 of 31</p>
          <div class="flex items-center gap-2">
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              &lt;
            </button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded-lg">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              &gt;
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>