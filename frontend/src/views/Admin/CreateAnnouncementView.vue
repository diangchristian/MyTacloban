<script setup>
  import { reactive, ref, watch, onBeforeUnmount, onMounted } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import TinyMCE from "@/components/others/TinyMCE.vue";
  import Input from "@/components/ui/input/Input.vue";
  import Button from "@/components/ui/button/Button.vue";
  import Label from "@/components/ui/label/Label.vue";
  import { Checkbox } from "@/components/ui/checkbox";
  import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from "@/components/ui/select";
  import { useUploadStore } from "@/stores/upload";
  import { useAnnouncementStore } from "@/stores/announcements";
  import { storeToRefs } from "pinia";
  import Skeleton from "@/components/ui/skeleton/Skeleton.vue";
  import FieldError from "@/components/forms/FieldError.vue";
  import {useAuthStore} from "@/stores/auth"



  // ====== ROUTE & MODE ======
  const route = useRoute();
  const router = useRouter();
  const isEdit = ref(!!route.params.id);
  const announcementId = route.params.id;
  
  // ====== STORES ======
  const uploadStore = useUploadStore();
  const announcementStore = useAnnouncementStore();
  const { announcementDetails, categories, isLoading, errors } = storeToRefs(announcementStore);
  const {user} = storeToRefs(useAuthStore())

// ====== FORM DATA ======
const formData = reactive({
  announcement_title: "",
  body: "",
  category_id: null,
  isHighlight: false,
  image: uploadStore.uploadedFiles[0]?.url ?? null,
  status: "published",
  user_id: user.value.id
});
const editorLoaded = ref(false);

const previewUrl = ref(formData.image ?? null);

// Fetch announcement if editing
if (isEdit.value) {
  onMounted(async() => {
    await announcementStore.getAnnouncementById(announcementId);
  });

  // Watch announcementDetails and populate formData when data is ready
  watch(
  () => announcementDetails.value,
  (val) => {
    if (!val || val.length === 0) return;

    const announcement = val[0]; // ✅ extract object

    formData.announcement_title = announcement.title ?? "";
    formData.body = announcement.body ?? "";
    formData.category_id = announcement.category_id ?? null;
    formData.isHighlight = !!announcement.isHighlight;
    formData.image = announcement.image ?? null;
    formData.status = announcement.status ?? "published";

    previewUrl.value = announcement.image ?? null;

    console.log("FormData populated:", { ...formData });
  },
  { immediate: true }
);



}

