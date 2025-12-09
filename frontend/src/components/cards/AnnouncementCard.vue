<script setup>
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { defineProps } from "vue";
import Button from "../ui/button/Button.vue";
import { useRouter } from "vue-router";
import { useDialogStore } from '@/stores/dialogStore'
import { computed } from "vue";


const router = useRouter()
const props = defineProps({
    role: {
        type: String,
    },
    announcement: {
        type: Object
    }
});

const id = props.announcement.announcement_id

function stripHtml(html) {
  return html.replace(/<[^>]*>/g, "");
}

const edithandler = () => {

  router.push({name: 'admin.announcements-edit', params: { id }})
}

const categoryColors = {
  "Holiday": "bg-yellow-100 text-yellow-800",
  "Notice": "bg-blue-100 text-blue-800",
  "Public Service": "bg-teal-100 text-teal-800",
  "Emergency": "bg-red-100 text-red-800",
  "Community Event": "bg-green-100 text-green-800",
  "General Announcement": "bg-gray-100 text-gray-800"
};

const categoryClass = computed(() => {
  return categoryColors[props.announcement.category_name] || "bg-gray-100 text-gray-800";
});

</script>


<template>
    <Card class="m-0">
        <CardHeader>
            <CardTitle class="text-md">
                <div class="flex items-center justify-between w-full mb-2">
                    <span :class="`px-2 py-1 text-xs rounded-lg ${categoryClass}`">
                        {{ announcement.category_name }}
                    </span>
                    <p class="text-gray-400 text-xs font-regular">2 hours ago</p>
                    </div>
                {{announcement.title}}
            </CardTitle>
            <CardDescription class="mt-2">
                {{ announcement.body }}
            </CardDescription>
        </CardHeader>
        <CardContent class="mt-auto">

            <!-- add kit emit here per button depending ha action -->
            <template v-if="role === 'admin'">
                <Button variant="outline" class="mr-2 cursor-pointer"  @click="edithandler">Edit</Button>
                <Button variant="destructive" class="cursor-pointer" @click="$emit('delete', announcement)">Delete</Button>
            </template>
            <template v-else>
                <Button variant="outline" class="cursor-pointer" @click="$emit('view', announcement)" >Read More</Button>
            </template>
        </CardContent>
    </Card>
  
</template>