<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: String,   // current selected category
  category: Object,
});

const emit = defineEmits(["update:modelValue"]);

// Determine if this radio is selected
const checked = computed(() => props.modelValue === props.category.value);

console.log(props.category.bg)
function select() {
  emit("update:modelValue", props.category.value);
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

    <div class=" size-10 md:size-12  border border-gray-400 {{ category.bg }} flex items-center justify-center rounded-md"
          :class="checked ? 'bg-primary' : category.bg"
        
    >
    <component :is="category.icon"   :class="category.value !=  'other_issues' ? 'text-white' : 'text-black' " />
    </div>

    <p class="text-sm sm:text-md  mt-2">{{ category.label }}</p>
  </label>
</template>
