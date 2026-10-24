<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { KeyRound } from "@lucide/vue";

import type { Passkey } from "@/types";

import HeadingSmall from "@/components/heading-small.vue";
import PasskeyItem from "@/components/passkey-item.vue";
import PasskeyRegister from "@/components/passkey-register.vue";

interface Props {
  passkeys?: Passkey[];
}

withDefaults(defineProps<Props>(), {
  passkeys: () => [],
});

function handleDelete(passkey: Passkey, onError: () => void) {
  router.delete(passkey.destroy_url, {
    preserveScroll: true,
    onError,
  });
}

// The new passkey is stored by the passkeys package, so reload to pick it up in the list.
function handleRegisterSuccess() {
  router.reload();
}
</script>

<template>
  <div class="space-y-6">
    <HeadingSmall title="Passkeys" description="Manage your passkeys for passwordless sign-in" />

    <div class="overflow-hidden rounded-lg border border-border">
      <template v-if="passkeys.length">
        <PasskeyItem v-for="passkey in passkeys" :key="passkey.id" :passkey="passkey" @remove="handleDelete" />
      </template>

      <div v-else class="p-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-muted">
          <KeyRound class="h-7 w-7 text-muted-foreground" />
        </div>
        <p class="font-medium">No passkeys yet</p>
        <p class="mt-1 text-sm text-muted-foreground">Add a passkey to sign in without a password</p>
      </div>
    </div>

    <PasskeyRegister @success="handleRegisterSuccess" />
  </div>
</template>