// Fetch categories
onMounted(() => {
  announcementStore.getCategories();
});

  // ====== IMAGE PREVIEW ======
  let createdObjectUrl = null;
  
  function handleFileChange(e) {
    const file = e.target.files?.[0] ?? null;
    if (!file) return;
  
    if (createdObjectUrl) URL.revokeObjectURL(createdObjectUrl);
  
    createdObjectUrl = URL.createObjectURL(file);
    previewUrl.value = createdObjectUrl;
    formData.image = file;
    uploadStore.uploadImages([file]);
  }
  
  // ====== RESET FORM ======
  const resetForm = () => {
    formData.announcement_title = "";
    formData.body = "";
    formData.category_id = null;
    formData.isHighlight = false;
    formData.image = null;
    formData.status = "published";
    previewUrl.value = null;
    uploadStore.uploadedFiles = [];
  };
  
  // ====== WATCH UPLOADED FILES ======
  watch(
    () => uploadStore.uploadedFiles,
    (newVal) => {
      const first = newVal?.[0];
      if (first?.url) {
        if (createdObjectUrl) URL.revokeObjectURL(createdObjectUrl);
        createdObjectUrl = null;
  
        previewUrl.value = first.url;
        formData.image = first.url;
      }
    },
    { deep: true }
  );
  
  onBeforeUnmount(() => {
    if (createdObjectUrl) URL.revokeObjectURL(createdObjectUrl);
    createdObjectUrl = null;
  });
  
  // ====== SUBMIT HANDLER ======
  const handleSubmit = async (status) => {
    formData.status = status;
  
    let success;
    if (isEdit.value) {
      success = await announcementStore.updateAnnouncement(formData, announcementId);
    } else {
      success = await announcementStore.createAnnouncement(formData);
    }
  
    if (success) {
      resetForm();
      router.push("/admin/announcements"); // redirect to announcements list
    }
  };
  </script>
  
  <template>
    <main>
      <form @submit.prevent="handleSubmit" >
        <!-- <Skeleton v-if="isLoading" class="h-96 bg-gray-200"/> -->
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-3 gap-4">
  
            <!-- LEFT -->
            <div class="col-span-2 space-y-4">
              <div>
                <Label class="mb-2">Title</Label>
                <Input
                  v-model="formData.announcement_title"
                  placeholder="Enter title"
                  class="bg-white"
                />
                <FieldError v-if="errors.announcement_title" class="mt-2" :errorMessage="errors.announcement_title[0]"/>
              </div>
              <Skeleton v-if="!editorLoaded" class="h-full bg-gray-200 w-full" />
             <TinyMCE   v-model="formData.body" class="w-full mt-4"  @init="editorLoaded = true" />




            </div>
  
            <!-- RIGHT -->
            <div class="bg-white p-4 rounded-lg shadow-sm space-y-4 flex flex-col">
  
              <!-- Category -->
              <div>
                <Label class="mb-2">Category</Label>
                <Select v-model="formData.category_id">
                  <SelectTrigger class="w-full mt-2">
                    <SelectValue placeholder="Select category" />
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
                <FieldError v-if="errors.category_id" class="mt-2" errorMessage="The category field is required"/>
              </div>
  
              <!-- Highlight -->
              <div class="mt-2 flex items-center gap-3">
                <Checkbox v-model="formData.isHighlight" id="highlights" />
                <Label for="highlights" class="font-semibold">
                  Add to Highlights
                </Label>
              </div>
  
              <!-- Image Upload -->
              <div>
                <label
                  for="dropzone-file"
                  class="flex flex-col items-center justify-center w-full h-64
                         bg-neutral-secondary-medium border border-dashed
                         border-default-strong rounded-base cursor-pointer
                         hover:bg-neutral-tertiary-medium"
                >
                  <img
                    v-if="previewUrl"
                    :src="previewUrl"
                    alt="Preview"
                    class="w-full h-64 object-cover rounded-base mb-2"
                  />
                  <div v-else class="flex flex-col items-center text-body pt-5 pb-6">
                    <p class="mb-2 text-sm font-semibold">
                      Click to upload or drag and drop
                    </p>
                    <p class="text-xs">
                      SVG, PNG, JPG or GIF (MAX. 800x400px)
                    </p>
                  </div>
  
                  <input
                    id="dropzone-file"
                    type="file"
                    class="hidden"
                    @change="handleFileChange"
                  />
                </label>
              </div>

              <div v-if="isEdit"> 
                <Label class="mb-2">Update Status</Label>
                <Select v-model="formData.status">
                  <SelectTrigger class="w-full mt-2">
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="published">Published</SelectItem>
                    <SelectItem value="archived">Archived</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError v-if="errors.status" class="mt-2" :errorMessage="errors.status[0]"/>
            </div>

  
              <!-- Actions -->
              <div class="mt-auto">
                <!-- Publish / Update -->
                <Button type="button" class="mr-4" @click="handleSubmit(formData.status)">
                  {{ isEdit ? "Update" : "Publish" }}
                </Button>
  
                <!-- Draft -->
                <Button type="button" variant="outline" @click="handleSubmit('draft')">
                  {{ isEdit ? "Save Changes" : "Save as Draft" }}
                </Button>
              </div>
  
            </div>
          </div>
        </div>
      </form>
    </main>
  </template>
  