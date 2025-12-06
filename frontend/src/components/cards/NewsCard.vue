<script setup>
import { Button } from '../ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '../ui/card'
import { Label } from '../ui/label';
import { ArrowRight } from 'lucide-vue-next';

const props = defineProps({
  news: {
    type: Object,
    required: true,
    default: () => ({
      title: "UWAN NOW A SUPER TYPHOON, SIGNAL NO. 2 UP IN TACLOBAN",
      summary: "Typhoon Uwan has intensified into a super typhoon with Signal No. 2 raised over Tacloban and nearby areas.",
      image: "@/assets/images/news-sample.png",
      date: "December 6, 2024",
      category: "Weather Alert"
    })
  }
});

const emit = defineEmits(['view-details']);
</script>

<template>
    <Card class="flex flex-col hover:shadow-lg transition-shadow cursor-pointer h-full">
        <div class="flex flex-row flex-1">
            <CardHeader class="border-t-3 border-t-[#54D591] p-5 flex-[2] flex flex-col">
                <div class="space-y-3 flex-1">
                    <div v-if="news.category" class="text-xs text-[#54D591] font-semibold uppercase">
                        {{ news.category }}
                    </div>
                    <CardTitle class="w-full font-bold text-2xl line-clamp-3">
                        {{ news.title }}
                    </CardTitle>
                    <CardDescription v-if="news.summary" class="text-sm text-gray-600 line-clamp-2">
                        {{ news.summary }}
                    </CardDescription>
                </div>
            </CardHeader>
            <CardContent class="flex-1 p-0 overflow-hidden">
                <img 
                    :src="news.image" 
                    class="w-full h-full object-cover"
                    :alt="news.title">
            </CardContent>
        </div>
        <CardFooter class="px-7 py-3 flex items-center justify-between ">
            <span v-if="news.date" class="text-xs text-gray-500">{{ news.date }}</span>
            <Button 
                variant="ghost" 
                size="sm"
                @click.stop="emit('view-details')"
                class="text-[#54D591] hover:text-[#54D591]/80 hover:bg-[#54D591]/10 transition-colors cursor-pointer"
            >
                Read More
                <ArrowRight class="ml-2 h-4 w-4" />
            </Button>
        </CardFooter>
    </Card>
</template>