<script>
    import { Phone, Mail } from 'lucide-vue-next';

    export default {
    name: 'BarangayTable',
    components: { Phone, Mail },
    props: {
        barangays: Array
    }
    }
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div 
      v-for="(barangay, index) in barangays" 
      :key="barangay.id"
      :class="['p-4 flex items-center justify-between', index !== barangays.length - 1 && 'border-b border-gray-200']"
    >
      <div class="flex items-center gap-3 flex-1">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
          <span class="text-gray-600 font-semibold text-lg">
            {{ barangay.name.charAt(0) }}
          </span>
        </div>
        <div>
          <h3 class="font-semibold text-gray-800">{{ barangay.name }}</h3>
          <p class="text-sm text-gray-500">{{ barangay.captain }}</p>
        </div>
      </div>
      <div class="bg-green-50 text-green-700 text-xs px-3 py-1.5 rounded inline-flex items-center gap-1">
        <span>📍</span>
        <span>Barangay Name Here</span>
      </div>
      <div class="flex items-center gap-6 text-sm text-gray-600 ml-6">
        <div class="flex items-center gap-2">
          <Phone class="w-4 h-4" />
          <span>{{ barangay.phone }}</span>
        </div>
        <div class="flex items-center gap-2">
          <Mail class="w-4 h-4" />
          <span>{{ barangay.email }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
