<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

import UserPasswordResetNotificationController from "@/actions/App/Http/Controllers/UserPasswordResetNotificationController";
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout.vue";
import { login } from "@/routes";

defineProps<{
  status?: string;
}>();
</script>

<template>
  <AuthLayout title="Forgot password" description="Enter your email to receive a password reset link">
    <Head title="Forgot password" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <div class="space-y-6">
      <Form v-slot="{ errors, processing }" v-bind="UserPasswordResetNotificationController.store.form()">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input id="email" type="email" name="email" autocomplete="off" autofocus placeholder="email@example.com" />
          <InputError :message="errors.email" />
        </div>

        <div class="my-6 flex items-center justify-start">
          <Button class="w-full" :disabled="processing" data-test="email-password-reset-link-button">
            <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
            Email password reset link
          </Button>
        </div>
      </Form>

      <div class="space-x-1 text-center text-sm text-muted-foreground">
        <span>Or, return to</span>
        <TextLink :href="login()"> log in </TextLink>
      </div>
    </div>
  </AuthLayout>
</template>
