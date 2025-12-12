<script setup>
  import { ref, computed, reactive, watch } from 'vue'
  import { storeToRefs } from 'pinia'
  import { useAuthStore } from '@/stores/auth'
  import { useUploadStore } from '@/stores/upload'
  import { useDialogStore } from '@/stores/dialogStore'
  import ConfirmDeleteDialog from '@/components/others/ConfirmDeleteDialog.vue'
  
  const authStore = useAuthStore()
  const uploadStore = useUploadStore()
  const dialogStore = useDialogStore()
  
  const { user } = storeToRefs(authStore)
  
  const formData = reactive({
    username: user.value?.username || '',
    full_name: user.value?.full_name || '',
    bio: user.value?.bio || '',
    email: user.value?.email || '',
    profile_image: null,
    role: user.value?.role || '',
    status: user.value?.status || '',
  })
  
  const isSaved = ref(false)
  const selectedFile = ref(null)
  const fileInput = ref(null)
  
const profileImageSrc = computed(() => {
  if (selectedFile.value) {
    return URL.createObjectURL(selectedFile.value)
  }
  // fallback to saved profile image from authStore
  return user.value?.profile_image || null
})

  watch(selectedFile, (newFile, oldFile) => {
    if (oldFile) URL.revokeObjectURL(URL.createObjectURL(oldFile))
  })
  
  watch(
    () => uploadStore.uploadedFiles,
    (newFiles) => {
      formData.profile_image = newFiles[0]?.url || null
    },
    { immediate: true }
  )
  
  const handleSave = () => {
    authStore.updateUserProfile(formData)
    console.log(formData)
  }
  
  const handleFileUpload = (event) => {
    const target = event.target
    if (target.files && target.files.length > 0) {
      uploadStore.uploadImages(target.files[0])
      selectedFile.value = target.files[0]
    } else {
      selectedFile.value = null
    }
  }
  
  const triggerFileInput = () => fileInput.value?.click()
  
  // Open delete dialog with action
  const handleDeleteAccount = () => {
    dialogStore.openConfirm({
      title: 'Delete Account',
      description: 'This will permanently delete your account and all associated data.',
      confirmText: 'Delete Account',
      onConfirm: () => authStore.deleteAccount()
    })
  }
  </script>
  

<template>
  
  <main class=" max-w-7xl mx-auto bg-white p-6 sm:p-12 rounded-xl shadow-2xl">
    
    <div class="  mx-auto flex flex-col lg:flex-row lg:space-x-12">
      
      <div class="flex-1 space-y-8 order-2 lg:order-1 mt-8 lg:mt-0">
        
        <form action="" class="space-y-8" @submit.prevent="handleSave">
          <div class="space-y-2">
          <label for="picture" class="text-sm font-medium text-gray-700">Picture</label>
          
          <div
            @click="triggerFileInput"
            :class="['p-8 border-2 border-dashed rounded-xl cursor-pointer flex flex-col items-center justify-center space-y-3 transition-colors',
              selectedFile ? 'border-green-400 bg-green-50/50' : 'border-gray-300 bg-gray-50 hover:border-green-400']"
          >
            <input
              id="picture"
              type="file"
              ref="fileInput"
              class="hidden"
              @change="handleFileUpload"
              accept="image/*"
            />
            
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="['lucide lucide-upload', selectedFile ? 'text-green-600' : 'text-gray-500']"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
            
            <p v-if="selectedFile" class="text-sm font-medium text-green-700">
              {{ selectedFile.name }} (Click to change)
            </p>
            <p v-else class="text-sm text-gray-500">
              Upload or drag & drop
            </p>
          </div>
        </div>
        <div class="space-y-2">
          <h2 class="text-lg font-semibold border-b border-gray-200 pb-2">Username</h2>
          <div class="space-y-2">
            <label for="username" class="text-sm font-medium text-gray-700">Username</label>
            <div class="relative">
              <input
                id="username"
                type="text"
                v-model="formData.username"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Enter your username"
              />
              <svg v-if="formData.username === 'John_Doe'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check absolute right-3 top-1/2 -translate-y-1/2 text-green-600"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1  gap-4">
          <div class="">
            <label for="firstName" class="text-sm font-medium text-gray-700">Full Name</label>
            <div class="relative">
              <input
                id="firstName"
                type="text"
                v-model="formData.full_name"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="John"
              />
            </div>
          </div>
          <div class="space-y-2">
            <label for="firstName" class="text-sm font-medium text-gray-700">Email</label>
            <div class="relative">
              <input
                id="firstName"
                type="email"
                v-model="formData.email"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="John"
              />
            </div>
          </div>
        </div>

        <div class="space-y-2">
          <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
          <textarea
            id="bio"
            v-model="formData.bio"
            rows="4"
            class="flex min-h-[80px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
            placeholder="Tell us about yourself..."
          ></textarea>
        </div>

        <button
          type="submit"
          :disabled="isSaved"
          class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 bg-green-600 text-white hover:bg-green-700 shadow-lg"
        >
          <span v-if="!isSaved">Save</span>
          <span v-else class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-2"><path d="M20 6 9 17l-5-5"/></svg>
            Saved
          </span>
        </button>
        </form>
        
        <!-- delete account -->
        <div class="space-y-4 pt-8 border-t border-gray-200">
          <h2 class="text-lg font-semibold text-red-600">Delete Account</h2>
          <p class="text-sm text-gray-600">Delete your account & all of its contents.</p>

          <div class="p-4 rounded-lg bg-red-50 border border-red-400 space-y-2">
            <div class="text-sm font-bold text-red-600">WARNING</div>
            <p class="text-sm text-red-700">Please proceed with caution, this action cannot be undone.</p>
          </div>

          <button
            @click="handleDeleteAccount"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2 h-10 px-4 py-2 bg-red-600 text-white hover:bg-red-700"
          >
            Delete Account
          </button>
        </div>

      </div>

      <div class="lg:w-64 bg-gray-50 p-6 rounded-xl shadow-lg flex flex-col items-center justify-start h-80 order-1 lg:order-2 flex-shrink-0">
        <div class="text-lg font-semibold mb-6">{{ formData.username }}</div> 

        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4 overflow-hidden">
          <img v-if="profileImageSrc" :src="profileImageSrc" alt="Profile Picture" class="w-full h-full object-cover"/>
          <svg v-else xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-gray-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>

        <div class="text-gray-500 text-sm">Member</div>
      </div>
    </div>

    <ConfirmDeleteDialog/>

  </main>

</template>
