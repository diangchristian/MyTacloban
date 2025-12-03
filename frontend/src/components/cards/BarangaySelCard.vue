<script>
    import { Phone, Mail } from 'lucide-vue-next';

    export default {
        name: 'BarangayCard',
        components: { Phone, Mail },
        props: {
        name: String,
        captain: String,
        phone: String,
        email: String
        }
    }
</script>
<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
    <div class="p-4">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
            <span class="text-gray-600 font-semibold text-lg">
              {{ name.charAt(0) }}
            </span>
          </div>
          <div>
            <h3 class="font-semibold text-gray-800">{{ name }}</h3>
            <p class="text-sm text-gray-500">{{ captain }}</p>
          </div>
        </div>
      </div>
      <div class="bg-green-50 text-green-700 text-xs px-3 py-1.5 rounded inline-flex items-center gap-1 mb-3">
        <span>📍</span>
        <span>Barangay Name Here</span>
      </div>
      <div class="space-y-2">
        <div class="flex items-center gap-2 text-sm text-gray-600">
          <Phone class="w-4 h-4" />
          <span>{{ phone }}</span>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600">
          <Mail class="w-4 h-4" />
          <span>{{ email }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
