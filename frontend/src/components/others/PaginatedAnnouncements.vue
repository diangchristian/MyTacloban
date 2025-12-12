<script setup>
    import { ref, computed, watch } from 'vue'
    import {
      Pagination,
      PaginationContent,
      PaginationEllipsis,
      PaginationItem,
      PaginationNext,
      PaginationPrevious,
    } from '@/components/ui/pagination'
    import { useRoute, useRouter } from 'vue-router'
    import { Skeleton } from "@/components/ui/skeleton";
    import { useAnnouncementStore } from "@/stores/announcements";
    import { storeToRefs } from 'pinia';
    import AnnouncementCard from '@/components/cards/announcementCard.vue';
    import { useDialogStore } from "@/stores/dialogStore";
    const dialogStore = useDialogStore();

    const announcementStore = useAnnouncementStore();
    const {isLoading} = storeToRefs(announcementStore);
    const props = defineProps({
      announcements: {
        type: Array,
        required: true,
        default: () => [],
      },
    })
    
    const route = useRoute()
    const router = useRouter()
    
    const perPage = 8
    const currentPage = ref(Number(route.query.page) || 1)
    const selectedAnnouncement = ref(null);
  
    watch(
      () => props.announcements.length,
      () => {
        const maxPage = Math.max(1, Math.ceil(props.announcements.length / perPage))
        if (currentPage.value > maxPage) {
          currentPage.value = maxPage
        }
      },
    )
    
    const total = computed(() => props.announcements.length)
    
    const paginatedAnnouncements = computed(() => {
      const start = (currentPage.value - 1) * perPage
      const end = start + perPage
      return props.announcements.slice(start, end)
    })
    
    function onPageChange(newPage) {
      currentPage.value = newPage
      router.replace({
        query: { ...route.query, page: newPage },
      })
    }
    
    const deleteHandler = (announcement) => {
    selectedAnnouncement.value = announcement;
    dialogStore.openConfirm({
        title: "Delete Announcement",
        description: "This will permanently delete the annoucement.",
        confirmText: "Delete Announcment",
        onConfirm: () => {
        console.log(selectedAnnouncement.value);
        announcementStore.deleteAnnouncment(
            selectedAnnouncement.value.announcement_id
        );
        selectedAnnouncement.value = null;
        announcementStore.getAnnouncement();
        announcementStore.getStats();
        },
    });
    };

    </script>
    
    <template>
      <div class="space-y-4 mt-4">
    
        <!-- Cards -->
        <div v-if="isLoading" class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                  <Skeleton v-for="i in 8" :key="i" class="h-96 w-full rounded-md mb-2 bg-gray-200" />
          </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
         
            <AnnouncementCard
            v-for="announcement in paginatedAnnouncements"
            :key="announcement.id"
            role="admin"
            :announcement="announcement"
            @delete="deleteHandler"
        />
        </div>
    
        <!-- Pagination -->
        <Pagination
          class="pb-4"
          v-slot="{ page }"
          :items-per-page="perPage"
          :total="total"
          :default-page="currentPage"
          @update:page="onPageChange"
        >
          <PaginationContent v-slot="{ items }" class="mt-auto">
            <PaginationPrevious />
    
            <template v-for="(item, index) in items" :key="index">
              <PaginationItem
                v-if="item.type === 'page'"
                :value="item.value"
                :is-active="item.value === page"
              >
                {{ item.value }}
              </PaginationItem>
            </template>
    
            <PaginationEllipsis />
            <PaginationNext />
          </PaginationContent>
        </Pagination>
    
      </div>
    </template>
    

    