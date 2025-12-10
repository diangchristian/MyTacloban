<script setup>
import { ref, computed, onMounted } from 'vue';
import { useUserStore } from '@/stores/userStore'; // Import the store
import { toast } from 'vue-sonner';
import StatsCard from '@/components/cards/UserManagementCard.vue';
import UserRow from '@/components/others/UserRow.vue';
import { Users, Radio, Clock, Search, UserCircle, Mail, Shield, Calendar } from 'lucide-vue-next';
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

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'

import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'

import {
  Label
} from '@/components/ui/label'

import {
  Badge
} from '@/components/ui/badge'

// Initialize store
const userStore = useUserStore();

// Local component state
const selectedRole = ref('all');
const selectedStatus = ref('all');
const searchQuery = ref('');

// Delete confirmation dialog
const showDeleteDialog = ref(false);
const userToDelete = ref(null);

const openDeleteDialog = (person) => {
  userToDelete.value = person;
  showDeleteDialog.value = true;
};

// Edit user dialog
const showEditDialog = ref(false);
const userToEdit = ref(null);
const editForm = ref({
  role: '',
  status: ''
});

const openEditDialog = (person) => {
  userToEdit.value = person;
  editForm.value = {
    role: person.role,
    status: person.status
  };
  showEditDialog.value = true;
};

const closeEditDialog = () => {
  showEditDialog.value = false;
  userToEdit.value = null;
  editForm.value = { role: '', status: '' };
};

// View user dialog
const showViewDialog = ref(false);
const userToView = ref(null);

const openViewDialog = (person) => {
  userToView.value = person;
  showViewDialog.value = true;
};

const closeViewDialog = () => {
  showViewDialog.value = false;
  userToView.value = null;
};

// Stats computed from store getters
const stats = computed(() => [
  { 
    title: 'Total Users', 
    value: userStore.totalUsers, 
    icon: Users, 
    color: 'text-blue-600', 
    bgColor: 'bg-blue-100' 
  },
  { 
    title: 'Active Users', 
    value: userStore.activeUsers, 
    icon: Radio, 
    color: 'text-green-600', 
    bgColor: 'bg-green-100' 
  },
  { 
    title: 'Blocked Users', 
    value: userStore.blockedUsers, 
    icon: Clock, 
    color: 'text-red-600', 
    bgColor: 'bg-red-100' 
  },
  { 
    title: 'Inactive Users', 
    value: userStore.inactiveUsers, 
    icon: Users, 
    color: 'text-gray-600', 
    bgColor: 'bg-gray-100' 
  }
]);

// date cleaner 
const formatDate = (date) => {
  if (!date) return "Unknown";

  const parsed = new Date(date);
  return isNaN(parsed) ? "Unknown" : parsed.toLocaleDateString();
};

// Fetch users on component mount
onMounted(async () => {
  try {
    await userStore.fetchUsers();
    toast.success('Users loaded successfully', {
      description: `Loaded ${userStore.totalUsers} users`,
    });
  } catch (error) {
    toast.error('Failed to load users', {
      description: error.message || 'An error occurred',
    });
  }
});

const viewUser = (person) => {
  openViewDialog(person);
}

const editUser = (person) => {
  openEditDialog(person);
}

const confirmEditUser = async () => {
  if (!userToEdit.value) return;

  try {
    // Only send role and status - what admins actually want to update
    const payload = {
      role: editForm.value.role,
      status: editForm.value.status
    };
    
    await userStore.updateUser(userToEdit.value.id, payload);
    toast.success('User updated', {
      description: `${userToEdit.value.name} has been updated successfully`,
    });
    closeEditDialog();
  } catch (error) {
    const errorMessage = error.response?.data?.message || error.message || 'An error occurred';
    toast.error('Failed to update user', {
      description: errorMessage,
    });
  }
}

const confirmDeleteUser = async () => {
  if (!userToDelete.value) return;

  try {
    await userStore.deleteUser(userToDelete.value.id);
    toast.success('User deleted', {
      description: `${userToDelete.value.name} has been deleted successfully`,
    });
  } catch (error) {
    toast.error('Failed to delete user', {
      description: error.message || 'An error occurred',
    });
  } finally {
    showDeleteDialog.value = false;
    userToDelete.value = null;
  }
}

const deleteUser = (person) => {
  openDeleteDialog(person);
}

// Filtered people based on search and filters
const filteredPeople = computed(() => {
  let filtered = userStore.users;

  // Apply search filter
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(person => {
      return (
        person.name?.toLowerCase().includes(query) ||
        person.email?.toLowerCase().includes(query)
      );
    });
  }

  // Apply role filter
  if (selectedRole.value !== 'all') {
    filtered = filtered.filter(person => 
      person.role === selectedRole.value
    );
  }

  // Apply status filter
  if (selectedStatus.value !== 'all') {
    filtered = filtered.filter(person => 
      person.status === selectedStatus.value
    );
  }

  return filtered;
});

