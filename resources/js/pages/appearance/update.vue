<script lang="ts" setup>
import { Head } from "@inertiajs/vue3";

import type { BreadcrumbItem } from "@/types";

import AppearanceTabs from "@/components/appearance-tabs.vue";
import HeadingSmall from "@/components/heading-small.vue";
import AppLayout from "@/layouts/app-layout.vue";
import SettingsLayout from "@/layouts/settings/layout.vue";
import { edit } from "@/wayfinder/routes/appearance";

defineOptions({
  layout: [
    [
      AppLayout,
      {
        breadcrumbs: [
          {
            title: "Appearance settings",
            href: edit().url,
          },
        ] satisfies BreadcrumbItem[],
      },
    ],
    SettingsLayout,
  ],
});
</script>

<template>
  <div class="contents">
    <Head title="Appearance settings" />

    <div class="space-y-6">
      <HeadingSmall description="Update your account's appearance settings" title="Appearance settings" />
      <AppearanceTabs />
    </div>
  </div>
</template>
