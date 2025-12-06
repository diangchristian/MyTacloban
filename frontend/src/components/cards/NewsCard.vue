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
import { ArrowRight, Star } from 'lucide-vue-next';
import images from "@/assets/images/news-sample.png";
const props = defineProps({
  news: {
    type: Object,
    required: true,
    default: () => ({
      title: "UWAN NOW A SUPER TYPHOON, SIGNAL NO. 2 UP IN TACLOBAN",
      body: "Typhoon Uwan has intensified into a super typhoon with Signal No. 2 raised over Tacloban and nearby areas.",
      image: images,
      isHighlight: false,
      date: "December 6, 2024",
      category: "Weather Alert"
    })
  }
});

const emit = defineEmits(['view-details']);
</script>

<template>
   <Card class="hover:shadow-lg transition-shadow cursor-pointer h-full flex flex-col p-0  overflow-hidden">
    <!-- content area -->
    <div class="flex flex-row flex-1  ">

        <!-- Left text section -->
        <CardHeader class="border-t-4 border-t-[#54D591] p-5 flex-[2] flex flex-col ">
            <div class="space-y-3 flex-1">
                <div class="flex items-center justify-between">
                    <span v-if="news.isHighlight"
                        class="bg-yellow-300 px-2 py-1 text-xs font-semibold gap-2 rounded-full inline-flex items-center uppercase"> 
                        
                        <Star class="text-yellow-500 size-4"/> Highlight</span>
                    <div v-if="news.category" class="text-xs text-[#54D591] font-semibold uppercase">
                    {{ news.category }}
                    </div>
                </div>

                <CardTitle class="w-full font-bold text-2xl line-clamp-3">
                    {{ news.title }}
                </CardTitle>

                <span v-if="news.date" class="text-xs -mt-2 flex text-gray-500">
                    {{ news.date }}
                </span>

                <CardDescription v-if="news.body" class="text-sm text-gray-600 line-clamp-2">
                    {{ news.body }}
                </CardDescription>
            </div>

            <Button 
                variant="ghost" 
                size="sm"
                @click.stop="emit('view-details')"
                class="text-white hover:text-[#54D591]/80 hover:bg-primary/10 bg-primary transition mt-4"
            >
                Read More
                <ArrowRight class="ml-2 h-4 w-4" />
            </Button>
        </CardHeader>

        <!-- Right image section -->
        <CardContent class="flex-1 p-0 overflow-hidden max-w-[50%]"> 
            <img 
                :src="images" 
                class="w-full h-full object-cover"
                :alt="news.title"
            >
        </CardContent>
    </div>

    <!-- footer -->
    <!-- <CardFooter class="px-7 py-3">
        <Button 
            variant="ghost" 
            size="sm"
            @click.stop="emit('view-details')"
            class="text-white hover:text-[#54D591]/80 hover:bg-primary/10 bg-primary transition"
        >
            Read More
            <ArrowRight class="ml-2 h-4 w-4" />
        </Button>
    </CardFooter> -->
</Card>

</template>


<!-- <style scoped>
*{
    border: 1px solid red
}
</style> -->