<script setup>
import StartList from "@/components/StartList.vue"
</script>

<template>
  <div class="overflow-hidden py-12  ">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <h1
        class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl text-center"
      >
        What is <span class="text-primary">MyTacloban</span>?
      </h1>
      <div
        class="mx-auto grid max-w-3xl grid-cols-1 gap-x-4 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:mt-14"
      >
        <img
          width="2000"
          height="1442"
          src="https://tailwindcss.com/plus-assets/img/component-images/dark-project-app-screenshot.png"
          alt="Product screenshot"
          class="w-2xl max-w-none rounded-xl shadow-xl ring-1 ring-white/10 -ml-78 sm:w-228 md:-ml-4 lg:-ml-84 order-2 lg:order-1"
        />

        <div class="lg:pt-4 lg:pr-8 order-1 lg:order-2">
          <div class="lg:max-w-xl  ">
            <p class="mt-4 lg:mt-0 text-lg/8">
              MyTacloban is an innovative web-based platform developed for the
              Tacloban City Local Government Unit (LGU).  It aims to modernize
              how citizens and the government connect — replacing scattered
              manual processes with one centralized digital system.
            </p>

            <dl class="mt-10 max-w-xl space-y-6 text-base/7 text-gray-600 lg:max-w-none">

                <div class="relative flex items-start">
                    <dt class="text-primary">
                        <StartList />
                    </dt>

                    <dd class="pl-4">
                        Provides quick access to verified LGU announcements and advisories.
                    </dd>
                </div>
                    <div class="relative flex items-start">
                    <dt class="text-primary">
                        <StartList />
                    </dt>

                    <dd class="pl-4">
                        Lets residents report community issues online and monitor their status.
                    </dd>
                </div>
                <div class="relative flex items-start">
                    <dt class="text-primary">
                        <StartList />
                    </dt>

                    <dd class="pl-4">
                        Helps the city government manage, respond, and track concerns efficiently.
                    </dd>
                </div>
                <div class="relative flex items-start">
                    <dt class="text-primary">
                        <StartList />
                    </dt>

                    <dd class="pl-4">
                        Strengthens transparency and accountability in local governance.                    </dd>
                </div>
            

            </dl>

          </div>
        </div>

        
      </div>
    </div>
  </div>
</template>
