<script setup>
import { reactive, computed, onMounted } from "vue";
import Button from "@/components/ui/button/Button.vue";
import { Plus, Pencil, Trash2, Calendar, Megaphone, Save, XCircle, Info } from "lucide-vue-next";
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {useCategoriesStore} from "@/stores/categories"
import { storeToRefs } from "pinia";
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'
import FieldError from "../forms/FieldError.vue";



const categoriesStore = useCategoriesStore()
const { 
  eventCategories,
  announcementCategories,
  reportCategories,
  eventCount,
  announcementCount,
  reportsCount,
  errors
} = storeToRefs(categoriesStore);
// =====================
// Reactive State
// =====================
const state = reactive({
    categoryId: '',
    activeType: "event",
    categoryInput: "",
    slug: "",
    icon_name: "",
    color: "",
    editingCategory: null,
    isFormVisible: false,
})


onMounted(() => {
    categoriesStore.getAnnouncementCategories()
    categoriesStore.getEventCategories()
    categoriesStore.getReportCategories()
})


// =====================
// Computed
// =====================
const activeCategories = computed(() => {
  return state.activeType === "event"
    ? eventCategories.value
    : state.activeType === "announcement"
    ? announcementCategories.value
    : reportCategories.value;
});


const formTitle = computed(() =>
    state.editingCategory ? "Edit" : "Create"
);


// =====================
// Methods
// =====================
const switchType = (type) => {
    state.activeType = type;
    cancelOperation();
};

const toggleForm = () => {
    if (!state.isFormVisible) {
    state.categoryInput = "";
    state.editingCategory = null;
    state.isFormVisible = true;
    } else {
    cancelOperation();
    }
};

const saveCategory = async () => {
    const trimmed = state.categoryInput.trim();
    const formData = {
        id: state.categoryId,
        name: trimmed,
        type: state.activeType
    }

    if(state.activeType === 'report'){
        formData.slug = state.slug.trim();
        formData.icon_name = state.icon_name.trim();
        formData.color = state.color.trim();
    }

    console.log(formData)

    if(!state.editingCategory ){
        console.log('you are creating state')
        await categoriesStore.createCategory(formData)
    }else{
        console.log('you are editing state')
        await categoriesStore.updateCategory(formData)
    }

    if (Object.values(errors.value).some(arr => arr.length > 0)) {

        return
    // has errors
    }

    cancelOperation()
};

const editCategory = (category) => {
    console.log(category)
    state.categoryId = category.id
    state.editingCategory = category;
    state.slug = category.slug
    state.icon_name = category.icon_name
    state.color = category.color
    state.categoryInput = category.category_name;
    state.isFormVisible = true;
};

const cancelOperation = () => {
    state.categoryInput = "";
    state.slug = ""
    state.icon_name = ""
    state.color = ""
    state.editingCategory = null;
    state.isFormVisible = false;
    errors.value = {}
};

