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
          <AlertDialogAction class="bg-destructive" @click="dialogStore.confirm">
            {{ dialogStore.confirmText }}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </template>
  