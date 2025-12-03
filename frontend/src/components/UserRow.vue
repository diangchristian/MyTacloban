<script setup>
import { defineProps, defineEmits } from 'vue'
import { MoreHorizontal } from 'lucide-vue-next'

import { TableRow, TableCell } from '@/components/ui/table'
import { Checkbox } from '@/components/ui/checkbox'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem
} from '@/components/ui/dropdown-menu'

const props = defineProps({
  person: { type: Object, required: true },
  selected: { type: Boolean, required: true }
})

const emit = defineEmits(['view', 'edit', 'delete', 'toggle'])

const getStatusVariant = (status) => {
  const variants = {
    Active: 'default',
    Inactive: 'inactive',
    Blocked: 'destructive',
    Pending: 'pending',
  }
  return variants[status] || 'default'
}

</script>

<template>
  <TableRow>
    <TableCell>
      <Checkbox
        :checked="selected"
        @update:checked="() => emit('toggle', person.id)"
      />
    </TableCell>

    <TableCell>{{ person.name }}</TableCell>
    <TableCell>{{ person.email }}</TableCell>
    <TableCell>{{ person.role }}</TableCell>

    <TableCell>
      <Badge :variant="getStatusVariant(person.status)"> 
        {{ person.status }}
      </Badge>
    </TableCell>

    <TableCell>{{ person.dateJoined }}</TableCell>

    <TableCell>
      <DropdownMenu>
        <DropdownMenuTrigger as-child class="cursor-pointer">
          <Button variant="ghost" size="icon">
            <MoreHorizontal class="h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end">
          <DropdownMenuItem @click="emit('view', person.id)" class="cursor-pointer">View Details</DropdownMenuItem>
          <DropdownMenuItem @click="emit('edit', person.id)" class="cursor-pointer">Edit</DropdownMenuItem>
          <DropdownMenuItem @click="emit('delete', person.id)" class="text-red-600 cursor-pointer">
            Delete
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </TableCell>
  </TableRow>
</template>
