<script setup>
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardAction,
    CardDescription,
    CardTitle

} from '@/components/ui/card'

</script>

<template>
  <Card class="w-full max-w-sm mx-auto overflow-hidden border border-gray-100 shadow-lg rounded-xl">

    <!-- Header -->
    <CardHeader class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-4 rounded-tl-lg rounded-tr-lg">
      <h2 class="text-lg font-bold mb-1">Nula-Tula (Brgy. 3 & 3A)</h2>
      <div class="flex items-center gap-1 text-sm opacity-90">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
        </svg>
        <span>Tacloban City, Leyte</span>
      </div>
    </CardHeader>

    <!-- Stats -->
    <CardContent class="bg-gray-50 p-4">
      <div class="grid grid-cols-3 gap-4">

        <!-- Population -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-blue-600 mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span class="font-bold text-lg">2,663</span>
          </div>
          <span class="text-xs text-gray-500">Population</span>
        </div>

        <!-- Households -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-purple-600 mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="font-bold text-lg">2,663</span>
          </div>
          <span class="text-xs text-gray-500">Households</span>
        </div>

        <!-- Area -->
        <div class="flex flex-col items-center">
          <div class="flex items-center gap-1 text-orange-600 mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span class="font-bold text-lg">0.2782 km²</span>
          </div>
          <span class="text-xs text-gray-500">Area Coverage</span>
        </div>

      </div>
    </CardContent>

    <!-- Contact Info -->
    <CardContent class="p-4 space-y-3">

      <!-- Contact Person -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Contact Person</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          John Doe I
        </div>
      </div>

      <!-- Contact Number -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Contact Number</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          +639062688526
        </div>
      </div>

      <!-- Email -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Email</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
          </svg>
          johndoe@email.com
        </div>
      </div>

      <!-- Address -->
      <div>
        <div class="text-xs text-gray-400 mb-1">Address</div>
        <div class="flex items-center gap-2 text-sm text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
            <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
          </svg>
          Tacloban City, Leyte
        </div>
      </div>

    </CardContent>

    <!-- Footer -->
    <CardFooter class="p-4 pt-2 pb-1">
      <Button class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg hover:cursor-pointer">
        View Details
      </Button>
    </CardFooter>
  </Card>
</template>

