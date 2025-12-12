<script setup>
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import Button from "@/components/ui/button/Button.vue";
import { Clock, MapPin, Calendar } from "lucide-vue-next";

const props = defineProps({
    modelValue: { type: Boolean, default: false },
    event: { type: Object, default: null }
})

const emit = defineEmits(["update:modelValue"])

const close = () => emit("update:modelValue", false)
</script>

<template>
    <Dialog 
        :open="modelValue" 
        @update:open="emit('update:modelValue', $event)"
    >
        <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto p-6">
            <div v-if="event" class="space-y-6">
                <!-- Banner Image -->
                <div v-if="event.image || event.bannerImageUrl" class="w-full h-56 overflow-hidden rounded-lg">
                    <img 
                        :src="event.image || event.bannerImageUrl"
                        :alt="event.title"
                        class="w-full h-full object-cover"
                    />
                </div>

                <div v-else class="w-full h-56 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center rounded-lg">
                    <span class="text-gray-400 italic">No Image Available</span>
                </div>

                <!-- Event Details -->
                <div>
                    <DialogHeader class="border-b pb-4 mb-4">
                        <DialogTitle class="text-4xl font-bold text-gray-900">{{ event.title }}</DialogTitle>
                    </DialogHeader>

                    <!-- Meta Information -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-3 text-gray-700">
                            <Calendar class="size-5 text-green-600 flex-shrink-0" />
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Date</p>
                                <p class="font-semibold">{{ event.event_date }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <Clock class="size-5 text-green-600 flex-shrink-0" />
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Time</p>
                                <p class="font-semibold">{{ event.event_time }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <MapPin class="size-5 text-green-600 flex-shrink-0" />
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Location</p>
                                <p class="font-semibold">{{ event.location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description and Content -->
                    <div class="space-y-5">
                        <div v-if="event.description" class="text-gray-700">
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">Description</h3>
                            <p class="text-sm leading-relaxed text-gray-600">{{ event.description }}</p>
                        </div>
                        
                        <div v-if="event.content" class="text-gray-700">
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">Content</h3>
                            <p class="text-sm leading-relaxed text-gray-600">{{ event.content }}</p>
                        </div>
                    </div>
                </div>

                <!-- Close Button at Bottom -->
                <div class="flex gap-3 pt-6 border-t">
                    <Button 
                        @click="close"
                        class="w-full bg-green-600 text-white hover:bg-green-700"
                    >
                        Close
                    </Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
