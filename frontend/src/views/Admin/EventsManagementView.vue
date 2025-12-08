<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import Button from "@/components/ui/button/Button.vue";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from "@/components/ui/dialog";

import {
  Clock, Calendar, MapPin,
  SquarePen, Trash2, Eye, Plus, ListChecks, Megaphone, Upload
} from "lucide-vue-next";

import Input from "@/components/ui/input/Input.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Select from "@/components/ui/select/Select.vue";
import SelectContent from "@/components/ui/select/SelectContent.vue";
import SelectItem from "@/components/ui/select/SelectItem.vue";
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue";
import SelectValue from "@/components/ui/select/SelectValue.vue";
import Label from "@/components/ui/label/Label.vue";

import { useEventStore } from "@/stores/events";
import { storeToRefs } from "pinia";

// ⭐ Pinia Store
const eventStore = useEventStore();
const { events } = storeToRefs(eventStore);

onMounted(() => {
  eventStore.getEvents();
});

// -------------------------
// UI STATES
// -------------------------
const isEventFormOpen = ref(false);
const isPreviewDialogOpen = ref(false);
const selectedEvent = ref(null);

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
  bannerImageUrl: null,
});

// -------------------------
// IMAGE UPLOAD
// -------------------------
const selectedFile = ref<File | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

const bannerPreviewUrl = computed(() =>
  selectedFile.value
    ? URL.createObjectURL(selectedFile.value)
    : newEvent.value.bannerImageUrl || null
);

watch(selectedFile, (newFile, oldFile) => {
  if (oldFile) {
    URL.revokeObjectURL(URL.createObjectURL(oldFile));
  }
});

const handleFileUpload = (e: Event) => {
  const target = e.target as HTMLInputElement;
  selectedFile.value = target.files?.[0] || null;
};

const triggerFileInput = () => fileInput.value?.click();

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

// -------------------------
// OPEN / CLOSE DIALOGS
// -------------------------
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

// -------------------------
// CRUD OPERATIONS (PINIA)
// -------------------------
async function saveEvent() {
  if (!newEvent.value.title || !newEvent.value.date || !newEvent.value.location) {
    alert("Please fill in Title, Date, and Location.");
    return;
  }

  // Handle image preview
  if (selectedFile.value) {
    newEvent.value.bannerImageUrl = bannerPreviewUrl.value!;
  }

  if (newEvent.value.id) {
    // UPDATE
    await eventStore.updateEvent(newEvent.value);
  } else {
    // CREATE
    await eventStore.createEvent(newEvent.value);
  }

  isEventFormOpen.value = false;
  resetFormState();
}

async function deleteEvent(id: number) {
  if (confirm(`Delete event #${id}?`)) {
    await eventStore.deleteEvent(id);
  }
}

// -------------------------
// STATS (Dashboard Cards)
// -------------------------
const totalEvents = computed(() => events.value.length);
const publishedEvents = computed(() => events.value.filter(e => e.status === "Published").length);
const draftEvents = computed(() => events.value.filter(e => e.status === "Draft").length);
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

// -------------------------
const CATEGORY_OPTIONS = ["Health", "Sports", "Community", "Education", "Festival", "Culture", "Other"];
const STATUS_OPTIONS = ["Published", "Draft", "Pending"];

function getStatusClasses(status: string) {
  switch (status) {
    case "Published": return "bg-green-100 text-green-800";
    case "Draft": return "bg-yellow-100 text-yellow-800";
    case "Pending": return "bg-blue-100 text-blue-800";
    default: return "bg-gray-100 text-gray-800";
  }
}
</script>
