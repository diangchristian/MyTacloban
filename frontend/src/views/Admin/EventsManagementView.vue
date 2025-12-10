<script setup>
import { ref, computed, reactive, onMounted } from "vue"
import { storeToRefs } from "pinia"
import Button from "@/components/ui/button/Button.vue"
import { useEventStore } from "@/stores/events"
import EventFormDialog from "@/components/forms/EventFormDialog.vue"
import EventPreview from "@/components/others/EventPreview.vue"
import EventsTable from "@/components/tables/EventsTable.vue"
import EventDashboardCard from "@/components/cards/EventDashboardCard.vue"
import {
  Clock, Calendar, MapPin,
  SquarePen, Trash2, Eye, Plus, ListChecks, Megaphone
} from "lucide-vue-next";
// Store
const eventStore = useEventStore()
const { events, isLoading, errors } = storeToRefs(eventStore)

// Dialog States
const isEventFormOpen = ref(false)
const isPreviewDialogOpen = ref(false)

onMounted(() => {
    eventStore.getEvents()
})


// Event Model
const newEvent = reactive({
    id: null,
    category: "",
    category_id: 1,
    title: "",
    location: "",
    description: "",
    content: "",
    time: "",
    date: "",
    status: "",
    bannerImageUrl: null,
})

function resetEventForm() {
    Object.assign(newEvent, {
        id: null,
        category: "",
        category_id: 1,
        title: "",
        location: "",
        description: "",
        content: "",
        time: "",
        date: "",
        status: "",
        bannerImageUrl: null,
    })
}

const selectedEvent = ref(null)
const selectedFile = ref(null)

const dashboardCards = computed(() => [
                { title: "Total Events", value: 10, icon: Megaphone }, 
                { title: "Published", value: 10, icon: ListChecks }, 
                { title: "Drafts", value: 10, icon: SquarePen }, 
                { title: "Upcoming", value: 10, icon: Calendar },  ])
// Preview URL
const bannerPreviewUrl = computed(() =>
    selectedFile.value ? URL.createObjectURL(selectedFile.value) : newEvent.bannerImageUrl
)

const handleFileUpload = e => {
    selectedFile.value = e.target.files?.[0] || null
}

const triggerFileInput = () => fileInput.value?.click()

// Dialog handlers
function openCreateDialog() {
    resetEventForm()
    isEventFormOpen.value = true
}

function openEditDialog(event) {
    Object.assign(newEvent, event)
    selectedFile.value = null
    isEventFormOpen.value = true
}

function openPreviewDialog(event) {
    selectedEvent.value = event
    isPreviewDialogOpen.value = true
}

function getStatusClasses(status) {
    return (
        {
            Published: "bg-green-100 text-green-800",
            Draft: "bg-yellow-100 text-yellow-800",
            Pending: "bg-blue-100 text-blue-800",
        }[status] || "bg-gray-100 text-gray-800"
    )
}

// CRUD
async function saveEvent() {
    if (!newEvent.title || !newEvent.date || !newEvent.location)
        return alert("Please fill in Title, Date, and Location.")

    const formData = new FormData()
    Object.entries({
        title: newEvent.title,
        description: newEvent.description,
        content: newEvent.content,
        location: newEvent.location,
        event_time: newEvent.time,
        event_date: newEvent.date,
        status: newEvent.status,
        category_id: newEvent.category_id,
    }).forEach(([k, v]) => formData.append(k, v))

    if (selectedFile.value) formData.append("image", selectedFile.value)

    newEvent.id
        ? await eventStore.editEvent(newEvent.id, formData)
        : await eventStore.addEvent(formData)

    if (!errors.value.message) {
        isEventFormOpen.value = false
        resetEventForm()
    }
}

async function deleteEvent(id) {
    if (confirm("Delete this event?")) await eventStore.deleteEvent(id)
}
</script>
    

<template>
    <div class="p-6 md:p-10 space-y-8 bg-gray-50 min-h-screen">

        <EventDashboardCard  :cards="dashboardCards"/>

        <section class="flex justify-end">
            <Button @click="openCreateDialog" class="bg-green-600 text-white flex gap-1">
                <Plus class="size-4" /> New Event
            </Button>
        </section>

        <EventsTable 
            :events="events"
            :getStatusClasses="getStatusClasses"
            @edit="openEditDialog"
            @preview="openPreviewDialog"
            @delete="deleteEvent"
        />

        <!-- Form Dialog -->
        <EventFormDialog
            v-model="isEventFormOpen"
            :event="newEvent"
            @save="saveEvent"
            @cancel="resetEventForm"
        />

        <!-- Preview Dialog -->
        <EventPreview 
            v-model="isPreviewDialogOpen"
            :event="selectedEvent"
            :getStatusClasses="getStatusClasses"
        />
    </div>
</template>
