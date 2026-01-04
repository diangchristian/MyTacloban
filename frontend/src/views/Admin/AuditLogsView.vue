<script setup>
import {
  Table,
  TableBody,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
  TableCell
} from '@/components/ui/table'
import {useAuditLogStore} from "@/stores/auditlogs"
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'

const auditLogs = useAuditLogStore()
const {logs} = storeToRefs(auditLogs)

const getActionStyle = (action) => {

    const map = {
        insert: {
            text: 'Created',
            border: 'border-green-500',
            bg: 'bg-green-500/10',
            dot: 'bg-green-500',
            textColor: 'text-green-600',
        },
        update: {
            text: 'Updated',
            border: 'border-orange-500',
            bg: 'bg-orange-500/10',
            dot: 'bg-orange-500',
            textColor: 'text-orange-600',
        },
        delete: {
            text: 'Deleted',
            border: 'border-red-500',
            bg: 'bg-red-500/10',
            dot: 'bg-red-500',
            textColor: 'text-red-600',
        },

    }

    return map[action];
}


const formattedDate = (date, type) => {
    const parsed = new Date(date)

    return type === 'date' ? parsed.toLocaleDateString() :
            type === 'time' ? parsed.toLocaleTimeString() : ''

}


onMounted(() => {
    auditLogs.fetchlogs()
})


console.log(getActionStyle('insert'))

</script>


<template>
    
    <div class="max-w-6xl mx-auto pb-4 ">
        <form action="" @submit.prevent class="mt-8">
            <div class="max-w-4xl flex flex-col sm:flex-row gap-4 sm:items-center w-full ">
                <Input placeholder="Search reports" class="w-full sm:w-1/2 bg-white" v-model="search" @keyup="handleSearch"/>
                <Button > Search </Button>
                <!-- Status Filter -->
            </div>
      </form>
        <div class="mt-4 bg-white rounded-md p-4 shadow-sm">
            <Table>
            <TableHeader class=" ">
              <TableRow class="row">
                <TableHead>User</TableHead>
                <TableHead>Action</TableHead>
                <TableHead>Date & Time</TableHead>
                <TableHead>Module</TableHead>
                <TableHead>Record ID</TableHead>
                <TableHead class="w-[70px]">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody >
                <TableRow v-for="log in logs" :key="log.id">
                    <TableCell class="flex flex-col">
                        <p class="font-semibold">{{ log.user.full_name }}</p>
                        <p class="text-xs">{{ log.user.email }}</p>
                    </TableCell>
                    <TableCell>
                        <div
                            :class="[
                            'rounded-md w-fit px-2 py-1 flex items-center gap-2 border',
                            getActionStyle(log.action.toLowerCase()).border,
                            getActionStyle(log.action.toLowerCase()).bg
                            ]"
                        >
                            <span
                            :class="[
                                'size-2 rounded-full',
                                getActionStyle(log.action.toLowerCase()).dot
                            ]"
                            ></span>

                            <span
                            :class="[
                                'text-xs font-semibold',
                                getActionStyle(log.action.toLowerCase()).textColor
                            ]"
                            >
                            {{ getActionStyle(log.action.toLowerCase()).text }}
                            </span>
                        </div>
                    </TableCell>
                            
                    <TableCell>
                        <p class="font-semibold">{{ formattedDate(log.created_at, 'date') }}</p>
                        <p class="text-xs">{{formattedDate(log.created_at, 'time') }}</p>
                    </TableCell>
                    <TableCell>{{ log.table_name }}</TableCell>
                    <TableCell>#{{ log.record_id }}</TableCell>
                    <TableCell>
                        <Button variant="ghost" size="sm">View</Button>
                    </TableCell>
                </TableRow>
              
            </TableBody>
          </Table>
        </div>
    </div>
</template>