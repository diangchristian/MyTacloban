<script setup>
import {
  Card,
  CardAction,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Newspaper, TrendingUp, TrendingDown  } from 'lucide-vue-next';
import { computed, defineProps } from "vue";

const props = defineProps({
  stat: {
    type: Object
  }
})


const trend = props.stat.value.lastWeek > props.stat.value.thisWeek ? 'decrease' : 'increase';
</script>



<template>
    <div class="">
      <Card class="@container/card">
      <CardHeader class="flex items-start justify-between">
        <div>
          <CardDescription class="text-black  font-semibold">{{stat.title}}</CardDescription>
          <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
            {{stat.value.count}}
          </CardTitle>
        </div>

        <!-- ICON ON THE RIGHT -->
        <div class="text-white  p-3 rounded-lg" :class="stat.bg">
          <!-- <Newspaper class="size-6 "  :class="stat.textColor"/> -->
          <component :is="stat.icon" class="size-6 " :class="stat.textColor" />
        </div>
      </CardHeader>

      <CardFooter class="flex-col items-start gap-1.5 text-sm">
        <!-- <div class="line-clamp-1 flex gap-2 font-medium">
          Trending up this month <TrendingUp  class="size-4 text-primary" />
        </div> -->
        <div class="text-muted-foreground flex gap-2 items-center font-regular">
          <div class="flex gap-2" v-if="trend ==='increase'">
              <span class="text-green-500 flex items-center gap-2">  <TrendingUp  class="size-4 " /> +12</span>
              from last week
          </div> 
          <div class="flex gap-2" v-else>
              <span class="text-red-500 flex items-center gap-2">  <TrendingDown  class="size-4 " /> -12</span>
              from last week
          </div> 

        </div>
      </CardFooter>
    </Card>

      
    </div>
  </template>


<style scoped>
/* *
{
  border: 1px solid red
} */

</style>