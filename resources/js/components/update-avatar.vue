<script setup lang="ts">
import { Form, Link } from "@inertiajs/vue3";
import { ref } from "vue";

import type { App } from "@/wayfinder/types";

import HeadingSmall from "@/components/heading-small.vue";
import InputError from "@/components/input-error.vue";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { getInitials } from "@/composables/use-initials";
import UserAvatarController from "@/wayfinder/App/Http/Controllers/UserAvatarController";

defineProps<{
  user: App.Models.User;
}>();

const avatarInput = ref<HTMLInputElement | null>(null);
</script>

<template>
  <div class="space-y-6">
    <HeadingSmall title="Profile picture" description="Upload a JPG, PNG, GIF or WebP image up to 2 MB" />

    <Form
      v-slot="{ errors, processing, submit }"
      v-bind="UserAvatarController.update.form()"
      :options="{
        preserveScroll: true,
      }"
      reset-on-success
      reset-on-error
      class="space-y-2"
    >
      <div class="flex items-center gap-4">
        <Avatar class="size-16 overflow-hidden rounded-full">
          <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
          <AvatarFallback class="rounded-full text-lg text-black dark:text-white">
            {{ getInitials(user.name) }}
          </AvatarFallback>
        </Avatar>

        <!-- Uploads as soon as a file is picked; the button stands in for the native file input. -->
        <input
          id="avatar"
          ref="avatarInput"
          type="file"
          name="avatar"
          accept="image/jpeg,image/png,image/gif,image/webp"
          class="hidden"
          @change="submit()"
        />

        <Button
          type="button"
          variant="outline"
          :disabled="processing"
          data-test="upload-avatar-button"
          @click="avatarInput?.click()"
        >
          {{ processing ? "Uploading..." : "Upload photo" }}
        </Button>

        <Button v-if="user.avatar" variant="ghost" :disabled="processing" as-child>
          <Link :href="UserAvatarController.destroy()" as="button" preserve-scroll data-test="remove-avatar-button">
            Remove
          </Link>
        </Button>
      </div>

      <InputError :message="errors.avatar" />
    </Form>
  </div>
</template>
