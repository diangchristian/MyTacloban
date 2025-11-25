<script setup>
import { Lightbulb, Waves, Trash2, CarFront, Building, Plus    } from 'lucide-vue-next';
import { ref, computed, reactive, watch } from 'vue';
import ReportsCategory from '@/components/ReportsCategory.vue';
import { Textarea } from '@/components/ui/textarea'
import LocationPicker from '@/components/LocationPicker.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
const categories = [
    {
        value: 'street_light',
        label: 'Street Light',
        bg: 'bg-yellow-400',
        icon: Lightbulb
    },
    {
        value: 'flooding',
        label: 'Flooding',
        bg: 'bg-blue-400',
        icon: Waves
    },
    {
        value: 'garbage_collection',
        label: 'Garbage  Collection',
        bg: 'bg-green-400',
        icon: Trash2
    },
    {
        value: 'road_damage',
        label: 'Road Damage',
        bg: 'bg-black',
        icon: CarFront
    },
    {
        value: 'public_safety',
        label: 'Public Safety',
        bg: 'bg-red-400',
        icon: Building
    },
    {
        value: 'other_issues',
        label: 'Other  Issues',
        bg: 'bg-white',
        icon: Plus
    },
]
const selectedCategory = ref("")
const otherIssueText = ref('');
const requiresOtherInput = computed(() => selectedCategory.value === 'other_issues');

const formData = reactive({
  category: "",
  location: "",
  description: "",
  images: [],
  other_issue: ""
});




watch(selectedCategory, (newVal) => {
  formData.category = newVal;
});

watch(otherIssueText, (newVal) => {
  formData.other_issue = newVal;
});

const handleFiles = (event) => {
    const files = Array.from(event.target.files)
    formData.images = files
}



</script>

<template>
<main>
    <div class="max-w-7xl mx-auto ">
       <form action="" @submit.prevent="console.log(formData)">
            <div class="w-full bg-white p-4">
                <p class="font-semibold">Select Category</p>
                <div class=" grid grid-cols-1  sm:grid-cols-2   md:grid-cols-3   lg:grid-cols-6 rounded-xl shadow-sm gap-4 mt-2">
                    <ReportsCategory v-model="selectedCategory" v-for="category in categories" :key="category.value" :category="category"/>
                </div>
        </div>
            <div class="grid   lg:grid-cols-2 mt-4 gap-4">
                <div class="span-1 bg-white">
                    <LocationPicker v-model="formData.location"/>
                </div>
                <div class="col-span-1 bg-white p-4 space-y-4">
                    <div v-if="requiresOtherInput" class="mt-4">
                        <label class="block text-sm font-medium">Please describe the issue</label>
                        <Input v-model="otherIssueText"
                        type="text"
                        placeholder="Describe the issue..."
                        class="border p-2 rounded w-full mt-2"/>
                        
                    </div>
                    <div class="">
                        <label for="" class="block text-sm font-medium">Description</label>
                        <Textarea placeholder="Type your message here." class="h-40 mt-2" v-model="formData.description"/>
                    </div>
                    <div class="">
                        <label class="block mb-2.5 text-sm font-medium text-heading" for="multiple_files">Upload multiple files</label>
                        <input 
                            class="cursor-pointer  border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" 
                            id="multiple_files"
                            type="file" multiple
                            @change="handleFiles"
                            >
                    </div>
                    <Button class="" type="submit">Submit Report</Button>
                </div>

            </div>
       </form>

    </div>
</main>



</template>