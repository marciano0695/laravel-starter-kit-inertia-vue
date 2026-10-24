<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import type { BreadcrumbItem } from "@/types";

import AppLayout from "@/layouts/app-layout.vue";
import { dashboard } from "@/wayfinder/routes";

import PlaceholderPattern from "../components/placeholder-pattern.vue";

defineOptions({
  layout: [
    AppLayout,
    {
      breadcrumbs: [
        {
          title: "Dashboard",
          href: dashboard().url,
        },
      ] satisfies BreadcrumbItem[],
    },
  ],
});
</script>

<template>
  <div class="contents">
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
          <PlaceholderPattern />
        </div>
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
          <PlaceholderPattern />
        </div>
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
          <PlaceholderPattern />
        </div>
      </div>
      <div
        class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
      >
        <PlaceholderPattern />
      </div>
    </div>
  </div>
</template>
