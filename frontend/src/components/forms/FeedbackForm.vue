<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
    Field,
    FieldDescription,
    FieldGroup,
    FieldLabel,
    FieldSeparator,
} from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import { useAuthStore } from "@/stores/auth"
import { reactive } from "vue"
import { RouterLink } from "vue-router"
import FieldError from "./FieldError.vue"
import { storeToRefs } from "pinia"
import { Textarea } from '@/components/ui/textarea'

const props = defineProps<{
    class?: HTMLAttributes["class"]
}>()


const authStore = useAuthStore();
const {errors} = storeToRefs(authStore)
const formData = reactive({
    email: '',
    name: ''
})


const handleSubmit = () => {
    authStore.Authenticate('login',formData )
}



</script>

<template>
    <form :class="cn('flex flex-col gap-6', props.class)" @submit.prevent="handleSubmit">
        <FieldGroup>
            <FieldError v-if="errors.general" :errorMessage="errors.general[0]"/>
        
            <Field>
                <FieldLabel for="name">
                    Name
                </FieldLabel>
                <Input id="name" type="name"  v-model="formData.name"  placeholder="John Doe"/>
                 <FieldError v-if="errors.name" :errorMessage="errors.name[0]"/>
            </Field>
            <Field>
                <FieldLabel for="email">
                    Email
                </FieldLabel>
                <Input id="email" type="email" placeholder="m@example.com"  v-model="formData.email" />
                <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/>
            </Field>
            <Field>
                <FieldLabel for="feedback">
                    Feedback
                </FieldLabel>
                <Textarea placeholder="Type your message here." id="feedback"  class="h-40"/>
            </Field>
            <Field>
                <Button type="submit" class="cursor-pointer">
                    Submit Feedback
                </Button>
            </Field>
        </FieldGroup>
    </form>
</template>
