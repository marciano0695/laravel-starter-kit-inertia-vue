<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";

import type { NavItem } from "@/types";

import Heading from "@/components/heading.vue";
import { Button } from "@/components/ui/button";
import { toUrl, urlIsActive } from "@/lib/utils";
import { edit as editPassword } from "@/wayfinder/routes/password";
import { show } from "@/wayfinder/routes/two-factor";
import { index as indexPasskeys } from "@/wayfinder/routes/user-passkey";
import { edit as editProfile } from "@/wayfinder/routes/user-profile";

const page = usePage();

const settingsNavItems: NavItem[] = [
  {
    title: "Profile",
    href: editProfile(),
  },
  {
    title: "Password",
    href: editPassword(),
  },
  {
    title: "Two-Factor Auth",
    href: show(),
  },
  // Passkeys are opt-in, so the tab only shows while the feature is enabled.
  ...(page.props.passkeyRoutes
    ? [
        {
          title: "Passkeys",
          href: indexPasskeys(),
        },
      ]
    : []),
];
</script>

<template>
  <div class="px-4 py-6">
    <Heading title="Settings" description="Manage your profile and account settings" />

    <nav aria-label="Settings" class="mb-8 flex flex-wrap gap-2">
      <Button
        v-for="item in settingsNavItems"
        :key="toUrl(item.href)"
        :variant="urlIsActive(item.href, page.url) ? 'default' : 'secondary'"
        class="rounded-full px-5"
        as-child
      >
        <Link :href="item.href" :aria-current="urlIsActive(item.href, page.url) ? 'page' : undefined">
          {{ item.title }}
        </Link>
      </Button>
    </nav>

    <!-- Gap rather than space-y, so pages can wrap their sections in a display: contents root -->
    <section class="flex max-w-xl flex-col gap-12">
      <slot />
    </section>
  </div>
</template>
