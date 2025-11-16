<script setup>
import { Button } from '../ui/button';
</script>

<template>
     <div class="overflow-hidden  py-16 sm:pt-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid max-w-3xl grid-cols-1 gap-x-4 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
          <div class="lg:pt-4 lg:pr-8  ">
            <div class="lg:max-w-xl">
              <h2 class="text-xs font-semibold text-indigo-400 bg-primary/10 border-2 border-primary text-primary w-fit px-4 py-2 rounded-full mt-4 ">Official Platform of the Tacloban City</h2>
              <p class="text-4xl font-semibold tracking-tight text-pretty  sm:text-5xl mt-4">Empower Your <span class="text-primary">Community</span></p>
              <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty  sm:text-5xl">Connect With Tacloban, Anytime.</p>
              <p class="mt-4 text-lg/8 ">Access city updates, submit reports, and stay informed — all in one trusted digital platform built for every Taclobanon.</p>
              
              <Button class="mt-4 cursor-pointer">Get Started</Button>
    
            </div>
          </div>

          <img width="2000" height="1442" src="@/assets/images/Mockup2.png" alt="Product screenshot" 
          class="w-2xl max-w-none rounded-xl shadow-xl ring-1 ring-white/10 sm:w-228 md:-ml-4 lg:-ml-0 " />
        </div>
      </div>
    </div>
</template>