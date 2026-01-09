<script lang="ts" setup>
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

import UserController from "@/actions/App/Http/Controllers/UserController";
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout.vue";
import { login } from "@/routes";
</script>

<template>
  <AuthLayout description="Enter your details below to create your account" title="Create an account">
    <Head title="Register" />

    <Form
      v-slot="{ errors, processing }"
      :reset-on-success="['password', 'password_confirmation']"
      class="flex flex-col gap-6"
      v-bind="UserController.store.form()"
    >
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="name">Name</Label>
          <Input
            id="name"
            :tabindex="1"
            autocomplete="name"
            autofocus
            name="name"
            placeholder="Full name"
            required
            type="text"
          />
          <InputError :message="errors.name" />
        </div>

        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            :tabindex="2"
            autocomplete="email"
            name="email"
            placeholder="email@example.com"
            required
            type="email"
          />
          <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
          <Label for="password">Password</Label>
          <Input
            id="password"
            :tabindex="3"
            autocomplete="new-password"
            name="password"
            placeholder="Password"
            required
            type="password"
          />
          <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
          <Label for="password_confirmation">Confirm password</Label>
          <Input
            id="password_confirmation"
            :tabindex="4"
            autocomplete="new-password"
            name="password_confirmation"
            placeholder="Confirm password"
            required
            type="password"
          />
          <InputError :message="errors.password_confirmation" />
        </div>

        <Button :disabled="processing" class="mt-2 w-full" data-test="register-user-button" tabindex="5" type="submit">
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
          Create account
        </Button>
      </div>

      <div class="text-center text-sm text-muted-foreground">
        Already have an account?
        <TextLink :href="login()" :tabindex="6" class="underline underline-offset-4"> Log in </TextLink>
      </div>
    </Form>
  </AuthLayout>
</template>
