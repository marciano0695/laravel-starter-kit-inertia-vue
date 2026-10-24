<script setup lang="ts">
import type { BreadcrumbItemType } from "@/types";

import AppContent from "@/components/app-content.vue";
import AppShell from "@/components/app-shell.vue";
import AppSidebarHeader from "@/components/app-sidebar-header.vue";
import AppSidebar from "@/components/app-sidebar.vue";
import { Toaster } from "@/components/ui/sonner";

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar" class="overflow-x-hidden">
      <AppSidebarHeader :breadcrumbs="breadcrumbs" />
      <slot />
    </AppContent>
    <Toaster />
  </AppShell>
</template>
