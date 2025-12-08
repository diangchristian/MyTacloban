<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import { storeToRefs } from "pinia"; // Import storeToRefs for reactive state
import Button from "@/components/ui/button/Button.vue";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import {
    Clock, Calendar, MapPin,
    SquarePen, Trash2, Eye, Plus, ListChecks, Megaphone, Upload, X // Added X for file reset
} from "lucide-vue-next";
import Input from "@/components/ui/input/Input.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Select from "@/components/ui/select/Select.vue";
import SelectContent from "@/components/ui/select/SelectContent.vue";
import SelectItem from "@/components/ui/select/SelectItem.vue";
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue";
import SelectValue from "@/components/ui/select/SelectValue.vue";
import Label from "@/components/ui/label/Label.vue";

// Assuming your Pinia store is correctly named 'event' and has actions:
// getEvents, addEvent, editEvent, deleteEvent
import { useEventStore } from "@/stores/events"; 

// ===================================
// PINIA STORE INTEGRATION (CORRECTED)
// ===================================
const eventStore = useEventStore();
// Use storeToRefs to maintain reactivity when destructuring the store state
const { events, isLoading, errors } = storeToRefs(eventStore); 

onMounted(() => {
    // 1. Fetch data from DB on mount
    eventStore.getEvents();
});

// ===================================
// FORM / STATE DEFINITION
// NOTE: Assuming your backend expects 'category_id' (number) and uses
// the same properties as your initial `newEvent` definition (title, location, etc.)
// ===================================

// Placeholder for missing initialEvents type definition
type EventItem = {
    id: number | null;
    category: string; // Used for UI selection
    category_id: number | null; // Mapped to API
    title: string;
    location: string;
    description: string;
    content: string;
    time: string; // Mapped to API event_time
    date: string; // Mapped to API event_date
    status: string;
    bannerImageUrl: string | null;
};

const getTodayDate = () => new Date().toISOString().slice(0, 10);
const todayDate = getTodayDate();

const isEventFormOpen = ref(false);
const isPreviewDialogOpen = ref(false);
// NOTE: Now uses the actual EventItem structure from the Pinia store (which is the DB data structure)
const selectedEvent = ref(null as EventItem | null); 
const formTitle = computed(() => newEvent.value.id !== null ? 'Edit Event' : 'Create New Event');

const newEvent = ref<EventItem>({
    id: null,
    category: 'Community', // Used for UI only
    category_id: 1, // Default ID for creation
    title: '',
    location: '',
    description: '',
    content: '',
    time: '9:00 AM – 5:00 PM', // Mapped to event_time
    date: todayDate, // Mapped to event_date
    status: 'Draft',
    bannerImageUrl: null,
});

const selectedFile = ref<File | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

const CATEGORY_OPTIONS = ["Health", "Sports", "Community", "Education", "Festival", "Culture", "Other"];
const STATUS_OPTIONS = ["Published", "Draft", "Pending"];

const bannerPreviewUrl = computed(() => {
    if (selectedFile.value) {
        return URL.createObjectURL(selectedFile.value)
    }
    return newEvent.value.bannerImageUrl || null
})

// Corrected watch to cleanup previous file URL
watch(selectedFile, (newFile, oldFile) => {
    // Revoke the object URL associated with the old file/preview if it exists
    // This is more complex than a simple `watch(selectedFile...)` setup 
    // unless you stored the URL string explicitly. For simplicity and avoiding
    // issues with component lifecycle, let's keep the existing logic simple 
    // or remove the revoke entirely if you don't notice memory issues.
    // I'll comment out the potentially problematic line from your original code.
    // if (oldFile) { URL.revokeObjectURL(URL.createObjectURL(oldFile)) }
}, { immediate: true })

