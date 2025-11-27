<script setup>
import ReportHeaderCard from '@/components/cards/ReportHeaderCard.vue';
import ReportStatusCard from '@/components/cards/ReportStatusCard.vue';
import TimelineCard from '@/components/cards/TimelineCard.vue';
import ReportImages from '@/components/ReportImages.vue';
import MapLocation from '@/components/MapLocation.vue';
import Button from '@/components/ui/button/Button.vue';
import AddTimeline from '@/components/AddTimeline.vue';
import { ref } from 'vue';
import { MessageSquare } from 'lucide-vue-next';


const addNewActivity = ref(false)

const addActivity = () => {
    addNewActivity.value = true
}

</script>


<template>
    <main>
        <div class="w-full">
            <div class="grid  grid-cols-1   lg:grid-cols-3 gap-4">
                <div class="col-span-2 space-y-4">
                    <ReportHeaderCard class=""/>
                    <MapLocation/>
                    <ReportImages/>
                </div>
                <div class="flex flex-col gap-4">
                  <ReportStatusCard/>
                  <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="inline-flex items-center gap-2 "> 
                                <MessageSquare class="size-5 text-primary"/> 
                                Activity Timeline
                        </h3>
                        <Button size="sm" class="cursor-pointer" @click="addActivity">+ Add</Button>
                    </div>
                    <AddTimeline v-if="addNewActivity" @close="addNewActivity = false"/>
                    <TimelineCard/>
                  </div>
                </div>
            </div>
        </div>
    </main>
</template>
