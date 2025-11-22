<script setup >
import { Input } from "@/components/ui/input"
import {Button} from "@/components/ui/button"
import { useAuthStore } from "@/stores/auth";
import { reactive } from "vue";
import { RouterLink } from "vue-router";
import LoginForm from "@/components/forms/LoginForm.vue";

const {Authenticate} = useAuthStore()

const formData = reactive({
    email: '',
    password: ''
})



</script>


<template>
  <div class="grid min-h-svh lg:grid-cols-2">
    <div class="flex flex-col gap-4 p-6 md:p-10">
      <div class="flex justify-center gap-2 md:justify-start">
        <RouterLink to="/" class="flex items-center gap-2 font-medium">
          <div class="bg-primary text-primary-foreground flex size-6 items-center justify-center rounded-md">
            <GalleryVerticalEnd class="size-4" />
          </div>
          MyTacloban
        </RouterLink>
      </div>
      <div class="flex flex-1 items-center justify-center">
        <div class="w-full max-w-xs">
          <LoginForm />
        </div>
      </div>
    </div>
    <div class="bg-muted relative hidden lg:block">
      <img
        src="https://placehold.co/600x400"
        alt="Image"
        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
      >
    </div>
  </div>
</template>
