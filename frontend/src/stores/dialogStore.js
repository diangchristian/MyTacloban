// stores/dialog.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDialogStore = defineStore('dialog', () => {
  const open = ref(false)
  const title = ref('')
  const description = ref('')
  const confirmText = ref('Confirm')
  const cancelText = ref('Cancel')
  const variant = ref('default') // danger | warning | info | success

  let onConfirm = null
  let onCancel = null

  function openConfirm({
    title: t,
    description: d,
    confirmText: c,
    cancelText: cancel,
    variant: v,
    onConfirm: confirmFn,
    onCancel: cancelFn,
  }) {
    title.value = t
    description.value = d
    confirmText.value = c ?? 'Confirm'
    cancelText.value = cancel ?? 'Cancel'
    variant.value = v ?? 'default'
    onConfirm = confirmFn
    onCancel = cancelFn
    open.value = true
  }

  function confirm() {
    onConfirm?.()
    close()
  }

  function cancel() {
    onCancel?.()
    close()
  }

  function close() {
    open.value = false
    onConfirm = null
    onCancel = null
  }

  return {
    open,
    title,
    description,
    confirmText,
    cancelText,
    variant,
    openConfirm,
    confirm,
    cancel,
    close,
  }
})
