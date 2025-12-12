<script setup>
import { MapPin } from "lucide-vue-next";
import { onMounted, ref } from "vue";
import MapLocation from "../location/MapLocation.vue";
import ImagesModal from "@/components/others/ImagesModal.vue";
import { initFlowbite } from "flowbite";
import sample from "@/assets/images/news-sample.png";
import TimelineCard from "../cards/TimelineCard.vue";
import { useSubmitReport } from "@/stores/submitReport";
import { Skeleton } from "@/components/ui/skeleton";
import { storeToRefs } from "pinia";
import { MessageSquare } from "lucide-vue-next";
import { Separator } from "@/components/ui/separator";

const submitReportStore = useSubmitReport();
const { reportDetails, isLoading } = storeToRefs(submitReportStore);


const props = defineProps({
  report: Object,
});

onMounted(() => {
  initFlowbite();
});

const formatted = new Date(props.report.created_at).toLocaleString("en-US", {
  year: "numeric",
  month: "short",
  day: "2-digit",
  hour: "2-digit",
  minute: "2-digit",
});
</script>

<template>
  <div class="h-full overflow-y-scroll">
    <div class="space-y-4">
      <div class="rounded-md">
        <!-- ID + Date -->
        <div
          class="flex flex-wrap items-center justify-between w-full mb-2 text-sm text-gray-400 gap-1"
        >
          <div class="flex gap-2 items-center">
            <p>Report ID: #{{ report.id }}</p>
            <span
              :class="{
                'bg-yellow-100 text-yellow-800': report.status === 'pending',
                'bg-blue-100 text-blue-800': report.status === 'in_progress',
                'bg-green-100 text-green-800': report.status === 'resolved',
              }"
              class="px-2 py-1 rounded-full text-xs font-semibold flex-shrink-0"
            >
              {{ report.status }}
            </span>
          </div>
          <p>{{ formatted }}</p>
        </div>

        <!-- Header -->
        <div>
          <h1 class="text-xl md:text-3xl font-bold break-words">
            {{ report.title }}
          </h1>

          <div class="flex flex-wrap gap-2 mt-2 text-sm">
            <h2>Category:</h2>
            <span
              class="bg-red-500/15 px-2 py-1 text-xs rounded-lg text-destructive"
            >
              {{ report.category_name }}
            </span>
          </div>
        </div>
        <!-- Description -->
      </div>
      <Separator/>
      <div>
        <p class="font-semibold">Description:</p>
        <p class="text-gray-700 break-words">{{ report.description }}</p>
      </div>
      <Separator/>
      <div>
        <MapLocation :coordinates="report.coordinates" class="shadow-none" />
      </div>


      <Separator />
      <!-- Images -->
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div>
          <p class="font-semibold mb-2">Images:</p>

          <div class="flex flex-wrap gap-4" v-if="report.images">
            <button
              data-modal-target="default-modal"
              data-modal-toggle="default-modal"
              class="bg-primary border border-transparent shadow-xs focus:outline-none rounded-lg overflow-hidden"
            >
              <img
                :src="report.images[0]"
                alt="Mockup"
                class="w-20 sm:w-48 h-auto object-cover cursor-pointer"
              />
            </button>
          </div>
          <div class="" v-else>No images attached</div>

          <ImagesModal :images="report.images" />
        </div>
      </div>
      <Separator />
      <!-- timeline -->
      <div class="bg-white p-4 rounded-xl shadow-sm w-full">
        <div class="flex justify-between items-center mb-4">
          <h3 class="inline-flex items-center gap-2">
            <MessageSquare class="size-5 text-primary" />
            Activity Timeline
          </h3>
        </div>

        <div v-if="!isLoading">
          <Skeleton
            v-for="i in 3"
            :key="i"
            class="h-12 w-full rounded-md mb-2 bg-gray-200"
          />
        </div>
        <TimelineCard v-if="report" :timelines="report.timelines" />
      </div>
    </div>
  </div>
</template>
