<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { LayoutGrid } from "@lucide/vue";

import type { NavItem } from "@/types";

import NavMain from "@/components/nav-main.vue";
import NavUser from "@/components/nav-user.vue";
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar";
import { dashboard } from "@/wayfinder/routes";

import AppLogo from "./app-logo.vue";

const mainNavItems: NavItem[] = [
  {
    title: "Dashboard",
    href: dashboard(),
    icon: LayoutGrid,
  },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="dashboard()">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
