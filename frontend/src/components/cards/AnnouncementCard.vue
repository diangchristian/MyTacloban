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
import {useAuthStore} from "@/stores/auth"



const authStore = useAuthStore()

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


console.log(authStore.userRole)
const edithandler = () => {

  router.push({name:  authStore.userRole === 'LGU_ADMIN'  ? 'admin.announcements-edit' : 'barangay.announcements-edit', params: { id }})
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


function  timeAgo(date) {
    const now = new Date()
    const past = new Date(date)
    const diffInSeconds = Math.floor((now - past) / 1000)

    const minutes = Math.floor(diffInSeconds / 60)
    const hours = Math.floor(minutes / 60)
    const days = Math.floor(hours / 24)
    const weeks = Math.floor(days / 7)

    if (minutes < 1) return 'Just now'
    if (minutes < 60) return `${minutes} min${minutes > 1 ? 's' : ''} ago`
    if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`
    if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`
    return `${weeks} week${weeks > 1 ? 's' : ''} ago`
  }
</script>


<template>
    <Card class="m-0">
        <CardHeader>
            <CardTitle class="text-md line-clamp-2">
                <div class="flex items-center justify-between w-full mb-2">
                    <span :class="`px-2 py-1 text-xs rounded-lg ${categoryClass}`">
                        {{ announcement.category_name }}
                    </span>
                    <p class="text-gray-400 text-xs font-regular">
                        {{ timeAgo(announcement.created_at) }}
                        </p>


                    </div>
                {{announcement.title}}
            </CardTitle>
            <CardDescription class="mt-2">
                <div v-html="announcement.body" class="line-clamp-5"></div>
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

