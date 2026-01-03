<script setup>
import {
  Card,
  CardAction,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

defineProps({
    cards: { type: Array, required: true }
})
</script>



<template>
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
        v-for="card in cards"
        :key="card.title"
        class=""
        >
        
        <div class="">
            <Card class="@container/card">
            <CardHeader class="flex items-start justify-between">
                <div>
                    <CardDescription class="text-black  font-semibold">{{card.title}}</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                        {{card.value}}
                    </CardTitle>
                </div>

                <!-- ICON ON THE RIGHT -->
                <div class="text-white  p-3 rounded-lg" :class="card.bg">
                    <component :is="card.icon" class="size-6 " :class="card.textColor" />
                </div>
            </CardHeader>
            </Card>

            
            </div>

            
           
        </div>
    </section>
</template>

