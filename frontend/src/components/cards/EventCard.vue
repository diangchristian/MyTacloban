<script setup>
import { computed } from "vue"
import { Calendar, Clock, MapPin } from "lucide-vue-next"
import Badge from "@/components/ui/badge/Badge.vue"

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
})

const eventImage = computed(() => 
    props.event.image || props.event.bannerImageUrl || '/placeholder-event.jpg'
)

const categoryName = computed(() => 
    props.event.category || props.event.category_name || 'Event'
)
</script>

<template>
    <article 
        class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200 flex flex-col h-full"
    >
        <!-- Event Image -->
        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-green-50 to-green-100">
            <img 
                :src="eventImage"
                :alt="event.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                @error="$event.target.src = '/placeholder-event.jpg'"
            />
            
            <!-- Category Badge Overlay -->
            <div class="absolute top-3 right-3">
                <Badge class="bg-green-600 text-white shadow-lg">
                    {{ categoryName }}
                </Badge>
            </div>
        </div>

        <!-- Event Content -->
        <div class="p-5 flex flex-col flex-1">
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-green-600 transition-colors">
                {{ event.title }}
            </h3>

            <!-- Meta Info -->
            <div class="space-y-2 mb-4 flex-1">
                <!-- Date -->
                <div class="flex items-start gap-2 text-sm text-gray-600">
                    <Calendar class="size-4 text-green-600 mt-0.5 flex-shrink-0" />
                    <span class="font-medium">{{ event.event_date }}</span>
                </div>

                <!-- Time -->
                <div v-if="event.event_time" class="flex items-start gap-2 text-sm text-gray-600">
                    <Clock class="size-4 text-green-600 mt-0.5 flex-shrink-0" />
                    <span>{{ event.event_time }}</span>
                </div>

                <!-- Location -->
                <div class="flex items-start gap-2 text-sm text-gray-600">
                    <MapPin class="size-4 text-green-600 mt-0.5 flex-shrink-0" />
                    <span class="line-clamp-2">{{ event.location }}</span>
                </div>
            </div>

            <!-- Description (if available) -->
            <p v-if="event.description" class="text-sm text-gray-500 line-clamp-2 mb-4">
                {{ event.description }}
            </p>

            <!-- CTA Button -->
            <div class="mt-auto pt-3 border-t border-gray-100">
                <button 
                    class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors text-sm flex items-center justify-center gap-2"
                    @click="$emit('view', event)"
                >
                    View Details
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </article>
</template>
