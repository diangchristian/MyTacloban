// stores/dialog.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDialogStore = defineStore('dialog', () => {
  const open = ref(false)
  const title = ref('Are you absolutely sure?')
  const description = ref('This action cannot be undone.')
  const confirmText = ref('Continue')
  let onConfirm = null

  function openConfirm({ title: t, description: d, confirmText: c, onConfirm: fn }) {
    title.value = t ?? title.value
    description.value = d ?? description.value
    confirmText.value = c ?? confirmText.value
    onConfirm = fn
    open.value = true
  }

  function confirm() {
    if (onConfirm) onConfirm()
    close()
  }

  function close() {
    open.value = false
    onConfirm = null
  }

  return { open, title, description, confirmText, openConfirm, confirm, close }
})
