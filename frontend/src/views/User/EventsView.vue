<script setup>
import { ref, computed, onMounted } from "vue"
import { storeToRefs } from "pinia"
import { useEventStore } from "@/stores/events"
import { useCategoriesStore } from "@/stores/categories"
import EventCard from "@/components/cards/EventCard.vue"
import EventPreview from "@/components/others/EventPreview.vue"
import Input from "@/components/ui/input/Input.vue"
import Select from "@/components/ui/select/Select.vue"
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue"
import SelectValue from "@/components/ui/select/SelectValue.vue"
import SelectContent from "@/components/ui/select/SelectContent.vue"
import SelectItem from "@/components/ui/select/SelectItem.vue"
import { Search, Calendar, Filter } from "lucide-vue-next"

// Stores
const eventStore = useEventStore()
const categoriesStore = useCategoriesStore()
const { events, isLoading } = storeToRefs(eventStore)
const { eventCategories } = storeToRefs(categoriesStore)

// UI State
const searchTerm = ref("")
const selectedCategory = ref("all")
const isPreviewOpen = ref(false)
const selectedEvent = ref(null)

onMounted(() => {
    eventStore.getEvents()
    categoriesStore.getEventCategories()
})

// Computed
const filteredEvents = computed(() => {
    let result = events.value || []
    
    // Only show upcoming events (today and future)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    result = result.filter(e => {
        const eventDate = new Date(e.event_date)
        eventDate.setHours(0, 0, 0, 0)
        return eventDate >= today
    })
    
    // Filter by search term
    const term = searchTerm.value.trim().toLowerCase()
    if (term) {
        result = result.filter(e => {
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
    }
    
    // Filter by category
    if (selectedCategory.value !== "all") {
        result = result.filter(e => String(e.category_id) === selectedCategory.value)
    }
    
    // Sort by date (upcoming first)
    return result.sort((a, b) => {
        const dateA = new Date(a.event_date)
        const dateB = new Date(b.event_date)
        return dateA - dateB
    })
})

const upcomingEvents = computed(() => {
    return filteredEvents.value
})

const pastEvents = computed(() => {
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    return filteredEvents.value.filter(e => {
        const eventDate = new Date(e.event_date)
        eventDate.setHours(0, 0, 0, 0)
        return eventDate < today
    })
})

function viewEvent(event) {
    selectedEvent.value = event
    isPreviewOpen.value = true
}
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50/30">
        <!-- Hero Section -->
        <div class="w-full mx-auto">
            <div class="w-full h-52 overflow-hidden rounded-md relative">
                <img
                    src="@/assets/images/user-bg.jpg"
                    class="w-full h-full object-cover"
                    alt=""
                >
                <div class="w-full h-full bg-gradient-to-l via-green to-primary/70 absolute top-0 left-0 z-2 flex flex-col justify-center px-8 text-white">
                    <h1 class="text-5xl font-bold">EVENTS</h1>
                    <p class="font-semibold">Upcoming schedules and activities in the city</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-gray-50 py-8 px-6">
            <section class="max-w-7xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search & Filter</h3>
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1 relative">
                            <Search class="size-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <Input
                                v-model="searchTerm"
                                placeholder="Search events by title, location, or date..."
                                class="pl-10"
                            />
                        </div>

                        <!-- Category Filter -->
                        <div class="md:w-64 relative">
                            <Filter class="size-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none z-10" />
                            <Select v-model="selectedCategory">
                                <SelectTrigger class="pl-10">
                                    <SelectValue placeholder="All Categories" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Categories</SelectItem>
                                    <SelectItem
                                        v-for="cat in eventCategories"
                                        :key="cat.id"
                                        :value="String(cat.id)"
                                    >
                                        {{ cat.category_name || cat.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="max-w-7xl mx-auto px-6 py-12">
            <div class="text-center text-gray-500">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-green-600 border-t-transparent mb-4"></div>
                <p>Loading events...</p>
            </div>
        </div>

        <!-- Events Grid -->
        <div v-else class="max-w-7xl mx-auto px-6 py-12 space-y-12">
            
            <!-- Upcoming Events -->
            <section v-if="upcomingEvents.length > 0">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-600 text-white p-2 rounded-lg">
                        <Calendar class="size-6" />
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Upcoming Events
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <EventCard
                        v-for="event in upcomingEvents"
                        :key="event.id"
                        :event="event"
                        @view="viewEvent"
                    />
                </div>
            </section>

            <!-- Empty State -->
            <div v-if="upcomingEvents.length === 0" class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <Calendar class="size-10 text-gray-400" />
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Events Found</h3>
                <p class="text-gray-500">
                    {{ searchTerm || selectedCategory !== 'all' 
                        ? 'Try adjusting your search or filters' 
                        : 'Check back soon for upcoming community events' 
                    }}
                </p>
            </div>
        </div>

        <!-- Event Preview Dialog -->
        <EventPreview 
            v-model="isPreviewOpen"
            :event="selectedEvent"
        />
    </div>
</template>