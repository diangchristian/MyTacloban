<script setup>
import {
  Card,
  CardAction,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { TrendingUp, TrendingDown  } from 'lucide-vue-next';
import { computed, defineProps } from "vue";

const props = defineProps({
  stat: {
    type: Object
  }
})

const thisWeek = computed(() => props.stat?.value?.thisWeek ?? 0);
const lastWeek = computed(() => props.stat?.value?.lastWeek ?? 0);
const delta = computed(() => thisWeek.value - lastWeek.value);
const trend = computed(() => delta.value > 0 ? 'increase' : delta.value < 0 ? 'decrease' : 'neutral');
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
        <div class="text-muted-foreground flex gap-2 items-center font-regular">
          <template v-if="trend === 'increase'">
            <span class="text-green-500 flex items-center gap-2">
              <TrendingUp class="size-4" /> {{ `+${delta}` }}
            </span>
            from last week
          </template>
          <template v-else-if="trend === 'decrease'">
            <span class="text-red-500 flex items-center gap-2">
              <TrendingDown class="size-4" /> {{ `-${Math.abs(delta)}` }}
            </span>
            from last week
          </template>
          <template v-else>
            <span class="text-gray-500 flex items-center gap-2">0</span>
            from last week
          </template>
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