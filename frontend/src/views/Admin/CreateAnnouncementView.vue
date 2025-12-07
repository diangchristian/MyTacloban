<script setup>
  import { reactive } from "vue";
  
  import TinyMCE from "@/components/TinyMCE.vue";
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
  
  /* Reactive form data */
  const formData = reactive({
    title: "",
    body: "",
    category: "",
    is_highlighted: false,
    image: null,
    status: "published",
  });
  
  /* File handler */
  function handleFileChange(e) {
    formData.image = e.target.files[0] ?? null;
  }
  
  /* Submit */
  function submitForm(status) {
    formData.status = status;
  
    const payload = new FormData();
    Object.keys(formData).forEach((key) => {
      payload.append(key, formData[key]);
    });
  
    // Debug
    console.log("Submitting:", Object.fromEntries(payload));
  
    // Example:
    // axios.post('/announcements', payload)
  }
  </script>
  
  <template>
    <main>
      <form @submit.prevent="submitForm('published')">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-3 gap-4">
  
            <!-- LEFT -->
            <div class="col-span-2">
              <div>
                <Label class="mb-2">Title</Label>
                <Input
                  v-model="formData.title"
                  placeholder="Enter title"
                  class="bg-white"
                />
              </div>
  
              <TinyMCE
                v-model="formData.body"
                class="w-full mt-4"
              />
            </div>
  
            <!-- RIGHT -->
            <div class="bg-white p-4 rounded-lg shadow-sm space-y-4 flex flex-col">
  
              <!-- Category -->
              <div>
                <Label class="mb-2">Category</Label>
                <Select v-model="formData.category">
                  <SelectTrigger class="w-full mt-2">
                    <SelectValue placeholder="Select category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="emergency">Emergency</SelectItem>
                    <SelectItem value="community_event">Community Event</SelectItem>
                    <SelectItem value="notice">Notice</SelectItem>
                  </SelectContent>
                </Select>
              </div>
  
              <!-- Highlight -->
              <div class="mt-2 flex items-center gap-3">
                <Checkbox v-model:checked="formData.is_highlighted" id="highlights" />
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
                  <div class="flex flex-col items-center text-body pt-5 pb-6">
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
  
              <!-- Actions -->
              <div class="mt-auto">
                <Button type="submit" class="mr-4">
                  Publish
                </Button>
  
                <Button
                  type="button"
                  variant="outline"
                  @click="submitForm('draft')"
                >
                  Save as Draft
                </Button>
              </div>
  
            </div>
          </div>
        </div>
      </form>
    </main>
  </template>
  