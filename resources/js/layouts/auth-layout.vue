<script setup lang="ts">
import AuthSimpleLayout from "@/layouts/auth/auth-simple-layout.vue";

defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <AuthSimpleLayout :title="title" :description="description">
    <slot />
  </AuthSimpleLayout>
</template>
