<script setup>
    import {
Dialog,
DialogContent,
DialogHeader,
DialogTitle,
DialogDescription,
} from "@/components/ui/dialog";

    const props = defineProps({
        modelValue: { type: Boolean, default: false },   // used by v-model
        event: { type: Object, default: null },
        getStatusClasses: { type: Function, required: true }
    })
    
    const emit = defineEmits(["update:modelValue"])
    
    // closing handler
    const close = () => emit("update:modelValue", false)
    </script>
    
<template>
    <Dialog 
        :open="modelValue" 
        @update:open="emit('update:modelValue', $event)"
    >
        <DialogContent class="max-w-2xl p-0 max-h-[90vh] overflow-y-auto">
            <div v-if="event" class="bg-white rounded-lg shadow-xl">

                <img 
                    v-if="event.bannerImageUrl" 
                    :src="event.bannerImageUrl" 
                    class="h-48 w-full object-cover"
                />

                <div v-else class="h-16 bg-green-600 text-white flex items-center justify-center">
                    Event Banner Placeholder
                </div>

                <div class="p-6 space-y-4">
                    <DialogHeader class="border-b pb-4">
                        <div class="flex justify-between items-center gap-3">
                            <DialogTitle class="text-3xl font-bold">{{ event.title }}</DialogTitle>
                            <span :class="getStatusClasses(event.status)" class="badge">{{ event.status }}</span>
                        </div>
                        <p class="text-sm font-bold text-green-700">{{ event.category }}</p>
                    </DialogHeader>

                    <slot /> <!-- optional extra content -->
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
