<script setup>
import Button from './ui/button/Button.vue';
import Textarea from './ui/textarea/Textarea.vue';
import Label from './ui/label/Label.vue';


const emit = defineEmits(["close"])


</script>

<template>
    <div class=" bg-gray-100/50 p-4 shadow-sm rounded-md mb-4">
        <form action="">
            <Label>Add Activity Note</Label>
            <Textarea placeholder="Describe the activity or update"  class="h-30 mt-2 bg-white" />
            <div class="mt-2">
                <Button size="sm" class="mr-4 cursor-pointer">Save Activity</Button>
                <Button size="sm" variant="outline"  class="cursor-pointer"  @click="emit('close')">Cancel</Button>
            </div>
        </form>
    </div>
</template>