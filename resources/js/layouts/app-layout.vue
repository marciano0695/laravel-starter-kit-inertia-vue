<script setup lang="ts">
import type { BreadcrumbItemType } from "@/types";

import AppSidebarLayout from "@/layouts/app/app-sidebar-layout.vue";

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppSidebarLayout :breadcrumbs="breadcrumbs">
    <slot />
  </AppSidebarLayout>
</template>
