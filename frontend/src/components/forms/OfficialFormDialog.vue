<script setup>
import { ref, watch } from 'vue'
import { useBarangayOfficialStore } from '@/stores/barangayOfficial'
import { useBarangayStore } from '@/stores/barangay'
import { toast } from 'vue-sonner'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectGroup,
  SelectItem,
} from '@/components/ui/select'
import FieldError from '@/components/forms/FieldError.vue'

const props = defineProps({
  open: Boolean,
  official: Object, // for editing
  preselectedBarangayId: Number // for creating with barangay already selected
})

const emit = defineEmits(['update:open', 'saved'])

const officialStore = useBarangayOfficialStore()
const barangayStore = useBarangayStore()

// form state
const form = ref({
  barangay_id: '',
  official_name: '',
  position: '',
  email: '',
  contact_number: ''
})

const errors = ref({})
const isSubmitting = ref(false)

const positions = [
  { value: 'captain', label: 'Barangay Captain' },
  { value: 'skchairman', label: 'SK Chairman' },
  { value: 'secretary', label: 'Barangay Secretary' },
  { value: 'treasurer', label: 'Barangay Treasurer' },
  { value: 'councilor', label: 'Barangay Councilor' }
]

// watch for official prop changes (edit mode)
watch(() => props.official, (newVal) => {
  if (newVal) {
    form.value = {
      barangay_id: newVal.barangay_id || '',
      official_name: newVal.official_name || '',
      position: newVal.position || '',
      email: newVal.email || '',
      contact_number: newVal.contact_number || ''
    }
  }
}, { immediate: true })

// watch for preselected barangay
watch(() => props.preselectedBarangayId, (newVal) => {
  if (newVal && !props.official) {
    form.value.barangay_id = newVal
  }
}, { immediate: true })

// reset form when dialog closes
watch(() => props.open, (isOpen) => {
  if (!isOpen && !props.official) {
    resetForm()
  }
})

const resetForm = () => {
  form.value = {
    barangay_id: props.preselectedBarangayId || '',
    official_name: '',
    position: '',
    email: '',
    contact_number: ''
  }
  errors.value = {}
}

const closeDialog = () => {
  emit('update:open', false)
}

const handleSubmit = async () => {
  isSubmitting.value = true
  errors.value = {}

  try {
    console.log('Form data being sent:', JSON.stringify(form.value, null, 2))
    
    if (props.official?.id) {
      // update existing official
      await officialStore.updateOfficial(props.official.id, form.value)
      toast.success('Official updated successfully')
    } else {
      // create new official
      await officialStore.createOfficial(form.value)
      toast.success('Official created successfully')
    }
    
    emit('saved')
    closeDialog()
    resetForm()
  } catch (error) {
    console.error('Full error:', JSON.stringify(error.response?.data, null, 2))
    console.error('Validation errors:', error.response?.data?.errors)
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      toast.error(error.response.data.message || 'Please fix the form errors')
    } else {
      toast.error('An error occurred. Please try again.')
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="closeDialog">
    <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
      <DialogHeader>
        <DialogTitle>{{ official?.id ? 'Edit' : 'Add New' }} Barangay Official</DialogTitle>
        <DialogDescription>
          {{ official?.id ? 'Update the official information below.' : 'Fill in the details to add a new barangay official.' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4 py-4">
        <!-- Barangay Selection -->
        <div class="space-y-2">
          <Label for="barangay_id" class="required">Barangay</Label>
          <Select v-model="form.barangay_id">
            <SelectTrigger
              :class="{ 'border-red-500': errors.barangay_id }"
            >
              <SelectValue placeholder="Select barangay" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem
                  v-for="barangay in barangayStore.barangays"
                  :key="barangay.id"
                  :value="barangay.id"
                >
                  {{ barangay.name }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <FieldError :message="errors.barangay_id?.[0]" />
        </div>

        <!-- Official Name -->
        <div class="space-y-2">
          <Label for="official_name" class="required">Official Name</Label>
          <Input 
            id="official_name" 
            v-model="form.official_name" 
            placeholder="Enter official's full name"
            :class="{ 'border-red-500': errors.official_name }"
          />
          <FieldError :message="errors.official_name?.[0]" />
        </div>

        <!-- Position -->
        <div class="space-y-2">
          <Label for="position" class="required">Position</Label>
          <Select v-model="form.position">
            <SelectTrigger
              :class="{ 'border-red-500': errors.position }"
            >
              <SelectValue placeholder="Select position" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem
                  v-for="pos in positions"
                  :key="pos.value"
                  :value="pos.value"
                >
                  {{ pos.label }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <FieldError :message="errors.position?.[0]" />
        </div>

        <!-- Email & Contact Number -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input 
              id="email" 
              v-model="form.email" 
              type="email"
              placeholder="official@example.com"
              :class="{ 'border-red-500': errors.email }"
            />
            <FieldError :message="errors.email?.[0]" />
          </div>

          <div class="space-y-2">
            <Label for="contact_number">Contact Number</Label>
            <Input 
              id="contact_number" 
              v-model="form.contact_number" 
              type="tel"
              placeholder="+63 XXX XXX XXXX"
              :class="{ 'border-red-500': errors.contact_number }"
            />
            <FieldError :message="errors.contact_number?.[0]" />
          </div>
        </div>

        <DialogFooter class="gap-2">
          <Button type="button" variant="outline" @click="closeDialog" :disabled="isSubmitting">
            Cancel
          </Button>
          <Button type="submit" :disabled="isSubmitting">
            {{ isSubmitting ? 'Saving...' : (official?.id ? 'Update' : 'Create') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

<style scoped>
.required::after {
  content: " *";
  color: red;
}
</style>
