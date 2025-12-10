<script setup>
    import GeneralSettings from "@/components/settings/GeneralSettings.vue"
    import CategoryManager from "@/components/others/CategoryManager.vue";
</script>

<template>
<div class="space-y-12 bg-gray-50 min-h-screen p-4 sm:p-8">
    <GeneralSettings />
    <hr class="border-gray-200 max-w-4xl mx-auto" />
    <CategoryManager />
</div>
</template>
