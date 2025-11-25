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

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <form :class="cn('flex flex-col gap-6', props.class)">
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
        <Input id="name" type="text" placeholder="John Doe" required />
      </Field>
      <Field>
        <FieldLabel for="email">
          Email
        </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com" required />
        <FieldDescription>
          We'll use this to contact you. We will not share your email
          with anyone else.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="password">
          Password
        </FieldLabel>
        <Input id="password" type="password" required />
        <FieldDescription>
          Must be at least 8 characters long.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="confirm-password">
          Confirm Password
        </FieldLabel>
        <Input id="confirm-password" type="password" required />
        <FieldDescription>Please confirm your password.</FieldDescription>
      </Field>
      <Field>
        <Button type="submit">
          Create Account
        </Button>
      </Field>
    </FieldGroup>
  </form>
</template>
