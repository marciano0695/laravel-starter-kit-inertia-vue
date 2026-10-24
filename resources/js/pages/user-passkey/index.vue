<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import type { BreadcrumbItem, Passkey } from "@/types";

import ManagePasskeys from "@/components/manage-passkeys.vue";
import AppLayout from "@/layouts/app-layout.vue";
import SettingsLayout from "@/layouts/settings/layout.vue";
import { index } from "@/wayfinder/routes/user-passkey";

interface Props {
  passkeys?: Passkey[];
}

withDefaults(defineProps<Props>(), {
  passkeys: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Passkeys",
    href: index.url(),
  },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Passkeys" />
    <SettingsLayout>
      <ManagePasskeys :passkeys="passkeys" />
    </SettingsLayout>
  </AppLayout>
</template>
