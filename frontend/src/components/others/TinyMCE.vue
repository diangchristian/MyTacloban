<script setup>
  import Editor from '@tinymce/tinymce-vue'
  
  const apiKey = import.meta.env.VITE_TMCE_API
  
  const props = defineProps({
    modelValue: {
      type: String,
      default: '',
    },
  })
  
  const emit = defineEmits(['update:modelValue'])
  </script>
  
  <template>
    <Editor
      :api-key="apiKey"
      :model-value="modelValue"
      @update:model-value="emit('update:modelValue', $event)"
      :init="{
        plugins: 'advlist anchor autolink charmap code fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        height: 500,
      }"
    />
  </template>
  