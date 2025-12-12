    <script setup>
    import { ref, computed, reactive, onMounted, watch } from "vue"
    import { storeToRefs } from "pinia"
    import Button from "@/components/ui/button/Button.vue"
    import Input from "@/components/ui/input/Input.vue"
    import { useEventStore } from "@/stores/events"
    import { useCategoriesStore } from "@/stores/categories"
    import EventFormDialog from "@/components/forms/EventFormDialog.vue"
    import EventPreview from "@/components/others/EventPreview.vue"
    import ConfirmDialog from "@/components/others/ConfirmDialog.vue"
    import EventsTable from "@/components/tables/EventsTable.vue"
    import EventDashboardCard from "@/components/cards/EventDashboardCard.vue"
    import {
        Calendar,
        Plus, CheckCircle2, CalendarDays, Megaphone, Search,
        ChevronLeft, ChevronRight
    } from "lucide-vue-next";
    // Stores
    const eventStore = useEventStore()
    const categoriesStore = useCategoriesStore()
    const { events, isLoading, errors } = storeToRefs(eventStore)
    const { eventCategories } = storeToRefs(categoriesStore)

    // Dialog States
    const isEventFormOpen = ref(false)
    const isPreviewDialogOpen = ref(false)
    const isConfirmDeleteOpen = ref(false)
    const eventToDelete = ref(null)
    const searchTerm = ref("")
    const currentPage = ref(1)
    const pageSize = 10

    onMounted(() => {
        eventStore.getEvents()
        categoriesStore.getEventCategories()
    })


    // Event Model
    const newEvent = reactive({
        id: null,
        category_id: "",
        title: "",
        location: "",
        description: "",
        content: "",
        event_time: "",
        event_date: "",
        bannerImageUrl: null,
    })

    function resetEventForm() {
        Object.assign(newEvent, {
            id: null,
            category_id: eventCategories.value?.[0]?.id ? String(eventCategories.value[0].id) : "",
            title: "",
            location: "",
            description: "",
            content: "",
            event_time: "",
            event_date: "",
            bannerImageUrl: null,
        })
    }

    const selectedEvent = ref(null)

    const dashboardCards = computed(() => {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const allEvents = events.value || []

        const totalEvents = allEvents.length

        const completedEvents = allEvents.filter(e => {
            const d = new Date(e.event_date)
            d.setHours(0, 0, 0, 0)
            return d < today
        }).length

        const now = new Date()
        const month = now.getMonth()
        const year = now.getFullYear()
        const thisMonthEvents = allEvents.filter(e => {
            const d = new Date(e.event_date)
            return d.getMonth() === month && d.getFullYear() === year
        }).length

        const upcomingEvents = allEvents.filter(e => {
            const d = new Date(e.event_date)
            d.setHours(0, 0, 0, 0)
            return d >= today
        }).length

        return [
            { title: "Total Events", value: totalEvents, icon: Megaphone },
            { title: "This Month's Events", value: thisMonthEvents, icon: Calendar },
            { title: "Completed Events", value: completedEvents, icon: CheckCircle2 },
            { title: "Upcoming", value: upcomingEvents, icon: CalendarDays },
        ]
    })

    const filteredEvents = computed(() => {
        const term = searchTerm.value.trim().toLowerCase()
        if (!term) return events.value || []
        return (events.value || []).filter(e => {
            const haystack = [
                e.title,
                e.description,
                e.location,
                e.event_date,
                e.event_time,
            ]
                .filter(Boolean)
                .join(" ")
                .toLowerCase()
            return haystack.includes(term)
        })
    })

    const totalPages = computed(() => {
        const total = filteredEvents.value.length
        return total === 0 ? 1 : Math.ceil(total / pageSize)
    })

    const paginatedEvents = computed(() => {
        const start = (currentPage.value - 1) * pageSize
        return filteredEvents.value.slice(start, start + pageSize)
    })

    function goToPage(page) {
        if (page < 1 || page > totalPages.value) return
        currentPage.value = page
    }

    function nextPage() {
        goToPage(currentPage.value + 1)
    }

    function prevPage() {
        goToPage(currentPage.value - 1)
    }
    // Dialog handlers
    function openCreateDialog() {
        resetEventForm()
        isEventFormOpen.value = true
    }

    function openEditDialog(event) {
        resetEventForm()
        Object.assign(newEvent, {
            id: event.id,
            category_id: String(event.category_id ?? ""),
            title: event.title,
            location: event.location,
            description: event.description,
            content: event.content,
            event_time: event.event_time,
            event_date: event.event_date,
            bannerImageUrl: event.image || event.bannerImageUrl,
        })
        isEventFormOpen.value = true
    }

    function openPreviewDialog(event) {
        selectedEvent.value = event
        isPreviewDialogOpen.value = true
    }

    // CRUD
    async function saveEvent(uploadedFile) {
        if (!newEvent.title || !newEvent.event_date || !newEvent.location) {
            return
        }

        const formData = new FormData()
        Object.entries({
            title: newEvent.title,
            description: newEvent.description,
            content: newEvent.content,
            location: newEvent.location,
            event_time: newEvent.event_time,
            event_date: newEvent.event_date,
            category_id: newEvent.category_id ? Number(newEvent.category_id) : "",
        }).forEach(([k, v]) => formData.append(k, v ?? ""))

        if (uploadedFile) formData.append("image", uploadedFile)

        newEvent.id
            ? await eventStore.updateEvent(newEvent.id, formData)
            : await eventStore.addEvent(formData)

        if (Object.keys(errors.value).length === 0) {
            isEventFormOpen.value = false
            resetEventForm()
            currentPage.value = 1
        }
    }

    async function deleteEvent(id) {
        eventToDelete.value = id
        isConfirmDeleteOpen.value = true
    }

    async function confirmDelete() {
        if (!eventToDelete.value) return
        await eventStore.deleteEvent(eventToDelete.value)
        eventToDelete.value = null
        isConfirmDeleteOpen.value = false
    }

    function cancelDelete() {
        eventToDelete.value = null
        isConfirmDeleteOpen.value = false
    }

    // Pagination guards
    watch(searchTerm, () => {
        currentPage.value = 1
    })

    watch(filteredEvents, () => {
        if (currentPage.value > totalPages.value) {
            currentPage.value = totalPages.value
        }
    })

    // Default category when available
    watch(eventCategories, (cats) => {
        if (!newEvent.category_id && cats?.length) {
            newEvent.category_id = String(cats[0].id)
        }
    }, { immediate: true })
    </script>
        

    <template>
        <div class="p-6 md:p-10 space-y-8 bg-gray-50 min-h-screen">

            <EventDashboardCard  :cards="dashboardCards"/>

            <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <Input
                        v-model="searchTerm"
                        placeholder="Search events..."
                        class="pl-10"
                    />
                    <Search class="size-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <Button @click="openCreateDialog" class="bg-green-600 text-white flex gap-1">
                        <Plus class="size-4" /> New Event
                    </Button>
                </div>
            </section>

            <EventsTable 
                :events="paginatedEvents"
                @edit="openEditDialog"
                @preview="openPreviewDialog"
                @delete="deleteEvent"
            />

            <!-- Pagination (bottom, centered) -->
            <div class="flex flex-col items-center gap-3">
                <div class="text-sm text-gray-600">Page {{ currentPage }} / {{ totalPages }}</div>
                <div class="flex gap-2">
                    <Button variant="outline" size="icon" @click="prevPage" :disabled="currentPage === 1">
                        <span class="sr-only">Prev</span>
                        <ChevronLeft class="size-4" />
                    </Button>
                    <Button variant="outline" size="icon" @click="nextPage" :disabled="currentPage === totalPages">
                        <span class="sr-only">Next</span>
                        <ChevronRight class="size-4" />
                    </Button>
                </div>
            </div>

            <!-- Form Dialog -->
            <EventFormDialog
                v-model="isEventFormOpen"
                :event="newEvent"
                :categories="eventCategories"
                :is-loading="isLoading"
                :title="newEvent.id ? 'Edit Event' : 'Create Event'"
                @save="saveEvent"
            />

            <!-- Preview Dialog -->
            <EventPreview 
                v-model="isPreviewDialogOpen"
                :event="selectedEvent"
            />

            <!-- Confirm Delete Dialog -->
            <ConfirmDialog
                :is-open="isConfirmDeleteOpen"
                title="Delete Event"
                message="Are you sure you want to delete this event? This action cannot be undone."
                confirm-text="Delete"
                cancel-text="Cancel"
                is-dangerous
                @confirm="confirmDelete"
                @cancel="cancelDelete"
            />

        </div>
    </template>
