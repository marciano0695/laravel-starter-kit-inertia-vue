<script lang="ts" setup>
import { Head } from "@inertiajs/vue3";

import type { BreadcrumbItem } from "@/types";

import AppearanceTabs from "@/components/appearance-tabs.vue";
import HeadingSmall from "@/components/heading-small.vue";
import AppLayout from "@/layouts/app-layout.vue";
import SettingsLayout from "@/layouts/settings/layout.vue";
import { edit } from "@/routes/appearance";

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: "Appearance settings",
    href: edit().url,
  },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Appearance settings" />

    <SettingsLayout>
      <div class="space-y-6">
        <HeadingSmall description="Update your account's appearance settings" title="Appearance settings" />
        <AppearanceTabs />
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
