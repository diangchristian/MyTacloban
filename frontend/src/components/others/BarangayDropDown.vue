<script setup>
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from '@/components/ui/field'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from "@/components/ui/select";
import FieldError from '../forms/FieldError.vue';
import { ref,  onMounted, watch } from 'vue';
import {useBarangayStore} from "@/stores/barangay"
import { storeToRefs } from "pinia";


const barangayStore = useBarangayStore();
const {barangays} = storeToRefs(barangayStore)
const props = defineProps({
    modelValue: Object,
    error: ''
})


const selectedBarangay = ref(null)

const emit = defineEmits(['update:modelValue'])

watch(selectedBarangay, (newVal) => {
    emit('update:modelValue', newVal);
})


onMounted(async () => {
    await barangayStore.getAllBarangay()
})


</script>


<template>
    <Field>
    <FieldLabel for="barangay">
        Barangay
    </FieldLabel>
    <Select name="barangay" v-model="selectedBarangay">
        <SelectTrigger class="w-full">
        <SelectValue placeholder="Select barangay" />
        </SelectTrigger>
        <SelectContent>
        <SelectItem
            v-for="barangay in barangays"
            :key="barangay.id"
            :value="barangay.id"
        >
            {{ barangay.name }}
        </SelectItem>
        </SelectContent>
    </Select>
    <FieldError v-if="error" errorMessage="The barangay field is required."/>
    </Field>
</template>