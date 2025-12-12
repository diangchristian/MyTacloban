<script setup>
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import Input from "@/components/ui/input/Input.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Select from "@/components/ui/select/Select.vue";
import SelectTrigger from "@/components/ui/select/SelectTrigger.vue";
import SelectValue from "@/components/ui/select/SelectValue.vue";
import SelectContent from "@/components/ui/select/SelectContent.vue";
import SelectItem from "@/components/ui/select/SelectItem.vue";
import Label from "@/components/ui/label/Label.vue";
import Button from "@/components/ui/button/Button.vue";
import { Upload, Calendar } from "lucide-vue-next";
import { ref, computed, watch } from "vue";

const props = defineProps({
    modelValue: { type: Boolean, default: false },
    event: { type: Object, required: true },
    title: { type: String, default: "Create Event" },
    categories: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
})

const emit = defineEmits(["update:modelValue", "save"])

const selectedFile = ref(null);
const fileInput = ref(null);
const dateInput = ref(null);

const localCategories = computed(() => props.categories || []);

const bannerPreviewUrl = computed(() =>
    selectedFile.value ? URL.createObjectURL(selectedFile.value) : props.event?.bannerImageUrl
);

// Clear local file selection when dialog closes so stale files are not re-used
watch(() => props.modelValue, (isOpen) => {
    if (!isOpen) {
        selectedFile.value = null;
    }
});

const handleFileUpload = (e) => {
    selectedFile.value = e.target.files?.[0] || null;
};

const triggerFileInput = () => {
    fileInput.value?.click();
};

const triggerDatePicker = () => {
    dateInput.value?.showPicker();
};

const saveEvent = () => {
    emit("save", selectedFile.value);
};

const close = () => emit("update:modelValue", false);
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
                            <div class="relative">
                                <input
                                    id="date"
                                    ref="dateInput"
                                    type="date"
                                    v-model="event.event_date"
                                    required
                                    class="sr-only"
                                />
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="triggerDatePicker"
                                    class="w-full justify-start text-left font-normal"
                                    :class="!event.event_date && 'text-muted-foreground'"
                                >
                                    <Calendar class="mr-2 h-4 w-4" />
                                    {{ event.event_date || 'Pick a date' }}
                                </Button>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="time">Time</Label>
                            <Input id="time" v-model="event.event_time" placeholder="e.g., 6:00 AM – 10:00 AM" />
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
                              <SelectTrigger class="w-full">
                                  <SelectValue placeholder="Select Category" />
                              </SelectTrigger>

                              <SelectContent>
                                  <SelectItem
                                      v-for="cat in localCategories"
                                      :key="cat.id"
                                      :value="String(cat.id)"
                                  >
                                      {{ cat.name || cat.category_name }}
                                  </SelectItem>
                              </SelectContent>
                          </Select>
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
                                    variant="ghost" 
                                    size="icon" 
                                    class="h-6 w-6 p-0 rounded-full"
                                    @click.stop="selectedFile = null; event.bannerImageUrl = null;"
                                >
                                    ✕
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

                    <div class="flex justify-end gap-3 pt-4">
                        <Button 
                            type="button" 
                            variant="outline" 
                            @click="close"
                            class="min-w-[80px]"
                        >
                            Cancel
                        </Button>
                        <Button type="submit" class="min-w-[80px] bg-green-600 hover:bg-green-700" :disabled="isLoading">
                            {{ isLoading ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </form>

        </DialogContent>
    </Dialog>
</template>
