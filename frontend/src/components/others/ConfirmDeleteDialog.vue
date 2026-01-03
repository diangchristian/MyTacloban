<script setup>
  import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
  } from '@/components/ui/alert-dialog'
  
  import { useDialogStore } from '@/stores/dialogStore'
  
  const dialogStore = useDialogStore()
  console.log(dialogStore.variant)
  const buttonVariant = dialogStore.variant === 'danger' ? 'bg-destructive hover:bg-red-600' : 'bg-orange-400 hover:bg-orange-600'
  </script>
  
  <template>
    <AlertDialog v-model:open="dialogStore.open">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>{{ dialogStore.title }}</AlertDialogTitle>
          <AlertDialogDescription>{{ dialogStore.description }}</AlertDialogDescription>
        </AlertDialogHeader>
  
        <AlertDialogFooter>
          <AlertDialogCancel @click="dialogStore.close">Cancel</AlertDialogCancel>
          <AlertDialogAction :class="buttonVariant" @click="dialogStore.confirm">
            {{ dialogStore.confirmText }}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </template>
  