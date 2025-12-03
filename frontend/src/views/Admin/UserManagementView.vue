<script setup>
import { ref, computed } from 'vue';
import StatsCard from '@/components/cards/UserManagementCard.vue';
import UserRow from '@/components/UserRow.vue';
import { Users, Radio, Clock, Search } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox'
import { Button } from '@/components/ui/button'

import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationLast,
  PaginationFirst,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

import {
  Table,
  TableBody,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

const stats = [
  { title: 'Total Users', value: 248, icon: Users, color: 'text-blue-600', bgColor: 'bg-blue-100' },
  { title: 'Active Users', value: 3, icon: Radio, color: 'text-green-600', bgColor: 'bg-green-100' },
  { title: 'Pending/For Approval', value: 2, icon: Clock, color: 'text-yellow-600', bgColor: 'bg-yellow-100' }
];

const people = ref([
  { id: 1, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Blocked', dateJoined: 'Jan. 12, 2023' },
  { id: 2, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
  { id: 3, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 4, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Inactive', dateJoined: 'Jan. 12, 2023' },
  { id: 5, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 6, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
  { id: 7, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 8, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 9, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 10, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 11, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
  { id: 12, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Blocked', dateJoined: 'Jan. 12, 2023' },
]);

const selectedPeople = ref([])

const toggleSelectAll = () => {
  if (selectedPeople.length === people.length) {
    selectedPeople.value = []
  } else {
    selectedPeople.value = people.value.map(p => p.id)
  }
}

const toggleSelection = (id) => {
  if (selectedPeople.value.includes(id)) {
    selectedPeople.value = selectedPeople.value.filter(x => x !== id)
  } else {
    selectedPeople.value.push(id)
  }
}

const viewUser = (person) => {
  console.log("Viewing:", person)
}

const editUser = (person) => {
  console.log("Editing:", person)
}

const deleteUser = (person) => {
  console.log("Deleting:", person)
}

// pagination area

const currentPage = ref(1)
const itemsPerPage = 10

const paginatedPeople = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return people.value.slice(start, end)
})

const totalItems = computed(() => people.value.length)

// const users = ref([
//   { id: 1, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Blocked', dateJoined: 'Jan. 12, 2023' },
//   { id: 2, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
//   { id: 3, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 4, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Inactive', dateJoined: 'Jan. 12, 2023' },
//   { id: 5, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 6, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Resident', status: 'Pending', dateJoined: 'Jan. 12, 2023' },
//   { id: 7, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 8, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 9, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 10, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' },
//   { id: 11, name: 'Juan De La Doe', email: 'juandelacru@gmail.com', role: 'Admin', status: 'Active', dateJoined: 'Jan. 12, 2023' }
// ]);

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
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>

          <div class="flex gap-3">
            <Select v-model="selectedRole">
              <SelectTrigger class="w-40">
                <SelectValue placeholder="All Roles" />
              </SelectTrigger>

              <SelectContent>
                <SelectItem value="all">All Roles</SelectItem>
                <SelectItem value="admin">Admin</SelectItem>
                <SelectItem value="resident">Resident</SelectItem>
              </SelectContent>
            </Select>

            <Select v-model="selectedStatus">
              <SelectTrigger class="w-40">
                <SelectValue placeholder="All Status" />
              </SelectTrigger>

              <SelectContent>
                <SelectItem value="all">All Status</SelectItem>
                <SelectItem value="active">Active</SelectItem>
                <SelectItem value="pending">Pending</SelectItem>
                <SelectItem value="blocked">Blocked</SelectItem>
                <SelectItem value="inactive">Inactive</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        <!-- Table -->
        <Table>
          <TableHeader>
            <TableRow class="row">
              <TableHead class="w-[50px]">
                <Checkbox 
                  :checked="selectedPeople.length === people.length"
                  @update:checked="toggleSelectAll"
                />
              </TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Role</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Date Joined</TableHead>
              <TableHead class="w-[70px]">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <UserRow
              v-for="person in paginatedPeople"
              :key="person.id"
              :person="person"
              :selected="selectedPeople.includes(person.id)"
              @toggle="toggleSelection"
              @view="viewUser"
              @edit="editUser"
              @delete="deleteUser"
            />
          </TableBody>
        </Table>

        <!-- Pagination -->
          <div class="mt-4 mb-4 flex justify-center">
            <Pagination 
              v-slot="{ page }" 
              :items-per-page="itemsPerPage" 
              :total="totalItems"
              :default-page="currentPage"
              @update:page="currentPage = $event"
            >
              <PaginationContent v-slot="{ items }">
                <PaginationPrevious />
                
                <template v-for="(item, index) in items" :key="index">
                  <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                  >
                    {{ item.value }}
                  </PaginationItem>
                  <PaginationEllipsis v-else :index="index" />
                </template>
                
                <PaginationNext />
              </PaginationContent>
            </Pagination>
          </div>
      </div>
    </div>
  </div>
</template>