<script setup>
import {
  Empty,
  EmptyContent,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty'
import { TextSelect } from 'lucide-vue-next';

</script>

<template>
     <Empty>
        <EmptyHeader>
        <EmptyMedia variant="icon">
            <TextSelect/>
        </EmptyMedia>
        <EmptyTitle>No Reports Select</EmptyTitle>
        <EmptyDescription>
            You haven't select any reports yet. Get started by clicking your reports.
        </EmptyDescription>
        </EmptyHeader>
        <EmptyContent>
        <div class="flex gap-2">
            <!-- <Button>Create Project</Button>
            <Button variant="outline">
            Import Project
            </Button> -->
        </div>
        </EmptyContent>
  </Empty>
</template>