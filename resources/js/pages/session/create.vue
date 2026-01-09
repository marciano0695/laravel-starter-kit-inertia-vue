<script lang="ts" setup>
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

import SessionController from "@/actions/App/Http/Controllers/SessionController";
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout.vue";
import { register } from "@/routes";
import { request } from "@/routes/password";

defineProps<{
  status?: string;
  canResetPassword: boolean;
}>();
</script>

<template>
  <AuthLayout description="Enter your email and password below to log in" title="Log in to your account">
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <Form
      v-slot="{ errors, processing }"
      :reset-on-success="['password']"
      class="flex flex-col gap-6"
      v-bind="SessionController.store.form()"
    >
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            :tabindex="1"
            autocomplete="email"
            autofocus
            name="email"
            placeholder="email@example.com"
            required
            type="email"
          />
          <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
          <div class="flex items-center justify-between">
            <Label for="password">Password</Label>
            <TextLink v-if="canResetPassword" :href="request()" :tabindex="5" class="text-sm">
              Forgot password?
            </TextLink>
          </div>
          <Input
            id="password"
            :tabindex="2"
            autocomplete="current-password"
            name="password"
            placeholder="Password"
            required
            type="password"
          />
          <InputError :message="errors.password" />
        </div>

        <div class="flex items-center justify-between">
          <Label class="flex items-center space-x-3" for="remember">
            <Checkbox id="remember" :tabindex="3" name="remember" />
            <span>Remember me</span>
          </Label>
        </div>

        <Button :disabled="processing" :tabindex="4" class="mt-4 w-full" data-test="login-button" type="submit">
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
          Log in
        </Button>
      </div>

      <div class="text-center text-sm text-muted-foreground">
        Don't have an account?
        <TextLink :href="register()" :tabindex="5"> Sign up </TextLink>
      </div>
    </Form>
  </AuthLayout>
</template>
