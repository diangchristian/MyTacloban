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

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()


const authStore = useAuthStore();

const formData = reactive({
  email: '',
  password: ''
})



</script>

<template>
  <form :class="cn('flex flex-col gap-6', props.class)" @submit.prevent="">
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
        <Input id="email" type="email" placeholder="m@example.com" required v-model="formData.email" />
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
        <Input id="password" type="password" required v-model="formData.password" />
      </Field>
      <Field>
        <Button type="submit">
          Login
        </Button>
      </Field>
      <RouterLink>Don't have an account?<span></span></RouterLink>
    </FieldGroup>
  </form>
</template>
