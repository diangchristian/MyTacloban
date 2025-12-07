<script setup lang="ts">
import { ref, computed, watch } from "vue";
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
  SquarePen, Trash2, Eye, Plus, LineChart, ListChecks, Megaphone, Upload
} from "lucide-vue-next";
import Input from "@/components/ui/input/Input.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Select from "@/components/ui/select/Select.vue";
import EventsCard from "@/components/cards/EventsCard.vue";
import SelectContent from "@/components/ui/select/SelectContent.vue";
import SelectItem from "@/components/ui/select/SelectItem.vue";
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue";
import SelectValue from "@/components/ui/select/SelectValue.vue";
import Label from "@/components/ui/label/Label.vue";

const initialEvents = [
  {
    id: 1,
    category: "Health",
    title: "Free Medical Mission",
    location: "Barangay 50 Covered Court",
    description: "Free medical services for all residents.",
    content: "The City Health Office is sponsoring a massive free medical mission...",
    time: "8:00 AM – 3:00 PM",
    date: "2025-11-19",
    status: "Published",
    bannerImageUrl: null,
  },
  {
    id: 2,
    category: "Sports",
    title: "Basketball Finals",
    location: "City Gym",
    description: "Inter-barangay championship games.",
    content: "Come and watch the exciting finale of the inter-barangay basketball tournament...",
    time: "6:00 PM – 10:00 PM",
    date: "2025-11-25",
    status: "Published",
    bannerImageUrl: null,
  },
  {
    id: 3,
    category: "Community",
    title: "Clean-Up Drive",
    location: "Nula-Tula Seawall",
    description: "Join us in keeping our community clean.",
    content: "The City Environment and Natural Resources Office (CENRO) is organizing a large-scale clean-up drive...",
    time: "7:00 AM – 11:00 AM",
    date: "2025-12-05",
    status: "Pending",
    bannerImageUrl: null,
  },
  {
    id: 4,
    category: "Education",
    title: "Scholarship Orientation",
    location: "City Hall Lobby",
    description: "Information on available scholarships.",
    content: "An orientation session will be held for students interested in applying for city-funded scholarships...",
    time: "2:00 PM – 4:00 PM",
    date: "2025-12-10",
    status: "Draft",
    bannerImageUrl: null,
  },
];

const events = ref(initialEvents);
const isEventFormOpen = ref(false);
const isPreviewDialogOpen = ref(false);
const selectedEvent = ref(null);
const formTitle = computed(() => selectedEvent.value && selectedEvent.value.id !== null ? 'Edit Event' : 'Create New Event');

const totalEvents = computed(() => events.value.length);
const publishedEvents = computed(() => events.value.filter(e => e.status === 'Published').length);
const draftEvents = computed(() => events.value.filter(e => e.status === 'Draft').length);
const upcomingEvents = computed(() => {
  const now = new Date();
  return events.value.filter(e => new Date(e.date) >= now).length;
});

const dashboardCards = computed(() => [
  { title: "Total Events", value: totalEvents.value, icon: Megaphone, color: "text-blue-600", bg: "bg-blue-50" },
  { title: "Published", value: publishedEvents.value, icon: ListChecks, color: "text-green-600", bg: "bg-green-50" },
  { title: "Drafts", value: draftEvents.value, icon: SquarePen, color: "text-yellow-600", bg: "bg-yellow-50" },
  { title: "Upcoming", value: upcomingEvents.value, icon: Calendar, color: "text-purple-600", bg: "bg-purple-50" },
]);

