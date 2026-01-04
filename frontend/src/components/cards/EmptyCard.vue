<script setup lang="ts">
import { Bell, RefreshCcw } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty'
import reloadPage from "@/utils/pageReload"



const props = defineProps({
    emptyObject: Object
})



</script>

<template>
    <Empty class="from-muted/50 to-background h-full bg-gradient-to-b from-30%">
    <EmptyHeader>
        <EmptyMedia variant="icon" class="">
            <component :is="emptyObject.icon" />
        </EmptyMedia>
        <EmptyTitle>{{ emptyObject.title }}</EmptyTitle>
        <EmptyDescription>
        {{ emptyObject.description }}
        </EmptyDescription>
    </EmptyHeader>
    <EmptyContent>
        <Button variant="outline" size="sm" class="cursor-pointer" @click="reloadPage">
            <RefreshCcw />
            Refresh
        </Button>
    </EmptyContent>
    </Empty>
</template>