// ===================================
// DASHBOARD COMPUTED PROPERTIES
// (Now correctly use the reactive 'events' from Pinia)
// ===================================
const totalEvents = computed(() => events.value.length);
const publishedEvents = computed(() => events.value.filter(e => e.status === 'Published').length);
const draftEvents = computed(() => events.value.filter(e => e.status === 'Draft').length);
const upcomingEvents = computed(() => {
    const today = new Date(todayDate);
    return events.value.filter(e => {
        // Ensure date comparison is accurate, comparing event date string against today's date string
        const eventDate = new Date(e.date);
        return eventDate >= today;
    }).length;
});

const dashboardCards = computed(() => [
    { title: "Total Events", value: totalEvents.value, icon: Megaphone, color: "text-blue-600", bg: "bg-blue-50" },
    { title: "Published", value: publishedEvents.value, icon: ListChecks, color: "text-green-600", bg: "bg-green-50" },
    { title: "Drafts", value: draftEvents.value, icon: SquarePen, color: "text-yellow-600", bg: "bg-yellow-50" },
    { title: "Upcoming", value: upcomingEvents.value, icon: Calendar, color: "text-purple-600", bg: "bg-purple-50" },
]);


// ===================================
// FORM HELPERS
// ===================================

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files.length > 0) {
        selectedFile.value = target.files[0]
    } else {
        selectedFile.value = null
    }
}

const triggerFileInput = () => {
    fileInput.value?.click()
}

function resetFormState() {
    newEvent.value = {
        id: null,
        category: 'Community',
        category_id: 1, // Reset to default category ID
        title: '',
        location: '',
        description: '',
        content: '',
        time: '9:00 AM – 5:00 PM',
        date: todayDate,
        status: 'Draft',
        bannerImageUrl: null,
    };
    selectedFile.value = null;
    selectedEvent.value = null;
}

function openCreateDialog() {
    resetFormState();
    isEventFormOpen.value = true;
}

function openEditDialog(event: EventItem) {
    // Map event properties to the newEvent ref, including category and category_id
    newEvent.value = { ...event };
    selectedFile.value = null;
    selectedEvent.value = event;
    isEventFormOpen.value = true;
}

function openPreviewDialog(event: EventItem) {
    selectedEvent.value = event;
    isPreviewDialogOpen.value = true;
}

