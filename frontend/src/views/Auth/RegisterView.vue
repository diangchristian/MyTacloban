<script setup lang="ts">
import { Input } from "@/components/ui/input"
import {Button} from "@/components/ui/button"
import { useAuthStore } from "@/stores/auth";
import { reactive } from "vue";

const {Authenticate} = useAuthStore()

const formData = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})



</script>


<template>
    <main>
      <div class="max-w-lg mt-28 p-4 mx-auto">
        <h1 class="text-2xl font-semibold text-center">Create an account</h1>
  
        <form class="w-full max-w-md mt-6" @submit.prevent="Authenticate('register',formData)">

            <Input
            type="text"
            placeholder="Enter name"
            class="w-full p-2 rounded"
            autocomplete="username"
            v-model="formData.name"
          />

          <Input
            type="email"
            placeholder="Enter email"
            class="w-full p-2 rounded mt-4"
            autocomplete="username"
            v-model="formData.email"
          />
  
          <Input
            type="password"
            placeholder="Enter password"
            class="mt-4 w-full p-2 rounded"
            autocomplete="current-password"
            v-model="formData.password"
          />
          <Input
            type="password"
            placeholder="Confirm your password"
            class="mt-4 w-full p-2 rounded"
            autocomplete="current-password"
            v-model="formData.password_confirmation"
          />
  
          <button
            type="submit"
            class="mt-4 bg-green-300 w-full hover:bg-green-500 cursor-pointer py-2 rounded"
            >
            Login
        </button>
        <p class="mt-4 text-slate-600">Already have an account? <a href="/login" class="text-primary underline">Login</a></p>
        </form>
      </div>
    </main>
  </template>