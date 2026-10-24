<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";

import type { BreadcrumbItem, PasswordRequirements } from "@/types";

import HeadingSmall from "@/components/heading-small.vue";
import InputError from "@/components/input-error.vue";
import PasswordInput from "@/components/password-input.vue";
import PasswordRequirementsList from "@/components/password-requirements.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout.vue";
import SettingsLayout from "@/layouts/settings/layout.vue";
import UserPasswordController from "@/wayfinder/App/Http/Controllers/UserPasswordController";
import { edit } from "@/wayfinder/routes/password";

defineOptions({
  layout: [
    [
      AppLayout,
      {
        breadcrumbs: [
          {
            title: "Password settings",
            href: edit().url,
          },
        ] satisfies BreadcrumbItem[],
      },
    ],
    SettingsLayout,
  ],
});

defineProps<{
  passwordRequirements: PasswordRequirements;
}>();
</script>

<template>
  <div class="contents">
    <Head title="Password settings" />

    <div class="space-y-6">
      <HeadingSmall
        title="Update password"
        description="Ensure your account is using a long, random password to stay secure"
      />

      <Form
        v-slot="{ errors, processing, recentlySuccessful }"
        v-bind="UserPasswordController.update.form()"
        :options="{
          preserveScroll: true,
        }"
        reset-on-success
        :reset-on-error="['password', 'password_confirmation', 'current_password']"
        class="space-y-6"
      >
        <div class="grid gap-2">
          <Label for="current_password">Current password</Label>
          <PasswordInput
            id="current_password"
            name="current_password"
            class="mt-1 block w-full"
            autocomplete="current-password"
            placeholder="Current password"
          />
          <InputError :message="errors.current_password" />
        </div>

        <div class="grid gap-2">
          <Label for="password">New password</Label>
          <PasswordInput
            id="password"
            aria-describedby="password-hints"
            name="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
            placeholder="New password"
            :passwordrules="passwordRequirements.rules"
          />
          <PasswordRequirementsList id="password-hints" :hints="passwordRequirements.hints" />
          <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
          <Label for="password_confirmation">Confirm password</Label>
          <PasswordInput
            id="password_confirmation"
            name="password_confirmation"
            class="mt-1 block w-full"
            autocomplete="new-password"
            placeholder="Confirm password"
            :passwordrules="passwordRequirements.rules"
          />
          <InputError :message="errors.password_confirmation" />
        </div>

        <div class="flex items-center gap-4">
          <Button :disabled="processing" data-test="update-password-button"> Save password </Button>

          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-show="recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
          </Transition>
        </div>
      </Form>
    </div>
  </div>
</template>
