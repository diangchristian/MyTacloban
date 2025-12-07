
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDialogStore = defineStore('dialog', () => {
  const deleteDialogOpen = ref(false)
  return { deleteDialogOpen }
})
