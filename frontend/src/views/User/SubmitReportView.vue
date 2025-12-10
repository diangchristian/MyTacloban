<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue';
import ReportsCategory from '@/components/reports/ReportsCategory.vue';
import { Textarea } from '@/components/ui/textarea'
import LocationPicker from '@/components/location/LocationPicker.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import {useUploadStore} from '@/stores/upload'
import {useCategoriesStore} from '@/stores/categories'
import {useSubmitReport} from '@/stores/submitReport'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia';
import FieldError from '@/components/forms/FieldError.vue';



const uploadStore = useUploadStore();
const categoriesStore = useCategoriesStore()
const authStore = useAuthStore()      // access auth store
const userId = authStore.user?.id     


const submitReportStore = useSubmitReport()
const {errors} = storeToRefs(useSubmitReport())
const fileInput = ref('')

const selectedCategory = ref(null)
const otherIssueText = ref('');
const requiresOtherInput = computed(() => selectedCategory.value === 6);




const formData = reactive({
    user_id: userId,
    title:"",
    category: "",
    coordinates: "",
    description: "",
    images: uploadStore.uploadedFiles.map(file => ({ path: file.path, url: file.url })),
    other_issue: ""
});

const resetForm = () => {
  selectedCategory.value = null;
  otherIssueText.value = '';
  uploadStore.clearUploadedFiles(); // assuming you have a method to clear uploaded files
  Object.assign(formData, {
    user_id: userId,           // keep user_id if fixed
    title: '',
    category: '',
    coordinates: '',
    description: '',
    images: [],
    other_issue: ''
  });

  // Clear the actual file input
  if (fileInput.value) {
    fileInput.value.value = null; 
  }
};

onMounted(() => {
    categoriesStore.getReportCategories()
})

watch(
  () => uploadStore.uploadedFiles,
  (newVal) => {
    formData.images = newVal;
  },
  { deep: true }
);


watch(selectedCategory, (newVal) => {
  formData.category = newVal;
});

watch(otherIssueText, (newVal) => {
  formData.other_issue = newVal;
});


const handleSubmit = async () => {
  const result = await submitReportStore.submitReport(formData);

  if (result === true) {
    // reset form, uploaded files, etc.
    resetForm();

  } else if (result && typeof result === 'object') {
    // validation errors returned
    errors.value = result;
  }
};


</script>

<template>
<main>
    <div class="max-w-7xl mx-auto ">
       <form action="" @submit.prevent="handleSubmit">
            <div class="w-full bg-white p-4">
                <p class="font-semibold">Select Category</p>
                <div class=" grid grid-cols-1  sm:grid-cols-2   md:grid-cols-3   lg:grid-cols-6 rounded-xl shadow-sm gap-4 mt-2" >
                <ReportsCategory     v-model="selectedCategory" v-for="category in categoriesStore.reportCategories" :key="category.value" :category="category"/>
                </div>
                <FieldError v-if="errors.category" class="mt-2" :errorMessage="errors.category[0]"/>
        </div>
            <div class="grid   lg:grid-cols-2 mt-4 gap-4">
                <div class="span-1 bg-white">
                    <LocationPicker v-model="formData.coordinates"    :errorMessage="errors.coordinates ? errors.coordinates[0] : ''" />
                   
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
                        <label class="block text-sm font-medium">Report Title</label>
                        <Input v-model="formData.title"
                        type="text"
                        placeholder="Report title..."
                        class="border p-2 rounded w-full mt-2"/>
                        <FieldError v-if="errors.title" class="mt-2" :errorMessage="errors.title[0]"/>


                        <label for="" class="block text-sm font-medium mt-4">Description</label>
                        <Textarea placeholder="Type your message here." class="h-40 mt-2" v-model="formData.description"/>
                        <FieldError v-if="errors.description" class="mt-2" :errorMessage="errors.description[0]"/>

                    </div>
                    <div class="">
                        <label class="block mb-2.5 text-sm font-medium text-heading" for="multiple_files">Upload multiple files</label>
                        <input 
                            class="cursor-pointer  border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" 
                            ref="fileInput"
                            id="multiple_files"
                            type="file" multiple
                            @change=" e => uploadStore.uploadImages([...e.target.files])"
                            >
                    </div>
                    <Button class="" type="submit">Submit Report</Button>
                </div>

            </div>
       </form>

    </div>
  
</main>



</template>