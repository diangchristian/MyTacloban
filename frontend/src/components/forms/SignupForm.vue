<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import { reactive, onMounted } from "vue"
import {useBarangayStore} from "@/stores/barangay"
import { storeToRefs } from "pinia"
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from "@/components/ui/select";
import { useAuthStore } from "@/stores/auth"
import FieldError from "./FieldError.vue"

const barangayStore = useBarangayStore()
const {barangays} = storeToRefs(barangayStore)

const authStore = useAuthStore();
const {errors} = storeToRefs(authStore)
const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()


onMounted(() => {
  barangayStore.getAllBarangay()
})




const formData = reactive({
  full_name: '',
  email: '',
  password: '',
  password_confirmation: '',
  barangay_id: ''

})


const handleSubmit = () => {
  authStore.Authenticate('register', formData)
}





</script>

<template>
  <form :class="cn('flex flex-col gap-6', props.class)" @submit.prevent="handleSubmit">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">
          Create your account
        </h1>
        <p class="text-muted-foreground text-sm text-balance">
          Fill in the form below to create your account
        </p>
      </div>
      <Field>
        <FieldLabel for="name">
          Full Name
        </FieldLabel>
        <Input id="name" type="text" placeholder="John Doe"  v-model="formData.full_name"/>
        <FieldError v-if="errors.full_name" :errorMessage="errors.full_name[0]"/>
      </Field>
      <Field>
        <FieldLabel for="barangay">
          Barangay
        </FieldLabel>
        <Select name="barangay" v-model="formData.barangay_id">
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
        <FieldError v-if="errors.barangay_id" errorMessage="The barangay field is required."/>
      </Field>
      <Field>
        <FieldLabel for="email">
          Email
        </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com"  v-model="formData.email"/>
        <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/>
        <FieldDescription>
          We'll use this to contact you. We will not share your email
          with anyone else.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="password">
          Password
        </FieldLabel>
        <Input id="password" type="password"  v-model="formData.password"/>
        <FieldError v-if="errors.password" :errorMessage="errors.password[0]"/>
        <FieldDescription>
          Must be at least 8 characters long.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="confirm-password">
          Confirm Password
        </FieldLabel>
        <Input id="confirm-password" type="password"  v-model="formData.password_confirmation"/>
        <FieldDescription>Please confirm your password.</FieldDescription>
      </Field>
      <Field>
        <Button type="submit">
          Create Account
        </Button>
      </Field>
    </FieldGroup>

    <p class="text-sm">Already have an account?<span><RouterLink to="/login" class="underline text-primary"> Login</RouterLink></span></p>
  </form>
</template>