// Pagination
const currentPage = ref(1)
const itemsPerPage = 10

const paginatedPeople = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredPeople.value.slice(start, end)
})

const totalItems = computed(() => filteredPeople.value.length)

const resetPagination = () => {
  currentPage.value = 1;
};

</script>

<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Loading State -->
      <div v-if="userStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Loading users...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="userStore.error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="text-red-600">{{ userStore.error }}</p>
        <Button @click="userStore.fetchUsers" class="mt-2" variant="outline">
          Retry
        </Button>
      </div>

      <!-- Main Content -->
      <div v-else>
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
                v-model="searchQuery"
                @input="resetPagination"
                type="text"
                placeholder="Search name, username or email..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>

            <div class="flex gap-3">
              <Select v-model="selectedRole" @update:model-value="resetPagination">
                <SelectTrigger class="w-40">
                  <SelectValue placeholder="All Roles" />
                </SelectTrigger>

                <SelectContent>
                  <SelectItem value="all">All Roles</SelectItem>
                  <SelectItem value="Admin">Admin</SelectItem>
                  <SelectItem value="User">User</SelectItem>
                </SelectContent>
              </Select>

              <Select v-model="selectedStatus" @update:model-value="resetPagination">
                <SelectTrigger class="w-40">
                  <SelectValue placeholder="All Status" />
                </SelectTrigger>

                <SelectContent>
                  <SelectItem value="all">All Status</SelectItem>
                  <SelectItem value="Active">Active</SelectItem>
                  <SelectItem value="Blocked">Blocked</SelectItem>
                  <SelectItem value="Inactive">Inactive</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          <!-- Table -->
          <Table>
            <TableHeader>
              <TableRow class="row">
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
                @view="viewUser"
                @edit="editUser"
                @delete="deleteUser"
              />
            </TableBody>
          </Table>

          <!-- No Results Message -->
          <div v-if="filteredPeople.length === 0" class="text-center py-12 text-gray-500">
            <p class="text-lg">No users found matching your search criteria.</p>
          </div>

          <!-- Pagination -->
          <div v-if="filteredPeople.length > 0" class="mt-4 mb-4 flex justify-center">
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

    <Dialog v-model:open="showViewDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>User Details</DialogTitle>
          <DialogDescription>
            Details for <strong>{{ userToView?.name }}</strong>
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-4">
          <div class="flex items-center gap-3">
            <UserCircle class="w-5 h-5 text-gray-500" />
            <span class="font-medium">{{ userToView?.name }}</span>
          </div>
          <div class="flex items-center gap-3">
            <Mail class="w-5 h-5 text-gray-500" />
            <span class="font-medium">{{ userToView?.email }}</span>
          </div>
          <div class="flex items-center gap-3">
            <Shield class="w-5 h-5 text-gray-500" />
            <span class="font-medium">{{ userToView?.role }}</span>
          </div>
          <div class="flex items-center gap-3">
            <Calendar class="w-5 h-5 text-gray-500" />
            <span class="font-medium">Joined on {{ formatDate(userToView?.created_at) }}</span>
          </div>
        </div>        
        <DialogFooter>
          <Button class="cursor-pointer" type="button" @click="closeViewDialog">
            Close
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <AlertDialog v-model:open="showDeleteDialog">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Are you sure?</AlertDialogTitle>
          <AlertDialogDescription>
            This will permanently delete <strong>{{ userToDelete?.name }}</strong> from the system. 
            This action cannot be undone.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Cancel</AlertDialogCancel>
          <AlertDialogAction @click="confirmDeleteUser" class="bg-red-600 hover:bg-red-700">
            Delete
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>

    <!-- Edit User Dialog -->
    <Dialog v-model:open="showEditDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Edit User</DialogTitle>
          <DialogDescription>
            Update role and status for <strong>{{ userToEdit?.name }}</strong>
          </DialogDescription>
        </DialogHeader>
        
        <div class="grid gap-4 py-4">
          <!-- Email (Read-only) -->
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <input
              id="email"
              type="email"
              :value="userToEdit?.email"
              disabled
              class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
            />
          </div>

          <!-- Role Select -->
          <div class="grid gap-2">
            <Label for="role">Role</Label>
            <Select v-model="editForm.role">
              <SelectTrigger id="role">
                <SelectValue placeholder="Select role" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="Admin">Admin</SelectItem>
                <SelectItem value="User">User</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Status Select -->
          <div class="grid gap-2">
            <Label for="status">Status</Label>
            <Select v-model="editForm.status">
              <SelectTrigger id="status">
                <SelectValue placeholder="Select status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="Active">Active</SelectItem>
                <SelectItem value="Inactive">Inactive</SelectItem>
                <SelectItem value="Blocked">Blocked</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="closeEditDialog">
            Cancel
          </Button>
          <Button type="button" @click="confirmEditUser">
            Save Changes
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>