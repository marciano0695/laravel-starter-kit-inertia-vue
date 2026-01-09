<script setup lang="ts">
import type { BreadcrumbItemType } from "@/types";

import AppContent from "@/components/app-content.vue";
import AppHeader from "@/components/app-header.vue";
import AppShell from "@/components/app-shell.vue";

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppShell class="flex-col">
    <AppHeader :breadcrumbs="breadcrumbs" />
    <AppContent>
      <slot />
    </AppContent>
  </AppShell>
</template>
