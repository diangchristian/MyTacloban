<script setup>
import { ref, computed } from 'vue';
import Button from "@/components/ui/button/Button.vue";
import { 
    Plus, Pencil, Trash2, Calendar, Megaphone, 
    Settings, Save, XCircle, FileText 
} from 'lucide-vue-next'; 
import { Image as ImageIcon } from 'lucide-vue-next';

// --- Category Manager Reactive States ---
const eventCategories = ref([
    { id: 101, name: 'Health' },
    { id: 102, name: 'Sports' },
]);
const announcementCategories = ref([
    { id: 201, name: 'General Notice' },
    { id: 202, name: 'Advisory' },
    { id: 203, name: 'Maintenance' },
]);

const activeType = ref('events');
const categoryInput = ref('');
const editingCategory = ref(null);
const isFormVisible = ref(false);

// --- Category Manager Computed Properties ---
const activeCategories = computed(() => {
    return activeType.value === 'events' ? eventCategories.value : announcementCategories.value;
});

const headerTitle = computed(() => {
    return activeType.value === 'events' ? 'Event Categories' : 'Announcement Categories';
});

const formTitle = computed(() => {
    return editingCategory.value ? `Edit ${activeType.value === 'events' ? 'Event' : 'Announcement'} Category` : 'Category Name';
});

// Category Counts for Tabs
const eventCount = computed(() => eventCategories.value.length);
const announcementCount = computed(() => announcementCategories.value.length);

// --- Category Manager Methods (omitted for brevity, they remain unchanged) ---
const switchType = (type) => {
    activeType.value = type;
    cancelOperation();
};

const toggleForm = () => {
    if (!isFormVisible.value) {
        categoryInput.value = '';
        editingCategory.value = null; 
        isFormVisible.value = true;
    } else {
        cancelOperation();
    }
};

const saveCategory = () => {
    const trimmedName = categoryInput.value.trim();
    if (!trimmedName) return;

    const currentList = activeType.value === 'events' ? eventCategories.value : announcementCategories.value;

    if (editingCategory.value) {
        const categoryToUpdate = currentList.find(c => c.id === editingCategory.value.id);
        if (categoryToUpdate) {
            categoryToUpdate.name = trimmedName;
        }
    } else {
        const newId = Date.now();
        currentList.push({ id: newId, name: trimmedName });
    }

    cancelOperation();
};

const editCategory = (category) => {
    editingCategory.value = category;
    categoryInput.value = category.name;
    isFormVisible.value = true;
};

const cancelOperation = () => {
    categoryInput.value = '';
    editingCategory.value = null;
    isFormVisible.value = false;
};

const deleteCategory = (id) => {
    const currentList = activeType.value === 'events' ? eventCategories.value : announcementCategories.value;
    const index = currentList.findIndex(c => c.id === id);
    if (index !== -1) {
        currentList.splice(index, 1);
    }
    
    if (editingCategory.value && editingCategory.value.id === id) {
        cancelOperation();
    }
};

// --- General System Settings Reactive States (Simple Mockup) ---
const systemName = ref('MyTacloban');
const systemDescription = ref('Please provide a detailed information about the system, including what it does and how it affects the community.');
// 🚨 NEW STATE for file handling 🚨
const uploadedLogoName = ref(null); 

const saveSettings = () => {
    console.log("Settings Saved:", { 
        name: systemName.value, 
        description: systemDescription.value, 
        logo: uploadedLogoName.value 
    });
    // In a real app, this is where you'd send data to an API
};

// 🚨 NEW METHOD for file handling 🚨
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        uploadedLogoName.value = file.name;
        // In a real application, you would now upload the file to your server here
        console.log("File selected:", file.name);
    }
};
</script>

