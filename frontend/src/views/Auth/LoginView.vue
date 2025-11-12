<script setup lang="ts">
import { Input } from "@/components/ui/input"
import {Button} from "@/components/ui/button"
import { useAuthStore } from "@/stores/auth";
import { reactive } from "vue";
import { RouterLink } from "vue-router";

const {Authenticate} = useAuthStore()

const formData = reactive({
    email: '',
    password: ''
})



</script>


<template>
    <main>
      <div class="max-w-lg mt-28 mx-auto p-4">
        <h1 class="text-2xl font-semibold text-center">Login to your account</h1>
  
        <form class="w-full max-w-md mt-6" @submit.prevent="Authenticate('login',formData)">
          <Input
            type="email"
            placeholder="Enter email"
            class="w-full p-2 rounded"
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
  
          <button
            type="submit"
            class="mt-4 bg-green-300 w-full hover:bg-green-500 cursor-pointer py-2 rounded"
            >
            Login
        </button>

        <p class="mt-4 text-slate-600">Don't have an account? <a href="/register" class="text-primary underline">Register</a></p>

        </form>
      </div>
    </main>
  </template>