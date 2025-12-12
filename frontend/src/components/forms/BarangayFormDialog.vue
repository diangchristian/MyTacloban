<script setup>
import { ref, watch } from "vue";
import { useBarangayStore } from "@/stores/barangay";
import { toast } from "vue-sonner";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import LocationPicker from "@/components/location/LocationPicker.vue";
import FieldError from "@/components/forms/FieldError.vue";
import Separator from "../ui/separator/Separator.vue";


const props = defineProps({
  open: Boolean,
  barangay: Object, // for editing
});

const emit = defineEmits(["update:open", "saved"]);

const barangayStore = useBarangayStore();

// form state
const form = ref({
  name: "",
  area: "",
  population: "",
  households: "",
  contact_no: "",
  contact_person: "",
  coordinates: "",
  email: "",
  phone_number: "",
});

const errors = ref({});
const isSubmitting = ref(false);

// watch for barangay prop changes (edit mode)
watch(
  () => props.barangay,
  (newVal) => {
    if (newVal) {
      form.value = {
        name: newVal.name || "",
        area: newVal.area || "",
        population: newVal.population || "",
        households: newVal.households || "",
        contact_no: newVal.contact_no || "",
        contact_person: newVal.contact_person || "",
        coordinates: newVal.coordinates || "",
        email: newVal.email || "",
        phone_number: newVal.phone_number || "",
      };
    }
  },
  { immediate: true }
);

// reset form when dialog closes
watch(
  () => props.open,
  (isOpen) => {
    if (!isOpen && !props.barangay) {
      resetForm();
    }
  }
);

const resetForm = () => {
  form.value = {
    name: "",
    area: "",
    population: "",
    households: "",
    contact_no: "",
    contact_person: "",
    coordinates: "",
    email: "",
    phone_number: "",
  };
  errors.value = {};
};

const closeDialog = () => {
  emit("update:open", false);
};

const handleSubmit = async () => {
  isSubmitting.value = true;
  errors.value = {};

  try {
    console.log("Form data being sent:", JSON.stringify(form.value, null, 2));

    if (props.barangay?.id) {
      // update existing barangay
      await barangayStore.updateBarangay(props.barangay.id, form.value);
      toast.success("Barangay updated successfully");
    } else {
      // create new barangay
      await barangayStore.createBarangay(form.value);
      toast.success("Barangay created successfully");
    }

    emit("saved");
    closeDialog();
    resetForm();
  } catch (error) {
    console.error("Full error:", JSON.stringify(error.response?.data, null, 2));
    console.error("Validation errors:", error.response?.data?.errors);
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      toast.error("Please fix the form errors");
    } else {
      toast.error("An error occurred. Please try again.");
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <Dialog :open="open" @update:open="closeDialog" class="">
    <DialogContent class=" sm:max-w-[900px]  max-h-[90vh] overflow-y-auto">
      <DialogHeader>
        <DialogTitle
          >{{ barangay?.id ? "Edit" : "Add New" }} Barangay</DialogTitle
        >
        <DialogDescription>
          {{
            barangay?.id
              ? "Update the barangay information below."
              : "Fill in the details to create a new barangay."
          }}
        </DialogDescription>
      </DialogHeader>
      <Separator/>
      <form @submit.prevent="handleSubmit" class="space-y-6 py-4">

<!-- 2 Columns on Large Screens -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  <!-- LEFT SIDE – Inputs -->
  <div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Barangay Name -->
      <div class="space-y-2">
        <Label class="required">Barangay Name</Label>
        <Input v-model="form.name" />
        <FieldError :message="errors.name?.[0]" />
      </div>

      <!-- Area Coverage -->
      <div class="space-y-2">
        <Label>Area Coverage</Label>
        <Input v-model="form.area" />
        <FieldError :message="errors.area?.[0]" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Population -->
      <div class="space-y-2">
        <Label>Population</Label>
        <Input v-model="form.population" type="number" />
      </div>

      <!-- Households -->
      <div class="space-y-2">
        <Label>Households</Label>
        <Input v-model="form.households" type="number" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Contact Person -->
      <div class="space-y-2">
        <Label class="required">Contact Person</Label>
        <Input v-model="form.contact_person" />
      </div>

      <!-- Contact Number -->
      <div class="space-y-2">
        <Label>Contact Number</Label>
        <Input v-model="form.contact_no" type="tel" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Email -->
      <div class="space-y-2">
        <Label class="required">Email</Label>
        <Input v-model="form.email" type="email" />
      </div>

      <!-- Phone Number -->
      <div class="space-y-2">
        <Label class="required">Phone Number</Label>
        <Input v-model="form.phone_number" type="tel" />
      </div>
    </div>

  </div>

  <!-- RIGHT SIDE – Map Picker -->
  <div class="space-y-3 mb-4">
    <Label class="required">Location Coordinates</Label>

    <LocationPicker 
      v-model="form.coordinates"
      :error-message="errors.coordinates?.[0]"
      class="w-full rounded-md"
    />
  </div>

</div>

<!-- Footer -->
<DialogFooter class="gap-"> 
  <Button type="button" variant="outline" @click="closeDialog" :disabled="isSubmitting"> Cancel 
  </Button> 
  <Button type="submit" :disabled="isSubmitting"> {{ isSubmitting ? 'Saving...' : (barangay?.id ? 'Update' : 'Create') }} 

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
