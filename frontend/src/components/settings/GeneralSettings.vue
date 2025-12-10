<script setup>
import { ref, onMounted, reactive, watch } from 'vue';
import Button from "@/components/ui/button/Button.vue";
import { Settings, Save, FileText, Image as ImageIcon } from 'lucide-vue-next';
import {useSystemSettingsStore} from "@/stores/system"
import { storeToRefs } from 'pinia';
import { useUploadStore } from '@/stores/upload'


const uploadStore = useUploadStore()
const systemSettingsStore = useSystemSettingsStore()
const {systemData, errors, isloading} = storeToRefs(systemSettingsStore)
const selectedFile = ref(null)
const formData = reactive({
    system_name: '',
    description: '',
    logo: '' 
})


onMounted(async () => {
    await systemSettingsStore.getSystemData()

    if(systemData.value){
        formData.system_name = systemData.value.system_name
        formData.description = systemData.value.description
        formData.logo = systemData.value.logo_path
    }
})


const uploadedLogoName = ref(null);

const saveSettings = () => {
    console.log(formData)
    systemSettingsStore.updateSettings(formData)
};

watch(
    () => uploadStore.uploadedFiles,
    (newFiles) => {
      formData.logo = newFiles[0]?.url || null
    },
    { immediate: true }
  )

const handleFileUpload = (event) => {
    const target = event.target
    if (target.files && target.files.length > 0) {
      uploadStore.uploadImages(target.files[0])
      selectedFile.value = target.files[0]
    } else {
      selectedFile.value = null
    }
  }
</script>
    
    <template>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-xl rounded-2xl border border-gray-100">
    
            <header class="mb-6 border-b pb-4 flex items-center gap-3">
                <Settings class="size-6 text-gray-600" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">General System Settings</h2>
                    <p class="text-sm text-gray-500">Configure basic system information and preferences.</p>
                </div>
            </header>
    
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">System Name</label>
                    <input
                        v-model="formData.system_name"
                        type="text"
                        class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                    />
                </div>
    
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City/LGU Logo</label>
                    <input type="file" id="logo-upload" accept="image/*" @change="handleFileUpload" class="hidden" />
    
                    <label
                        for="logo-upload"
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                        :class="[uploadedLogoName ? 'border-green-500 bg-green-50' : 'border-green-300 bg-green-50/50']"
                    >
                        <div v-if="uploadedLogoName" class="text-center text-green-700 p-4">
                            <FileText class="mx-auto size-8 mb-2" />
                            <p class="font-medium truncate">{{ uploadedLogoName }}</p>
                            <p class="text-sm text-green-600">Click to change logo</p>
                        </div>
                        <div v-else class="text-center text-gray-500">
                            <ImageIcon class="mx-auto size-8 text-green-500" />
                            <p class="mt-2 text-sm font-medium">Click to upload logo</p>
                        </div>
                    </label>
                </div>
    
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">About/Description</label>
                    <textarea
                        v-model="formData.description"
                        rows="4"
                        class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                    />
                </div>
            </div>
    
            <div class="mt-8 pt-4 border-t">
                <Button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl" @click="saveSettings">
                    <Save class="size-5 mr-2" /> Save Changes
                </Button>
            </div>
        </div>
    </template>
    