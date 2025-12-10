<script setup>
    import Button from '../ui/button/Button.vue';
    import Textarea from '../ui/textarea/Textarea.vue';
    import Label from '../ui/label/Label.vue';
    import Input from '../ui/input/Input.vue';
    import { reactive } from 'vue';
    import { useSubmitReport } from "@/stores/submitReport"
    
    const submitReportStore = useSubmitReport()
    const props = defineProps({ id: Number })
    
    const formData = reactive({
        status: "",
        description: "",
        id: props.id
    })
    
    const resetForm = () => {
        formData.status = "";
        formData.description = "";
        formData.id = props.id;
    }
    
    // ✨ add "saved" emit
    const emit = defineEmits(["close", "saved"])
    
    const handleSave = async () => {
        await submitReportStore.addNewTime(formData)
        emit("saved")  
        resetForm()
        emit("close")
    }
    </script>
    
    <template>
        <div class="bg-gray-100/50 p-4 shadow-sm rounded-md mb-4">
            <form @submit.prevent="handleSave" class="space-y-4">
                <div>
                    <Label>Add Title Note</Label>
                    <Input v-model="formData.status" placeholder="Describe the title" class="mt-2 bg-white"/>
                </div>
    
                <div>
                    <Label>Add Activity Note</Label>
                    <Textarea v-model="formData.description" placeholder="Describe the activity or update" class="h-30 mt-2 bg-white"/>
                </div>
    
                <div class="mt-2">
                    <Button size="sm" type="submit" class="mr-4 cursor-pointer">Save Activity</Button>
                    <Button size="sm" variant="outline" class="cursor-pointer" @click="emit('close')">Cancel</Button>
                </div>
            </form>
        </div>
    </template>
    