const newEvent = ref({
  id: null,
  category: 'Community',
  title: '',
  location: '',
  description: '',
  content: '',
  time: '9:00 AM – 5:00 PM',
  date: new Date().toISOString().slice(0, 10),
  status: 'Draft',
  bannerImageUrl: null as string | null,
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

watch(selectedFile, (newFile, oldFile) => {
  if (oldFile) {
    URL.revokeObjectURL(URL.createObjectURL(oldFile))
  }
}, { immediate: true })

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
        title: '',
        location: '',
        description: '',
        content: '',
        time: '9:00 AM – 5:00 PM',
        date: new Date().toISOString().slice(0, 10),
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

function openEditDialog(event: any) {
  newEvent.value = { ...event };
  selectedFile.value = null;
  selectedEvent.value = event;
  isEventFormOpen.value = true;
}

function openPreviewDialog(event: any) {
  selectedEvent.value = event;
  isPreviewDialogOpen.value = true;
}

function handleReadMore(event: any) {
  selectedEvent.value = event;
  isPreviewDialogOpen.value = true;
}

function saveEvent() {
  if (!newEvent.value.title || !newEvent.value.date || !newEvent.value.location) {
    alert("Please fill in Title, Date, and Location.");
    return;
  }
  
  if (selectedFile.value) {
      newEvent.value.bannerImageUrl = bannerPreviewUrl.value;
  }

  if (newEvent.value.id !== null) {
    const index = events.value.findIndex(e => e.id === newEvent.value.id);
    if (index !== -1) {
      Object.assign(events.value[index], newEvent.value);
    }
  } else {
    const newId = events.value.length > 0 ? Math.max(...events.value.map(e => e.id)) + 1 : 1;
    events.value.push({ ...newEvent.value, id: newId });
  }

  isEventFormOpen.value = false;
  selectedEvent.value = null;
  selectedFile.value = null;
}

function deleteEvent(id: number) {
  if (confirm(`Are you sure you want to delete the event with ID: ${id}?`)) {
    events.value = events.value.filter(e => e.id !== id);
  }
}

function getStatusClasses(status: string) {
  switch (status) {
    case 'Published': return 'bg-green-100 text-green-800';
    case 'Draft': return 'bg-yellow-100 text-yellow-800';
    case 'Pending': return 'bg-blue-100 text-blue-800';
    default: return 'bg-gray-100 text-gray-800';
  }
}
</script>

<template>
  <div class="p-6 md:p-10 space-y-8 bg-gray-50 min-h-screen">
    
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
       <p v-if="events.length === 0" class="text-gray-500 text-center py-10">
          No events found. Click "New Event" to create one.
        </p>
    </section>

    <Dialog v-model:open="isEventFormOpen">
      <DialogContent class="max-w-xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <h2 class="text-2xl font-bold text-gray-900">CREATE EVENT</h2>
        </DialogHeader>
        
        <form @submit.prevent="saveEvent" class="space-y-6 pt-4">
          <div class="space-y-2">
            <Label for="title">Title <span class="text-red-500">*</span></Label>
            <Input id="title" v-model="newEvent.title" placeholder="" required />
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
              <Input id="venue" v-model="newEvent.location" placeholder="" required />
            </div>
            
            <div class="space-y-2">
              <Label for="category">Category</Label>
              <Select v-model="newEvent.category">
                <SelectTrigger class="w-full">
                  <SelectValue placeholder="Select a Category" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="cat in CATEGORY_OPTIONS"
                    :key="cat"
                    :value="cat"
                  >
                    {{ cat }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          
          <div class="space-y-2">
              <Label for="banner-file">Image</Label>
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
                  <Upload class="size-4 ml-2 text-gray-500" />
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
              placeholder=""
              rows="2"
              maxlength="150"
            />
          </div>

          <div class="space-y-2">
            <Label for="content">Full Details</Label>
            <Textarea
              id="content"
              v-model="newEvent.content"
              placeholder=""
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
            <Button type="submit" class="min-w-[80px]">
              Save
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
    
    <Dialog v-model:open="isPreviewDialogOpen">
      <DialogContent class="max-w-2xl max-h-[80vh] overflow-y-auto">
        <DialogHeader v-if="selectedEvent">
          <div class="space-y-2">
            <DialogTitle class="text-2xl font-bold text-gray-900">
              {{ selectedEvent.title }}
            </DialogTitle>
            
            <div class="flex flex-col gap-1 text-sm text-gray-700">
              <div class="flex items-center">
                <Calendar class="size-4 mr-2 text-green-600" />
                <span>{{ selectedEvent.date }}</span>
              </div>
              <div class="flex items-center">
                <Clock class="size-4 mr-2 text-green-600" />
                <span>{{ selectedEvent.time }}</span>
              </div>
              <div class="flex items-center">
                <MapPin class="size-4 mr-2 text-green-600" />
                <span>{{ selectedEvent.location }}</span>
              </div>
            </div>
            
            <div class="text-sm text-green-600 font-bold pt-2">
              {{ selectedEvent.category }}
            </div>
          </div>
        </DialogHeader>
        
        <div class="space-y-4">
          <DialogDescription class="text-base text-gray-700 leading-relaxed">
              <div v-if="selectedEvent.bannerImageUrl" class="mb-4">
                <h4 class="font-semibold mb-2">Banner:</h4>
                <img :src="selectedEvent.bannerImageUrl" alt="Event Banner" class="w-full max-h-40 object-cover rounded-md"/>
              </div>

              <p class="text-gray-900 font-semibold mb-2">Short Description:</p>
              <p class="mb-4">{{ selectedEvent?.description }}</p>

            <p class="text-gray-900 font-semibold mb-2">Full Details:</p>
            <p>{{ selectedEvent?.content }}</p>
          </DialogDescription>
          
          <div class="pt-4 border-t text-sm text-gray-500">
              <p><strong>Status:</strong> {{ selectedEvent?.status }}</p>
              </div>
        </div>
      </DialogContent>
    </Dialog>

  </div>
</template>