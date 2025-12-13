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
import { Upload, Calendar, ChevronLeft, ChevronRight } from "lucide-vue-next";
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
const showDatePicker = ref(false);

const localCategories = computed(() => props.categories || []);

const bannerPreviewUrl = computed(() =>
    selectedFile.value ? URL.createObjectURL(selectedFile.value) : props.event?.bannerImageUrl
);

// Date picker helpers - watch for changes to event date
watch(() => props.event?.event_date, (newDate) => {
    if (newDate) {
        const date = new Date(newDate);
        currentPickerMonth.value = date.getMonth();
        currentPickerYear.value = date.getFullYear();
    }
});

const currentPickerMonth = ref(new Date(props.event?.event_date || new Date()).getMonth());
const currentPickerYear = ref(new Date(props.event?.event_date || new Date()).getFullYear());

const daysInMonth = computed(() => {
    return new Date(currentPickerYear.value, currentPickerMonth.value + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
    return new Date(currentPickerYear.value, currentPickerMonth.value, 1).getDay();
});

const monthName = computed(() => {
    return new Date(currentPickerYear.value, currentPickerMonth.value).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
});

const calendarDays = computed(() => {
    const days = [];
    for (let i = 0; i < firstDayOfMonth.value; i++) {
        days.push(null);
    }
    for (let i = 1; i <= daysInMonth.value; i++) {
        days.push(i);
    }
    return days;
});

const prevMonth = () => {
    if (currentPickerMonth.value === 0) {
        currentPickerMonth.value = 11;
        currentPickerYear.value--;
    } else {
        currentPickerMonth.value--;
    }
};

const nextMonth = () => {
    if (currentPickerMonth.value === 11) {
        currentPickerMonth.value = 0;
        currentPickerYear.value++;
    } else {
        currentPickerMonth.value++;
    }
};

const selectDate = (day) => {
    // Construct a local date string (YYYY-MM-DD) to avoid timezone shifts
    const y = currentPickerYear.value;
    const m = String(currentPickerMonth.value + 1).padStart(2, '0');
    const d = String(day).padStart(2, '0');
    props.event.event_date = `${y}-${m}-${d}`;
    showDatePicker.value = false;
};

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
                        <input 
                            id="title" 
                            v-model="event.title" 
                            type="text"
                            required 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                        />
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
                                    @click="showDatePicker = !showDatePicker"
                                    class="w-full justify-start text-left font-normal"
                                    :class="!event.event_date && 'text-muted-foreground'"
                                >
                                    <Calendar class="mr-2 h-4 w-4" />
                                    {{ event.event_date || 'Pick a date' }}
                                </Button>

                                <!-- Lucide Calendar Picker Popup -->
                                <div v-if="showDatePicker" class="absolute top-full left-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg p-4 z-50 w-72">
                                    <!-- Month/Year Navigation -->
                                    <div class="flex items-center justify-between mb-4">
                                        <Button type="button" variant="ghost" size="icon" @click="prevMonth" class="h-8 w-8">
                                            <ChevronLeft class="size-4" />
                                        </Button>
                                        <span class="font-semibold text-gray-900">{{ monthName }}</span>
                                        <Button type="button" variant="ghost" size="icon" @click="nextMonth" class="h-8 w-8">
                                            <ChevronRight class="size-4" />
                                        </Button>
                                    </div>

                                    <!-- Day Headers -->
                                    <div class="grid grid-cols-7 gap-2 mb-2">
                                        <div v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day" class="text-center text-xs font-semibold text-gray-500">
                                            {{ day }}
                                        </div>
                                    </div>

                                    <!-- Days Grid -->
                                    <div class="grid grid-cols-7 gap-2">
                                        <div v-for="(day, index) in calendarDays" :key="index" class="aspect-square">
                                            <button
                                                v-if="day"
                                                type="button"
                                                @click="selectDate(day)"
                                                :class="[
                                                    'w-full h-full rounded text-sm font-medium transition-colors',
                                                    event.event_date === `${currentPickerYear}-${String(currentPickerMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
                                                        ? 'bg-green-600 text-white'
                                                        : 'bg-gray-50 text-gray-900 hover:bg-green-100'
                                                ]"
                                            >
                                                {{ day }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Close button -->
                                    <Button 
                                        type="button" 
                                        variant="ghost" 
                                        @click="showDatePicker = false" 
                                        class="w-full mt-3 text-gray-600 text-sm"
                                    >
                                        Close
                                    </Button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="time">Time</Label>
                            <input 
                                id="time" 
                                v-model="event.event_time" 
                                type="text"
                                placeholder="e.g., 6:00 AM – 10:00 AM"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="venue">Venue <span class="text-red-500">*</span></Label>
                            <input 
                                id="venue" 
                                v-model="event.location" 
                                type="text"
                                required 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            />
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
                        <textarea
                            id="description"
                            v-model="event.description"
                            rows="2"
                            maxlength="150"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="content">Full Details</Label>
                        <textarea
                            id="content"
                            v-model="event.content"
                            rows="5"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
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
