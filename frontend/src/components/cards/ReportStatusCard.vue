<script setup>
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

</script>

<template>
    <div class="bg-white shadow-sm rounded-xl p-4">
        <h1>Manage Status</h1>
        <form action="" class="mt-4 ">
            <Label for="" class="mb-2">Current Status</Label>
            <Select >
                <SelectTrigger class="w-full mt-2">
                    <SelectValue placeholder="Select a status" class="w-full"/>
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="in_progress">
                        In progress
                    </SelectItem>
                    <SelectItem value="assigned">
                        Assigned
                    </SelectItem>
                    <SelectItem value="resolved">
                        Resolved
                    </SelectItem>
                    <SelectItem value="Rejected">
                        Rejected
                    </SelectItem>
                </SelectContent>
            </Select>
        </form>
    </div>

</template>