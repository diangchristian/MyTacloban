<script setup lang="ts">
import { ref, computed, watch } from 'vue'

// 1. ORIGINAL STATE: This holds the saved, official data.
const firstName = ref('John')
const lastName = ref('Doe')
const username = ref('John_Doe') // Saved username
const bio = ref('Tell us about yourself...')
const isSaved = ref(false)

// 2. TEMPORARY STATE: This is bound to the form input and updates instantly.
const tempUsername = ref(username.value) // Initialize with the saved value
const tempFirstName = ref(firstName.value)
const tempLastName = ref(lastName.value)
const tempBio = ref(bio.value)

// State for interactive file upload
const selectedFile = ref<File | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

// Computed property to create a URL for the selected image
const profileImageSrc = computed(() => {
  if (selectedFile.value) {
    return URL.createObjectURL(selectedFile.value)
  }
  return null // No image selected, fallback to default icon
})

// Clean up the object URL when the component unmounts or file changes
watch(selectedFile, (newFile, oldFile) => {
  if (oldFile) {
    // Check if oldFile is not null before trying to revoke its URL
    URL.revokeObjectURL(URL.createObjectURL(oldFile))
  }
}, { immediate: true })

// 3. Update the handleSave function to transfer temp values to original state
const handleSave = () => {
  // Transfer all temporary state values to the main, saved state
  username.value = tempUsername.value
  firstName.value = tempFirstName.value
  lastName.value = tempLastName.value
  bio.value = tempBio.value

  // Simulate API call or saving logic
  isSaved.value = true
  setTimeout(() => {
    isSaved.value = false
  }, 3000)
}

const handleDeleteAccount = () => {
  // In a real application, this would trigger a confirmation modal
  console.log('Delete Account clicked. A modal should appear here.')
}

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    selectedFile.value = target.files[0]
  } else {
    selectedFile.value = null
  }
}

const triggerFileInput = () => {
  fileInput.value?.click()
}

// 4. Set a watcher to update the temporary state if the saved state changes externally
// (Though in a simple form like this, you typically only rely on initialization).
// This is good practice for complex apps.
watch(username, (newValue) => {
    tempUsername.value = newValue;
})
</script>

<template>
  
  <main class="w-full mx-auto bg-white p-6 sm:p-12 rounded-xl shadow-2xl">
    
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row lg:space-x-12">
      
      <div class="flex-1 space-y-8 order-2 lg:order-1 mt-8 lg:mt-0">
        
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
        <div class="space-y-4">
          <h2 class="text-lg font-semibold border-b border-gray-200 pb-2">Username</h2>
          <div class="space-y-2">
            <label for="username" class="text-sm font-medium text-gray-700">Username</label>
            <div class="relative">
              <input
                id="username"
                type="text"
                v-model="tempUsername"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Enter your username"
              />
              <svg v-if="tempUsername === 'John_Doe'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check absolute right-3 top-1/2 -translate-y-1/2 text-green-600"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-2">
            <label for="firstName" class="text-sm font-medium text-gray-700">First Name</label>
            <div class="relative">
              <input
                id="firstName"
                type="text"
                v-model="tempFirstName"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="John"
              />
            </div>
          </div>
          <div class="space-y-2">
            <label for="lastName" class="text-sm font-medium text-gray-700">Last Name</label>
            <div class="relative">
              <input
                id="lastName"
                type="text"
                v-model="tempLastName"
                class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Doe"
              />
            </div>
          </div>
        </div>

        <div class="space-y-2">
          <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
          <textarea
            id="bio"
            v-model="tempBio"
            rows="4"
            class="flex min-h-[80px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
            placeholder="Tell us about yourself..."
          ></textarea>
        </div>

        <button
          @click="handleSave"
          :disabled="isSaved"
          class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 bg-green-600 text-white hover:bg-green-700 shadow-lg"
        >
          <span v-if="!isSaved">Save</span>
          <span v-else class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-2"><path d="M20 6 9 17l-5-5"/></svg>
            Saved
          </span>
        </button>
        
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
        <div class="text-lg font-semibold mb-6">{{ username }}</div> 

        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4 overflow-hidden">
          <img v-if="profileImageSrc" :src="profileImageSrc" alt="Profile Picture" class="w-full h-full object-cover"/>
          <svg v-else xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-gray-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>

        <div class="text-gray-500 text-sm">Member</div>
      </div>
    </div>
  </main>

</template>
