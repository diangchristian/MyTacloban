<script setup>
import Editor from '@tinymce/tinymce-vue'
const apiKey = import.meta.env.VITE_TMCE_API;
</script>

<template>
  <main id="sample">
    <editor
      id="uuid"
      :apiKey="apiKey"
      :init="{
        plugins: 'advlist anchor autolink charmap code fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        height: 500,
      }"
    />
  </main>
</template>

<style scoped>

/* 
@media (min-width: 1024px) {
  #sample {
    display: flex;
    flex-direction: column;
    place-items: center;
    width: 1000px;
  }
} */
</style>