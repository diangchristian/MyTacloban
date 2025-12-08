<script setup>
import {
Dialog,
DialogContent,
DialogHeader,
DialogTitle,
DialogDescription,
} from "@/components/ui/dialog";
import Input from "@/components/ui/input/Input.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Select from "@/components/ui/select/Select.vue";
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue";
import SelectValue from "@/components/ui/select/SelectValue.vue";
import Label from "@/components/ui/label/Label.vue";

const props = defineProps({
    modelValue: { type: Boolean, default: false }, // v-model value
    event: {type: Object},
    title: { type: String, default: "Create Event" }
})

const emit = defineEmits(["update:modelValue"])

// Close handler
const close = () => emit("update:modelValue", false)
</script>

<template>
    <Dialog :open="modelValue" @update:open="emit('update:modelValue', $event)">
        <DialogContent class="max-w-xl max-h-[90vh] overflow-y-auto">

            <DialogHeader>
                <h2 class="text-2xl font-bold">{{ title }}</h2>
            </DialogHeader>

            <!-- your form -->
            <form @submit.prevent="saveEvent" class="space-y-6 pt-4">
                    <div class="space-y-2">
                        <Label for="title">Title <span class="text-red-500">*</span></Label>
                        <Input id="title" v-model="event.title" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="date">Date <span class="text-red-500">*</span></Label>
                            <Input id="date" type="date" v-model="event.date" required />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="time">Time</Label>
                            <Input id="time" v-model="event.time" placeholder="e.g., 6:00 AM – 10:00 AM" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="venue">Venue <span class="text-red-500">*</span></Label>
                            <Input id="venue" v-model="event.location" required />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="category">Category</Label>
                            <Select v-model="event.category_id">
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
                          <input type="hidden" v-model.number="event.category_id">
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
                            <span v-else-if="event.bannerImageUrl" class="truncate text-gray-700">
                                Existing Image Present
                            </span>
                            <span v-else class="text-gray-500">
                                Click to upload image...
                            </span>
                            <div class="flex items-center gap-2">
                                <Button
                                    v-if="selectedFile || event.bannerImageUrl"
                                    type="button" 
                                    variant="destructive" 
                                    size="icon" 
                                    class="h-6 w-6 p-0 rounded-full"
                                    @click.stop="selectedFile = null; event.bannerImageUrl = null;"
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
                            v-model="event.description"
                            rows="2"
                            maxlength="150"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="content">Full Details</Label>
                        <Textarea
                            id="content"
                            v-model="event.content"
                            rows="5"
                        />
                    </div>
                    
                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <Label for="status">Status</Label>
                        <Select v-model="event.status">
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
</template>
