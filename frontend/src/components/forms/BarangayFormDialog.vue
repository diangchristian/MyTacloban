<script setup>
import { ref, watch } from 'vue'
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
import LocationPicker from '@/components/location/LocationPicker.vue'
import FieldError from '@/components/forms/FieldError.vue'

const props = defineProps({
  open: Boolean,
  barangay: Object // for editing
})

const emit = defineEmits(['update:open', 'saved'])

const barangayStore = useBarangayStore()

// form state
const form = ref({
  name: '',
  area: '',
  population: '',
  households: '',
  contact_no: '',
  contact_person: '',
  coordinates: '',
  email: '',
  phone_number: ''
})

const errors = ref({})
const isSubmitting = ref(false)

// watch for barangay prop changes (edit mode)
watch(() => props.barangay, (newVal) => {
  if (newVal) {
    form.value = {
      name: newVal.name || '',
      area: newVal.area || '',
      population: newVal.population || '',
      households: newVal.households || '',
      contact_no: newVal.contact_no || '',
      contact_person: newVal.contact_person || '',
      coordinates: newVal.coordinates || '',
      email: newVal.email || '',
      phone_number: newVal.phone_number || ''
    }
  }
}, { immediate: true })

// reset form when dialog closes
watch(() => props.open, (isOpen) => {
  if (!isOpen && !props.barangay) {
    resetForm()
  }
})

const resetForm = () => {
  form.value = {
    name: '',
    area: '',
    population: '',
    households: '',
    contact_no: '',
    contact_person: '',
    coordinates: '',
    email: '',
    phone_number: ''
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
    
    if (props.barangay?.id) {
      // update existing barangay
      await barangayStore.updateBarangay(props.barangay.id, form.value)
      toast.success('Barangay updated successfully')
    } else {
      // create new barangay
      await barangayStore.createBarangay(form.value)
      toast.success('Barangay created successfully')
    }
    
    emit('saved')
    closeDialog()
    resetForm()
  } catch (error) {
    console.error('Full error:', JSON.stringify(error.response?.data, null, 2))
    console.error('Validation errors:', error.response?.data?.errors)
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      toast.error('Please fix the form errors')
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
        <DialogTitle>{{ barangay?.id ? 'Edit' : 'Add New' }} Barangay</DialogTitle>
        <DialogDescription>
          {{ barangay?.id ? 'Update the barangay information below.' : 'Fill in the details to create a new barangay.' }}
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4 py-4">
        <!-- Barangay Name -->
        <div class="space-y-2">
          <Label for="name" class="required">Barangay Name</Label>
          <Input 
            id="name" 
            v-model="form.name" 
            placeholder="Enter barangay name"
            :class="{ 'border-red-500': errors.name }"
          />
          <FieldError :message="errors.name?.[0]" />
        </div>

        <!-- Area Coverage -->
        <div class="space-y-2">
          <Label for="area">Area Coverage</Label>
          <Input 
            id="area" 
            v-model="form.area" 
            placeholder="e.g., 2.5 sq km"
            :class="{ 'border-red-500': errors.area }"
          />
          <FieldError :message="errors.area?.[0]" />
        </div>

        <!-- Population & Households -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="population">Population</Label>
            <Input 
              id="population" 
              v-model="form.population" 
              type="number"
              placeholder="Enter population"
              :class="{ 'border-red-500': errors.population }"
            />
            <FieldError :message="errors.population?.[0]" />
          </div>

          <div class="space-y-2">
            <Label for="households">Households</Label>
            <Input 
              id="households" 
              v-model="form.households" 
              type="number"
              placeholder="Enter number of households"
              :class="{ 'border-red-500': errors.households }"
            />
            <FieldError :message="errors.households?.[0]" />
          </div>
        </div>

        <!-- Contact Person & Contact Number -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="contact_person" class="required">Contact Person</Label>
            <Input 
              id="contact_person" 
              v-model="form.contact_person" 
              placeholder="Enter contact person name"
              :class="{ 'border-red-500': errors.contact_person }"
            />
            <FieldError :message="errors.contact_person?.[0]" />
          </div>

          <div class="space-y-2">
            <Label for="contact_no">Contact Number</Label>
            <Input 
              id="contact_no" 
              v-model="form.contact_no" 
              type="tel"
              placeholder="Additional contact"
              :class="{ 'border-red-500': errors.contact_no }"
            />
            <FieldError :message="errors.contact_no?.[0]" />
          </div>
        </div>

        <!-- Email & Phone -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="email" class="required">Email</Label>
            <Input 
              id="email" 
              v-model="form.email" 
              type="email"
              placeholder="barangay@example.com"
              :class="{ 'border-red-500': errors.email }"
            />
            <FieldError :message="errors.email?.[0]" />
          </div>

          <div class="space-y-2">
            <Label for="phone_number" class="required">Phone Number</Label>
            <Input 
              id="phone_number" 
              v-model="form.phone_number" 
              type="tel"
              placeholder="+63 XXX XXX XXXX"
              :class="{ 'border-red-500': errors.phone_number }"
            />
            <FieldError :message="errors.phone_number?.[0]" />
          </div>
        </div>

        <!-- Location Picker -->
        <div class="space-y-2">
          <Label class="required">Location Coordinates</Label>
          <LocationPicker 
            v-model="form.coordinates"
            :error-message="errors.coordinates?.[0]"
          />
        </div>

        <DialogFooter class="gap-2">
          <Button type="button" variant="outline" @click="closeDialog" :disabled="isSubmitting">
            Cancel
          </Button>
          <Button type="submit" :disabled="isSubmitting">
            {{ isSubmitting ? 'Saving...' : (barangay?.id ? 'Update' : 'Create') }}
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
