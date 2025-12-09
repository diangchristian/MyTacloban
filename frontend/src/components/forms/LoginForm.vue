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
import { useAuthStore } from "@/stores/auth"
import { reactive } from "vue"
import { RouterLink } from "vue-router"
import FieldError from "./FieldError.vue"
import { storeToRefs } from "pinia"


const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()


const authStore = useAuthStore();
const {errors} = storeToRefs(authStore)
const formData = reactive({
  email: '',
  password: ''
})


const handleSubmit = () => {
  authStore.Authenticate('login',formData )
}



</script>

<template>
  <form :class="cn('flex flex-col gap-6', props.class)" @submit.prevent="handleSubmit">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">
          Login to your account
        </h1>
        <p class="text-muted-foreground text-sm text-balance">
          Enter your email below to login to your account
        </p>
      </div>
      <Field>
        <FieldLabel for="email">
          Email
        </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com"  v-model="formData.email" />
        <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/>
      </Field>
      <Field>
        <div class="flex items-center">
          <FieldLabel for="password">
            Password
          </FieldLabel>
          <a
            href="#"
            class="ml-auto text-sm underline-offset-4 hover:underline"
          >
            Forgot your password?
          </a>
        </div>
        <Input id="password" type="password"  v-model="formData.password" />
        <FieldError v-if="errors.password" :errorMessage="errors.password[0]"/>
      </Field>
      <Field>
        <Button type="submit">
          Login
        </Button>
      </Field>
      <p class="text-sm">Don't have an account?<span><RouterLink to="/register" class="underline text-primary"> Register</RouterLink></span></p>
    </FieldGroup>
  </form>
</template>
