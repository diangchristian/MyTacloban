<script setup lang="ts">

import { Button } from './ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from './ui/card'
import { Label } from './ui/label';

</script>

<template>
    <Card class="flex flex-row pl-5">
        <CardHeader class="border-t-3 border-t-[#54D591] p-5 flex-2">
            <CardTitle class="w-full font-bold text-2xl">UWAN NOW A SUPER TYPHOON, SIGNAL NO. 2 UP IN TACLOBAN</CardTitle>
        </CardHeader>
        <CardContent class="flex-1">
            <img 
            src="@/assets/images/news-sample.png" 
            class="w-full"
            alt="">
        </CardContent>
    </Card>


</template>