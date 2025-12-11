<script setup>
import { computed } from "vue";
import {useCategoriesStore} from '@/stores/categories'

const props = defineProps({
  modelValue: Number,   // current selected category
  category: Object,
});



const categoriesStore = useCategoriesStore()

const emit = defineEmits(["update:modelValue"]);

// Determine if this radio is selected
const checked = computed(() => props.modelValue === props.category.id);

function select() {
  emit("update:modelValue", props.category.id);
}
</script>

<template>
  <label
    @click="select"
    class="border border-gray-400 flex flex-col p-2 items-center justify-center
           rounded-lg cursor-pointer transition-colors duration-200"
    :class="{
      'border-primary border-2 bg-primary/20': checked
    }"
  >
    <input
      type="radio"
      class="hidden"
      :checked="checked"
    />

    <div class=" size-10 md:size-12  border border-gray-400  flex items-center justify-center rounded-md"
          :style="{ backgroundColor: category.color }"      
    :class="checked ? 'bg-primary' : category.bg"
        
    >
    <component :is="categoriesStore.getIcon(category.icon_name)" class="text-white" />
    </div>

    <p class="text-sm sm:text-md  mt-2">{{ category.category_name }}</p>
  </label>
</template>
