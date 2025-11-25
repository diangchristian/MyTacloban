<script setup lang="ts">

import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarFooter
} from "@/components/ui/sidebar"
import NavUser from "./NavUser.vue"
import getNavigations from "@/utils/navigations.js"
import { useRoute } from "vue-router"
import { RouterLink } from "vue-router"

const route = useRoute();
// Menu items.
const data = { 
  user: {
    name: "shadcn",
    email: "m@example.com",
    avatar: "/avatars/shadcn.jpg",
  }
}

const navigations = getNavigations(route.meta.role[0])
const isActive = (navPath) => {
    return route.path === navPath
}



</script>

<template>
  <Sidebar>
    <SidebarContent>
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" class="px-4 mt-4">
              <div class="flex aspect-square size-8  items-center justify-center rounded-lg bg-primary text-sidebar-primary-foreground">
                <GalleryVerticalEnd class="size-4" />
              </div>
              <div class="grid flex-1 text-left text-sm leading-tight">
                <span class="truncate font-semibold">MyTacloban</span>
              </div>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>
      <SidebarGroup>
        <SidebarGroupLabel>Navigation</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
              <SidebarMenuItem v-for="link in navigations" :key="link.title">
                <SidebarMenuButton as-child  :is-active="isActive(link.url)" class="data-[state=active]:sidebar-active">          
                    <RouterLink :to="link.url">
                      <component :is="link.icon" />
                      <span>{{ link.title }}</span>
                    </RouterLink>
                </SidebarMenuButton>
              </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
     
    </SidebarContent>
    <SidebarFooter>
        <NavUser :user="data.user" />
    </SidebarFooter>
  </Sidebar>
</template>
