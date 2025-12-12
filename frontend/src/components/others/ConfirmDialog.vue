<script setup>
import { ref } from "vue";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from "@/components/ui/dialog";
import Button from "@/components/ui/button/Button.vue";
import { AlertCircle } from "lucide-vue-next";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  title: { type: String, default: "Confirm Action" },
  message: { type: String, default: "Are you sure?" },
  confirmText: { type: String, default: "Delete" },
  cancelText: { type: String, default: "Cancel" },
  isDangerous: { type: Boolean, default: true }, // Red color for dangerous actions
});

const emit = defineEmits(["confirm", "cancel"]);

const handleConfirm = () => {
  emit("confirm");
};

const handleCancel = () => {
  emit("cancel");
};
</script>

<template>
  <Dialog :open="isOpen" @update:open="(val) => !val && handleCancel()">
    <DialogContent class="max-w-sm">
      <DialogHeader>
        <div class="flex items-center gap-3">
          <AlertCircle :class="isDangerous ? 'text-red-600' : 'text-amber-600'" class="size-6" />
          <DialogTitle>{{ title }}</DialogTitle>
        </div>
        <DialogDescription>{{ message }}</DialogDescription>
      </DialogHeader>

      <div class="flex justify-end gap-3 pt-4">
        <Button variant="outline" @click="handleCancel" class="min-w-[100px]">
          {{ cancelText }}
        </Button>
        <Button
          @click="handleConfirm"
          :class="isDangerous ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-green-600 text-white hover:bg-green-700'"
          class="min-w-[100px]"
        >
          {{ confirmText }}
        </Button>
      </div>
    </DialogContent>
  </Dialog>
</template>
