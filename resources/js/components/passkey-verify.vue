<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { usePasskeyVerify } from "@laravel/passkeys/vue";
import { KeyRound, LoaderCircle } from "@lucide/vue";

import InputError from "@/components/input-error.vue";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { dashboard } from "@/wayfinder/routes";

interface Props {
  // Login or confirm endpoints, shared by the server only while passkeys are enabled.
  routes: {
    options: string;
    submit: string;
  };
  label?: string;
  loadingLabel?: string;
  separator?: string;
}

const props = withDefaults(defineProps<Props>(), {
  label: "Sign in with a passkey",
  loadingLabel: "Authenticating...",
  separator: "Or continue with email",
});

const { verify, isLoading, error, isSupported } = usePasskeyVerify({
  routes: props.routes,
  onSuccess: (response) => {
    router.visit(response.redirect ?? dashboard());
  },
});
</script>

<template>
  <div v-if="isSupported">
    <div class="grid gap-2">
      <Button
        type="button"
        variant="outline"
        class="w-full"
        :disabled="isLoading"
        data-test="passkey-verify-button"
        @click="verify"
      >
        <LoaderCircle v-if="isLoading" class="h-4 w-4 animate-spin" />
        <KeyRound v-else class="h-4 w-4" />
        {{ isLoading ? loadingLabel : label }}
      </Button>

      <div v-if="error" class="text-center">
        <InputError :message="error" />
      </div>
    </div>

    <div class="relative my-6">
      <div class="absolute inset-0 flex items-center">
        <Separator class="w-full" />
      </div>
      <div class="relative flex justify-center text-xs uppercase">
        <span class="bg-background px-2 text-muted-foreground">
          {{ separator }}
        </span>
      </div>
    </div>
  </div>
</template>
