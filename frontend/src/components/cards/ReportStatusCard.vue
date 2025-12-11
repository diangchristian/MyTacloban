<script setup>
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { useSubmitReport } from "@/stores/submitReport"
import { ref, watch } from 'vue'
import {useAuthStore} from "@/stores/auth"


const authStore = useAuthStore()


const submitReportStore = useSubmitReport()

const props = defineProps({
  status: {
    type: String,
    default: '' // initial status
  },
  id: {
    type: Number,
    required: true  
  }
})

// reactive variable for Select
const selectedStatus = ref(props.status)

// keep selectedStatus in sync if props.status changes externally
watch(() => props.status, (newStatus) => {
  selectedStatus.value = newStatus
})

// 🔥 watch selectedStatus and update report status immediately when changed
watch(selectedStatus, (newStatus, oldStatus) => {
  if (newStatus && newStatus !== oldStatus) {
    submitReportStore.updateReportStatus(props.id, newStatus, authStore.user.id)
  }
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
    