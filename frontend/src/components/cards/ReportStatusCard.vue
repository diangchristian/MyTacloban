<script setup>
    import { Label } from '@/components/ui/label'
    import {
      Select,
      SelectContent,
      SelectItem,
      SelectTrigger,
      SelectValue,
    } from '@/components/ui/select'
    
    const props = defineProps({
      status: {
        type: String,
        default: '' // empty if no status provided
      }
    })

    console.log(props.status)

    // reactive variable for Select
    import { ref, watch } from 'vue'
    const selectedStatus = ref(props.status)
    
    // update selectedStatus if props.status changes dynamically
    watch(() => props.status, (newStatus) => {
      selectedStatus.value = newStatus
    })
    </script>
    
    <template>
      <div class="bg-white shadow-sm rounded-xl p-4">
        <h1>Manage Status</h1>
        <form class="mt-4">
          <Label class="mb-2">Current Status</Label>
          <Select v-model="selectedStatus">
            <SelectTrigger class="w-full mt-2">
              <!-- placeholder shows only if selectedStatus is empty -->
              <SelectValue
                :placeholder="!selectedStatus ? 'Select a status' : ''"
                class="w-full"
              />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="pending">Pending</SelectItem>
              <SelectItem value="in_progress">In Progress</SelectItem>
              <SelectItem value="assigned">Assigned</SelectItem>
              <SelectItem value="resolved">Resolved</SelectItem>
              <SelectItem value="rejected">Rejected</SelectItem>
            </SelectContent>
          </Select>
        </form>
      </div>
    </template>
    