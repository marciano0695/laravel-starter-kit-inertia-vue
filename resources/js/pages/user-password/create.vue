<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "@lucide/vue";
import { ref } from "vue";

import type { PasswordRequirements } from "@/types";

import InputError from "@/components/input-error.vue";
import PasswordInput from "@/components/password-input.vue";
import PasswordRequirementsList from "@/components/password-requirements.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout.vue";
import UserPasswordController from "@/wayfinder/App/Http/Controllers/UserPasswordController";

const props = defineProps<{
  token: string;
  email: string;
  passwordRequirements: PasswordRequirements;
}>();

const inputEmail = ref(props.email);
</script>

<template>
  <AuthLayout title="Reset password" description="Please enter your new password below">
    <Head title="Reset password" />

    <Form
      v-slot="{ errors, processing }"
      v-bind="UserPasswordController.store.form()"
      :transform="(data) => ({ ...data, token, email })"
      :reset-on-success="['password', 'password_confirmation']"
    >
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input
            id="email"
            v-model="inputEmail"
            type="email"
            name="email"
            autocomplete="email"
            class="mt-1 block w-full"
            readonly
          />
          <InputError :message="errors.email" class="mt-2" />
        </div>

        <div class="grid gap-2">
          <Label for="password">Password</Label>
          <PasswordInput
            id="password"
            aria-describedby="password-hints"
            name="password"
            autocomplete="new-password"
            class="mt-1 block w-full"
            autofocus
            placeholder="Password"
            :passwordrules="passwordRequirements.rules"
          />
          <PasswordRequirementsList id="password-hints" :hints="passwordRequirements.hints" />
          <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
          <Label for="password_confirmation"> Confirm Password </Label>
          <PasswordInput
            id="password_confirmation"
            name="password_confirmation"
            autocomplete="new-password"
            class="mt-1 block w-full"
            placeholder="Confirm password"
            :passwordrules="passwordRequirements.rules"
          />
          <InputError :message="errors.password_confirmation" />
        </div>

        <Button type="submit" class="mt-4 w-full" :disabled="processing" data-test="reset-password-button">
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
          Reset password
        </Button>
      </div>
    </Form>
  </AuthLayout>
</template>