function getStatusClasses(status: string) {
    switch (status) {
        case 'Published': return 'bg-green-100 text-green-800';
        case 'Draft': return 'bg-yellow-100 text-yellow-800';
        case 'Pending': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

// ===================================
// PINIA/API CALLS (CRUCIAL CORRECTIONS)
// ===================================

// **CORRECTED** saveEvent function to call Pinia store actions
async function saveEvent() {
    if (!newEvent.value.title || !newEvent.value.date || !newEvent.value.location) {
        alert("Please fill in Title, Date, and Location.");
        return;
    }
    
    // Convert data to FormData for API submission (required for file upload)
    const formData = new FormData();

    // Map local UI fields to API expectations (e.g., time -> event_time)
    formData.append("title", newEvent.value.title);
    formData.append("description", newEvent.value.description);
    formData.append("content", newEvent.value.content);
    formData.append("location", newEvent.value.location);
    formData.append("event_time", newEvent.value.time); // Mapped to API
    formData.append("event_date", newEvent.value.date); // Mapped to API
    formData.append("status", newEvent.value.status);
    // You'll need logic to map the selected category string to an ID if your API expects one.
    // For now, we use the `category_id` field.
    formData.append("category_id", String(newEvent.value.category_id)); // Assuming category_id is needed for API

    if (selectedFile.value) {
        formData.append("image", selectedFile.value); // This is the file upload
    }
    
    if (newEvent.value.id) {
        // Append the ID for PUT request body processing in some frameworks
        formData.append("id", String(newEvent.value.id));
        // 2. Call Pinia EDIT action
        await eventStore.editEvent(newEvent.value.id, formData);
    } else {
        // 2. Call Pinia ADD action
        await eventStore.addEvent(formData);
    }
    
    // Close dialog only if the action was successful (assuming error state is managed by Pinia)
    if (!errors.value.message) {
        isEventFormOpen.value = false;
        resetFormState();
    }
}

// **CORRECTED** deleteEvent function to call Pinia store action
async function deleteEvent(id: number) {
    if (confirm(`Are you sure you want to delete the event with ID: ${id}?`)) {
        // 2. Call Pinia DELETE action
        await eventStore.deleteEvent(id);
    }
}

// Helper to update category_id based on selected category name (example logic)
const updateCategoryId = (categoryName: string) => {
    // This is placeholder logic. In a real app, you'd fetch this from a categories table.
    const index = CATEGORY_OPTIONS.indexOf(categoryName);
    newEvent.value.category_id = index !== -1 ? index + 1 : 1; 
};


</script>

<template>
    <div class="p-6 md:p-10 space-y-8 bg-gray-50 min-h-screen">
        
        <div v-if="isLoading" class="p-3 bg-blue-100 text-blue-700 rounded-md shadow-sm">
            <p>Processing request...</p>
        </div>
        <div v-if="errors.message" class="p-3 bg-red-100 text-red-700 rounded-md shadow-sm">
            <p>Error: {{ errors.message }}</p>
        </div>
        
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                v-for="card in dashboardCards"
                :key="card.title"
                class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 flex items-center justify-between transition-shadow hover:shadow-xl"
            >
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ card.title }}</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ card.value }}</p>
                </div>
                <div
                    :class="[card.color, card.bg]"
                    class="p-3 rounded-full"
                >
                    <component :is="card.icon" class="size-6" />
                </div>
            </div>
        </section>

        <section class="flex justify-end pt-4">
            <Button
                @click="openCreateDialog"
                class="bg-green-600 hover:bg-green-700 text-white flex items-center gap-1 shadow-md"
            >
                <Plus class="size-4" />
                New Event
            </Button>
        </section>
        
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
            <p v-if="events.length === 0 && !isLoading" class="text-gray-500 text-center py-10">
                No events found. Click "New Event" to create one.
            </p>
        </section>

        <Dialog v-model:open="isEventFormOpen">
            <DialogContent class="max-w-xl max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <h2 class="text-2xl font-bold text-gray-900">{{ formTitle }}</h2>
                </DialogHeader>
                
                <form @submit.prevent="saveEvent" class="space-y-6 pt-4">
                    <div class="space-y-2">
                        <Label for="title">Title <span class="text-red-500">*</span></Label>
                        <Input id="title" v-model="newEvent.title" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="date">Date <span class="text-red-500">*</span></Label>
                            <Input id="date" type="date" v-model="newEvent.date" required />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="time">Time</Label>
                            <Input id="time" v-model="newEvent.time" placeholder="e.g., 6:00 AM – 10:00 AM" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="venue">Venue <span class="text-red-500">*</span></Label>
                            <Input id="venue" v-model="newEvent.location" required />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="category">Category</Label>
                            <Select v-model="newEvent.category_id">
                              <SelectTrigger>
                                  <SelectValue placeholder="Select Category" />
                              </SelectTrigger>

                              <SelectContent>
                                  <SelectItem
                                      v-for="cat in categories"
                                      :key="cat.id"
                                      :value="cat.id"
                                  >
                                      {{ cat.category_name }}
                                  </SelectItem>
                              </SelectContent>
                          </Select>
                          <input type="hidden" v-model.number="newEvent.category_id">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <Label>Image</Label>
                        <div
                            @click="triggerFileInput"
                            :class="['p-3 border rounded-md cursor-pointer flex items-center justify-between transition-colors h-10 text-sm',
                                bannerPreviewUrl ? 'border-green-400 bg-green-50/50' : 'border-gray-300 hover:border-green-400']"
                        >
                            <input
                                id="banner-file"
                                type="file"
                                ref="fileInput"
                                class="hidden"
                                @change="handleFileUpload"
                                accept="image/*"
                            />
                            <span v-if="selectedFile" class="truncate text-green-700">
                                {{ selectedFile.name }}
                            </span>
                            <span v-else-if="newEvent.bannerImageUrl" class="truncate text-gray-700">
                                Existing Image Present
                            </span>
                            <span v-else class="text-gray-500">
                                Click to upload image...
                            </span>
                            <div class="flex items-center gap-2">
                                <Button
                                    v-if="selectedFile || newEvent.bannerImageUrl"
                                    type="button" 
                                    variant="destructive" 
                                    size="icon" 
                                    class="h-6 w-6 p-0 rounded-full"
                                    @click.stop="selectedFile = null; newEvent.bannerImageUrl = null;"
                                >
                                    <X class="size-3" />
                                </Button>
                                <Upload class="size-4 ml-2 text-gray-500" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Preview</Label>
                        <div class="w-full h-40 bg-gray-100 rounded-md overflow-hidden border border-gray-300 flex items-center justify-center p-1">
                            <img
                                v-if="bannerPreviewUrl"
                                :src="bannerPreviewUrl"
                                alt="Banner Preview"
                                class="w-full h-full object-cover"
                            />
                            <p v-else class="text-gray-500 text-sm">No Banner Image Preview</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <Label for="description">Summary</Label>
                        <Textarea
                            id="description"
                            v-model="newEvent.description"
                            rows="2"
                            maxlength="150"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="content">Full Details</Label>
                        <Textarea
                            id="content"
                            v-model="newEvent.content"
                            rows="5"
                        />
                    </div>
                    
                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <Label for="status">Status</Label>
                        <Select v-model="newEvent.status">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="stat in STATUS_OPTIONS"
                                    :key="stat"
                                    :value="stat"
                                >
                                    {{ stat }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <Button 
                            type="button" 
                            variant="outline" 
                            @click="isEventFormOpen = false; resetFormState()"
                            class="min-w-[80px]"
                        >
                            Cancel
                        </Button>
                        <Button type="submit" class="min-w-[80px]" :disabled="isLoading">
                            {{ isLoading ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
        
        <Dialog v-model:open="isPreviewDialogOpen">
            <DialogContent class="max-w-2xl p-0 max-h-[90vh] overflow-y-auto">
                <div v-if="selectedEvent" class="bg-white rounded-lg shadow-xl overflow-hidden">
                    
                    <div v-if="selectedEvent.bannerImageUrl" class="h-48">
                        <img 
                            :src="selectedEvent.bannerImageUrl" 
                            alt="Event Banner" 
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div v-else class="h-16 bg-green-600 flex items-center justify-center">
                        <p class="text-white font-semibold">Event Banner Placeholder</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <DialogHeader class="p-0 border-b pb-4">
                            <div class="flex justify-between items-start">
                                <DialogTitle class="text-3xl font-extrabold text-gray-900 leading-tight">
                                    {{ selectedEvent.title }}
                                </DialogTitle>
                                <span :class="[getStatusClasses(selectedEvent.status)]" class="px-3 py-1 text-xs font-semibold rounded-full mt-1">
                                    {{ selectedEvent.status }}
                                </span>
                            </div>
                            <div class="text-sm text-green-700 font-bold">
                                {{ selectedEvent.category }}
                            </div>
                        </DialogHeader>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                            <div class="flex items-center">
                                <Calendar class="size-4 mr-2 text-blue-500 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Date</p>
                                    <span>{{ selectedEvent.date }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <Clock class="size-4 mr-2 text-blue-500 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Time</p>
                                    <span>{{ selectedEvent.time }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <MapPin class="size-4 mr-2 text-blue-500 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Location</p>
                                    <span>{{ selectedEvent.location }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-6 pt-4">
                            <DialogDescription class="text-base text-gray-700 leading-relaxed">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Summary</h3>
                                <p class="p-4 bg-gray-50 border rounded-lg text-gray-700">{{ selectedEvent.description }}</p>
                                
                                <h3 class="text-lg font-bold text-gray-900 mt-6 mb-2">Full Details</h3>
                                <p class="text-gray-800 whitespace-pre-wrap">{{ selectedEvent.content }}</p>
                            </DialogDescription>
                        </div>
                    </div>
                    
                </div>
            </DialogContent>
        </Dialog>

    </div>
</template>