const deleteCategory = (id) => {
    categoriesStore.deleteCategory(id, state.activeType)
};
</script>

    
    <template>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-xl rounded-2xl border">
    
            <header class="mb-6 border-b pb-4 flex items-center gap-3">
                <Megaphone class="size-6 text-gray-600" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Event & Announcement Categories</h2>
                    <p class="text-sm text-gray-500">Manage category tags used across the system.</p>
                </div>
            </header>
    
            <!-- Tabs -->
            <div class="flex justify-between mb-6 border-b">
    
                <div class="flex">
                    <button
                        @click="switchType('event')"
                        :disabled="state.isFormVisible"
                        class="px-4 py-3 font-semibold border-b-2 transition cursor-pointer"
                        :class="state.activeType === 'event' 
                            ? 'text-green-600 border-green-600' 
                            : 'text-gray-500 border-transparent'"
                    >
                        <Calendar class="size-4 inline-block mr-2" />
                        Events ({{ eventCount }})
                    </button>
    
                    <button
                        @click="switchType('announcement')"
                        :disabled="state.isFormVisible"
                        class="px-4 py-3 font-semibold border-b-2 transition cursor-pointer"
                        :class="state.activeType === 'announcement' 
                            ? 'text-green-600 border-green-600' 
                            : 'text-gray-500 border-transparent'"
                    >
                        <Megaphone class="size-4 inline-block mr-2" />
                        Announcements ({{ announcementCount }})
                    </button>
                    <button
                        @click="switchType('report')"
                        :disabled="state.isFormVisible"
                        class="px-4 py-3 font-semibold border-b-2 transition cursor-pointer"
                        :class="state.activeType === 'report' 
                            ? 'text-green-600 border-green-600' 
                            : 'text-gray-500 border-transparent'"
                    >
                        <Megaphone class="size-4 inline-block mr-2" />
                        Reports ({{ reportsCount }})
                    </button>
                </div>
    
                <Button 
                    @click="toggleForm" 
                    v-if="!state.isFormVisible"
                    class="bg-green-500 hover:bg-green-600 text-white flex items-center gap-1 px-4 py-2 rounded-md cursor-pointer"
                >
                    <Plus class="size-5" /> Add New
                </Button>
            </div>
    
            <!-- Form -->
            <div v-if="state.isFormVisible" class="bg-green-50 p-4 rounded-xl mb-6 border">
    
                <h3 class="text-lg font-bold text-green-800 mb-3">{{ formTitle }} Category</h3>
    
              <div class="" >
                <Label class="font-semibold mb-2">Category Name</Label>
                <Input
                    v-model="state.categoryInput"
                    class="w-full p-3 border rounded-lg mb-2 bg-white"
                    :placeholder="formTitle"
                    @keyup.enter="saveCategory"
                />
                <FieldError v-if="errors?.name" :errorMessage="errors?.name[0]" class="mb-2"/>
              </div>
                
                <div class="" v-if="state.activeType === 'report'">
                  <div class="">
                    <Label class="font-semibold mb-2">Category Slug</Label>
                    <Input
                        v-model="state.slug"
                        class="w-full p-3 border rounded-lg mb-4 bg-white"
                        :placeholder="formTitle"
                    />
                    <FieldError v-if="errors?.slug" :errorMessage="errors?.slug[0]" class="mb-2"/>
                  </div>
                  <div class="">
                    <div class="flex items-center gap-2 mb-2">
                        <Label class="font-semibold ">Icon Name</Label>
                        <TooltipProvider>
                            <Tooltip>
                            <TooltipTrigger as-child>
                                <Info class="size-4 text-blue-400"/>
                            </TooltipTrigger>
                            <TooltipContent class="bg-white text-black">
                                <p>Select an icon from <a href="https://lucide.dev/icons/" class="underline text-blue-400">Lucide Icons</a> for this category.</p>
                            </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                                            </div>
                    <Input
                        class="w-full p-3 border rounded-lg mb-4 bg-white"
                        :placeholder="formTitle"
                        v-model="state.icon_name"
                    />
                    <FieldError v-if="errors?.icon_name" :errorMessage="errors?.icon_name[0]" class="mb-2"/>
                  </div>
                  <div class="">
                    <Label class="font-semibold mb-2">Color</Label>
                    <Input
                        v-model="state.color"
                        class="w-full p-3 border rounded-lg mb-4 bg-white"
                        :placeholder="formTitle"
                    />
                    <FieldError v-if="errors?.color" :errorMessage="errors?.color[0]" class="mb-2"/>
                  </div>
                </div>



                <div class="flex gap-3">
                    <Button class="bg-primary text-white px-4 cursor-pointer" @click="saveCategory">
                        <Save class="size-4 mr-1" /> Save
                    </Button>
                    <Button variant="outline" class=" text-gray-800 px-4 cursor-pointer" @click="cancelOperation">
                        <XCircle class="size-4 mr-1" /> Cancel
                    </Button>
                </div>
            </div>
    
            <!-- Category List -->
            <div class="space-y-3">
                <div
                    v-for="category in activeCategories"
                    :key="category.id"
                    class="flex items-center justify-between p-3 border rounded-lg shadow-sm"
                >
                    <span class="font-medium">{{ category.category_name }}</span>
    
                    <div class="flex gap-2">
                        <button class="p-2 text-blue-500 cursor-pointer" @click="editCategory(category)">
                            <Pencil class="size-4" />
                        </button>
                        <button class="p-2 text-red-500 cursor-pointer" @click="deleteCategory(category.id)">
                            <Trash2 class="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    