<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

import type { BreadcrumbItem, Passkey } from "@/types";

import ManagePasskeys from "@/components/manage-passkeys.vue";
import AppLayout from "@/layouts/app-layout.vue";
import SettingsLayout from "@/layouts/settings/layout.vue";
import { index } from "@/wayfinder/routes/user-passkey";

defineOptions({
  layout: [
    [
      AppLayout,
      {
        breadcrumbs: [
          {
            title: "Passkeys",
            href: index.url(),
          },
        ] satisfies BreadcrumbItem[],
      },
    ],
    SettingsLayout,
  ],
});

interface Props {
  passkeys?: Passkey[];
}

withDefaults(defineProps<Props>(), {
  passkeys: () => [],
});
</script>

<template>
  <div class="contents">
    <Head title="Passkeys" />
    <ManagePasskeys :passkeys="passkeys" />
  </div>
</template>
