<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

import UserEmailVerificationNotificationController from "@/actions/App/Http/Controllers/UserEmailVerificationNotificationController";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import AuthLayout from "@/layouts/auth-layout.vue";
import { logout } from "@/routes";

defineProps<{
  status?: string;
}>();
</script>

<template>
  <AuthLayout
    title="Verify email"
    description="Please verify your email address by clicking on the link we just emailed to you."
  >
    <Head title="Email verification" />

    <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
      A new verification link has been sent to the email address you provided during registration.
    </div>

    <Form
      v-slot="{ processing }"
      v-bind="UserEmailVerificationNotificationController.store.form()"
      class="space-y-6 text-center"
    >
      <Button :disabled="processing" variant="secondary">
        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
        Resend verification email
      </Button>

      <TextLink :href="logout()" as="button" class="mx-auto block text-sm"> Log out </TextLink>
    </Form>
  </AuthLayout>
</template>