<template>
    <div class="space-y-12 bg-gray-50 min-h-screen p-4 sm:p-8">
        
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-xl rounded-2xl border border-gray-100">
            <header class="mb-6 border-b pb-4 flex items-center gap-3">
                <Settings class="size-6 text-gray-600" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">General System Settings</h2>
                    <p class="text-sm text-gray-500">Configure basic system information and preferences.</p>
                </div>
            </header>

            <div class="space-y-6">
                <div>
                    <label for="system-name" class="block text-sm font-semibold text-gray-700 mb-1">System Name</label>
                    <input 
                        v-model="systemName"
                        type="text"
                        id="system-name"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                    />
                </div>

                <div>
                    <label for="logo-upload" class="block text-sm font-semibold text-gray-700 mb-1">City/LGU Logo</label>
                    
                    <input 
                        type="file" 
                        id="logo-upload" 
                        accept="image/*" 
                        @change="handleFileUpload" 
                        class="hidden" 
                    />

                    <label for="logo-upload" 
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                        :class="[uploadedLogoName 
                            ? 'border-green-500 bg-green-50 hover:bg-green-100' 
                            : 'border-green-300 bg-green-50/50 hover:bg-green-100/70']"
                    >
                        <div v-if="uploadedLogoName" class="text-center text-green-700 p-4">
                            <FileText class="mx-auto size-8 mb-2" />
                            <p class="text-base font-medium truncate">{{ uploadedLogoName }}</p>
                            <p class="text-sm text-green-600 mt-1">Click to change logo</p>
                        </div>
                        <div v-else class="text-center text-gray-500">
                            <ImageIcon class="mx-auto size-8 text-green-500" />
                            <p class="mt-2 text-sm font-medium">Click to upload or drag & drop</p>
                        </div>
                    </label>
                </div>

                <div>
                    <label for="system-description" class="block text-sm font-semibold text-gray-700 mb-1">About/Description</label>
                    <textarea
                        v-model="systemDescription"
                        id="system-description"
                        rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                    ></textarea>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100">
                <Button 
                    @click="saveSettings"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl transition-colors font-semibold shadow-md shadow-green-200"
                >
                    <Save class="size-5 mr-2" /> Save Changes
                </Button>
            </div>
        </div>
        
        <hr class="border-gray-200 max-w-4xl mx-auto" />

        <div class="max-w-4xl mx-auto p-6 bg-white shadow-xl rounded-2xl border border-gray-100">
            
            <header class="mb-6 border-b pb-4 flex items-center gap-3">
                <Megaphone class="size-6 text-gray-600" /> 
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Event and Announcement Categories</h2>
                    <p class="text-sm text-gray-500">Manage the tags used to organize events and announcements.</p>
                </div>
            </header>
            
            <div class="flex flex-col sm:flex-row justify-between sm:items-end mb-6 border-b pb-0">
                
                <div class="flex">
                    <button
                        @click="switchType('events')"
                        :disabled="isFormVisible"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-semibold transition-colors disabled:opacity-50"
                        :class="activeType === 'events' 
                            ? 'text-green-600 border-b-2 border-green-600' 
                            : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent'"
                    >
                        <Calendar class="size-4" /> 
                        Events 
                        <span class="text-xs font-normal px-2 py-0.5 rounded-full bg-green-50 text-green-600">{{ eventCount }}</span>
                    </button>
                    <button
                        @click="switchType('announcements')"
                        :disabled="isFormVisible"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-semibold transition-colors disabled:opacity-50"
                        :class="activeType === 'announcements' 
                            ? 'text-green-600 border-b-2 border-green-600' 
                            : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent'"
                    >
                        <Megaphone class="size-4" /> 
                        Announcements
                        <span class="text-xs font-normal px-2 py-0.5 rounded-full bg-green-50 text-green-600">{{ announcementCount }}</span>
                    </button>
                </div>
                
                <div class="pt-4 sm:pt-0 pb-4 sm:pb-3">
                    <Button 
                        @click="toggleForm" 
                        v-if="!isFormVisible"
                        class="bg-green-500 hover:bg-green-600 text-white flex items-center gap-1 px-4 py-2 rounded-xl transition-colors font-semibold"
                    >
                        <Plus class="size-5" /> Add New
                    </Button>
                </div>
            </div>

            <div v-if="isFormVisible" class="bg-green-50 p-4 rounded-xl mb-6 border border-green-200 shadow-inner">
                
                <h3 class="text-lg font-bold text-green-800 mb-3">Category Adding</h3> 

                <label for="category-input" class="block text-sm font-semibold text-green-800 mb-2">{{ formTitle }}</label>
                <input 
                    v-model="categoryInput"
                    type="text"
                    id="category-input"
                    class="w-full p-3 border border-green-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 mb-4 transition-all text-gray-800"
                    :placeholder="formTitle"
                    @keyup.enter="saveCategory"
                />
                
                <div class="flex gap-3">
                    <Button 
                        @click="saveCategory"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
                        :disabled="!categoryInput.trim()"
                    >
                        <Save class="size-4 mr-1" /> Save
                    </Button>
                    <Button 
                        @click="cancelOperation"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors flex items-center"
                    >
                        <XCircle class="size-4 mr-1" /> Cancel
                    </Button>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <div 
                    v-for="category in activeCategories" 
                    :key="category.id"
                    class="flex justify-between items-center p-3 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow"
                >
                    <span class="text-gray-800 font-medium text-base">{{ category.name }}</span>
                    <div class="flex gap-2">
                        <button 
                            @click="editCategory(category)" 
                            class="p-2 text-blue-500 hover:bg-blue-50 transition-colors rounded-full disabled:opacity-50"
                            aria-label="Edit category"
                            :disabled="isFormVisible"
                        >
                            <Pencil class="size-4" />
                        </button>
                        <button 
                            @click="deleteCategory(category.id)" 
                            class="p-2 text-red-500 hover:bg-red-50 transition-colors rounded-full disabled:opacity-50"
                            aria-label="Delete category"
                            :disabled="isFormVisible"
                        >
                            <Trash2 class="size-4" />
                        </button>
                    </div>
                </div>
            </div>

            <p v-if="activeCategories.length === 0" class="text-center text-gray-500 mt-8 p-6 border-dashed border-2 border-gray-300 rounded-lg bg-gray-50">
                No {{ activeType }} categories have been added yet. Click 'Add New' to create one.
            </p>

        </div>
    </div>
</template>